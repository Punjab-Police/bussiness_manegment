<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\SupplierPayment;
use App\Models\SupplierPaymentAllocation;

class PurchaseController extends Controller
{
    public function __construct(
        private StockService $stockService
    ) {}

    public function create(): View
    {
        $suppliers = Supplier::where('status', true)
            ->orderBy('name')
            ->get();

        $products = Product::where('status', true)
            ->with(['unit'])
            ->orderBy('name')
            ->get();

        return view('purchases.create', compact(
            'suppliers',
            'products'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => [
                'required',
                'exists:suppliers,id',
            ],

            'invoice_number' => [
                'required',
                'string',
                'max:255',
                'unique:purchases,invoice_number',
            ],

            'purchase_date' => [
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

            'paid_amount' => [
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
                'exists:products,id',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'items.*.unit_cost' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Supplier
        |--------------------------------------------------------------------------
        */

        $supplier = Supplier::where('id', $validated['supplier_id'])
            ->where('status', true)
            ->first();

        if (! $supplier) {
            throw ValidationException::withMessages([
                'supplier_id' => 'The selected supplier is inactive.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Prepare Products
        |--------------------------------------------------------------------------
        */

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
                'items' => 'One or more selected products are inactive or invalid.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Purchase Totals
        |--------------------------------------------------------------------------
        */

        $subtotal = 0;

        $items = [];

        foreach ($validated['items'] as $item) {

            $quantity = (float) $item['quantity'];
            $unitCost = (float) $item['unit_cost'];

            $lineTotal = round(
                $quantity * $unitCost,
                2
            );

            $subtotal += $lineTotal;

            $items[] = [
                'product_id' => (int) $item['product_id'],
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'discount' => 0,
                'tax' => 0,
                'total' => $lineTotal,
            ];
        }

        $discount = (float) $validated['discount'];
        $tax = (float) $validated['tax'];
        $paidAmount = (float) $validated['paid_amount'];

        $totalAmount = round(
            $subtotal - $discount + $tax,
            2
        );

        if ($totalAmount < 0) {
            throw ValidationException::withMessages([
                'discount' => 'Discount cannot make the purchase total negative.',
            ]);
        }

        if ($paidAmount > $totalAmount) {
            throw ValidationException::withMessages([
                'paid_amount' => 'Paid amount cannot be greater than the purchase total.',
            ]);
        }

        $dueAmount = round(
            $totalAmount - $paidAmount,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Database Transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $items,
            $subtotal,
            $discount,
            $tax,
            $totalAmount,
            $paidAmount,
            $dueAmount,
            $products
        ) {
            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'user_id' => auth()->id(),
                'invoice_number' => $validated['invoice_number'],
                'purchase_date' => $validated['purchase_date'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total_amount' => $totalAmount,

                // Kept temporarily for compatibility.
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,

                'status' => 'posted',
                'notes' => $validated['notes'] ?? null,
            ]);

            /*
    |--------------------------------------------------------------------------
    | Purchase Items + Stock
    |--------------------------------------------------------------------------
    */

            foreach ($items as $item) {

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'discount' => $item['discount'],
                    'tax' => $item['tax'],
                    'total' => $item['total'],
                ]);

                $product = $products->get($item['product_id']);

                $this->stockService->stockIn(
                    product: $product,
                    quantity: $item['quantity'],
                    unitCost: $item['unit_cost'],
                    type: 'purchase',
                    referenceType: Purchase::class,
                    referenceId: $purchase->id,
                    movementDate: $validated['purchase_date'],
                    notes: 'Purchase invoice: ' . $purchase->invoice_number
                );
            }

            /*
    |--------------------------------------------------------------------------
    | Immediate Supplier Payment
    |--------------------------------------------------------------------------
    */

            if ($paidAmount > 0) {

                $payment = SupplierPayment::create([
                    'supplier_id' => $purchase->supplier_id,
                    'user_id' => auth()->id(),
                    'payment_date' => $validated['purchase_date'],
                    'amount' => $paidAmount,
                    'payment_method' => 'cash',
                    'reference_number' => 'PUR-' . $purchase->invoice_number,
                    'notes' => 'Automatic payment against purchase invoice '
                        . $purchase->invoice_number,
                ]);

                SupplierPaymentAllocation::create([
                    'supplier_payment_id' => $payment->id,
                    'purchase_id' => $purchase->id,
                    'amount' => $paidAmount,
                ]);
            }
        });

        return redirect()
            ->route('purchases.create')
            ->with(
                'success',
                'Purchase posted successfully and stock has been increased.'
            );
    }


    public function index(): View
    {
        $purchases = Purchase::with([
            'supplier',
            'user',
        ])
            ->latest('purchase_date')
            ->latest('id')
            ->paginate(15);

        return view('purchases.index', compact('purchases'));
    }

    public function show(Purchase $purchase): View
    {
        $purchase->load([
            'supplier',
            'user',
            'items.product.unit',
        ]);

        return view('purchases.show', compact('purchase'));
    }
}
