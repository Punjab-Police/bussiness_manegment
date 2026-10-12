<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\SaleItem;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index(): View
    {
        $sales = Sale::with(['customer', 'user'])
            ->latest('sale_date')
            ->latest('id')
            ->paginate(15);

        return view('sales.index', compact('sales'));
    }

    public function create(): View
    {
        $customers = Customer::where('status', true)
            ->orderBy('name')
            ->get();

        $products = Product::where('status', true)
            ->with('unit')
            ->orderBy('name')
            ->get();

        // Display current stock for product selection.
        $stock = app(\App\Services\StockService::class);

        $products->each(function ($product) use ($stock) {
            $product->available_stock =
                $stock->currentStock($product);
        });

        return view(
            'sales.create',
            compact('customers', 'products')
        );
    }

    public function show(Sale $sale): View
    {
        $sale->load([
            'customer',
            'user',
            'items.product.unit',
        ]);

        return view('sales.show', compact('sale'));
    }
    
public function store(
    Request $request,
    StockService $stockService
): RedirectResponse {
    $validated = $request->validate([
        'customer_id' => [
            'required',
            'integer',
            'exists:customers,id',
        ],
        'invoice_number' => [
            'required',
            'string',
            'max:255',
            'unique:sales,invoice_number',
        ],
        'sale_date' => [
            'required',
            'date',
        ],
        'discount' => [
            'required',
            'numeric',
            'min:0',
        ],
        'tax' => [
            'required',
            'numeric',
            'min:0',
        ],
        'notes' => [
            'nullable',
            'string',
            'max:2000',
        ],
        'items' => [
            'required',
            'array',
            'min:1',
        ],
        'items.*.product_id' => [
            'required',
            'integer',
            'distinct',
            'exists:products,id',
        ],
        'items.*.quantity' => [
            'required',
            'numeric',
            'gt:0',
        ],
        'items.*.unit_price' => [
            'required',
            'numeric',
            'min:0',
        ],
    ]);

    $customer = \App\Models\Customer::whereKey(
        $validated['customer_id']
    )->where('status', true)->first();

    if (! $customer) {
        throw ValidationException::withMessages([
            'customer_id' => 'The selected customer is inactive.',
        ]);
    }

    $productIds = collect($validated['items'])
        ->pluck('product_id')
        ->unique()
        ->values();

    $products = Product::whereIn('id', $productIds)
        ->where('status', true)
        ->get()
        ->keyBy('id');

    if ($products->count() !== $productIds->count()) {
        throw ValidationException::withMessages([
            'items' => 'One or more products are inactive or invalid.',
        ]);
    }

    $items = [];
    $subtotal = 0;

    foreach ($validated['items'] as $item) {
        $quantity = (float) $item['quantity'];
        $unitPrice = (float) $item['unit_price'];

        $lineTotal = round($quantity * $unitPrice, 2);
        $subtotal = round($subtotal + $lineTotal, 2);

        $items[] = [
            'product_id' => (int) $item['product_id'],
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'discount' => 0,
            'tax' => 0,
            'total' => $lineTotal,
        ];
    }

    $discount = (float) $validated['discount'];
    $tax = (float) $validated['tax'];

    $totalAmount = round(
        $subtotal - $discount + $tax,
        2
    );

    if ($discount > $subtotal) {
        throw ValidationException::withMessages([
            'discount' => 'Discount cannot exceed the subtotal.',
        ]);
    }

    if ($totalAmount < 0) {
        throw ValidationException::withMessages([
            'discount' => 'The invoice total cannot be negative.',
        ]);
    }

    $sale = DB::transaction(function () use (
        $validated,
        $items,
        $products,
        $subtotal,
        $discount,
        $tax,
        $totalAmount,
        $stockService
    ) {
        // Create invoice with no payment recorded yet.
        $sale = Sale::create([
            'customer_id' => $validated['customer_id'],
            'user_id' => auth()->id(),
            'invoice_number' => $validated['invoice_number'],
            'sale_date' => $validated['sale_date'],
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'due_amount' => $totalAmount,
            'status' => 'posted',
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($items as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount' => $item['discount'],
                'tax' => $item['tax'],
                'total' => $item['total'],
            ]);

            $product = $products->get($item['product_id']);

            $stockService->stockOut(
                product: $product,
                quantity: $item['quantity'],
                unitCost: (float) $product->purchase_price,
                type: 'sale',
                referenceType: Sale::class,
                referenceId: $sale->id,
                movementDate: $validated['sale_date'],
                notes: 'Sales invoice: ' . $sale->invoice_number
            );
        }

        return $sale;
    });

    return redirect()
        ->route('sales.show', $sale)
        ->with('success', 'Sales invoice created successfully.');
}
}