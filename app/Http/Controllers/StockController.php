<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Services\StockService;

class StockController extends Controller
{


    public function __construct(
        private StockService $stockService
    ) {}

    public function index(): View
    {
        $movements = StockMovement::with([
            'product',
            'user',
        ])
            ->latest('movement_date')
            ->latest('id')
            ->paginate(20);

        return view('stock.index', compact('movements'));
    }

    public function opening(): View
    {
        $products = Product::where('status', true)
            ->with(['unit'])
            ->orderBy('name')
            ->get();

        return view('stock.opening', compact('products'));
    }

    public function storeOpening(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'exists:products,id',
            ],

            'quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'unit_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'movement_date' => [
                'required',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $this->stockService->stockIn(
            product: $product,
            quantity: (float) $validated['quantity'],
            unitCost: (float) $validated['unit_cost'],
            type: 'opening',
            movementDate: $validated['movement_date'],
            notes: $validated['notes'] ?? null
        );

        return redirect()
            ->route('stock.index')
            ->with('success', 'Opening stock recorded successfully.');
    }

    public function summary(): View
    {
        $products = Product::with(['unit', 'category'])
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view('stock.summary', compact('products'));
    }

    public function adjustment(): View
    {
        $products = Product::where('status', true)
            ->with(['unit'])
            ->orderBy('name')
            ->get();

        return view('stock.adjustment', compact('products'));
    }
    public function storeAdjustment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'exists:products,id',
            ],

            'difference' => [
                'required',
                'numeric',
                'not_in:0',
            ],

            'unit_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'movement_date' => [
                'required',
                'date',
            ],

            'notes' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $this->stockService->adjustment(
            product: $product,
            difference: (float) $validated['difference'],
            unitCost: (float) $validated['unit_cost'],
            movementDate: $validated['movement_date'],
            notes: $validated['notes']
        );

        return redirect()
            ->route('stock.index')
            ->with('success', 'Stock adjustment recorded successfully.');
    }
}
