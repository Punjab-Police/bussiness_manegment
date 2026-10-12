<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class StockService
{
    /**
     * Get the current stock quantity of a product.
     */
    public function currentStock(Product $product): float
    {
        return (float) StockMovement::where('product_id', $product->id)
            ->sum('quantity');
    }

    /**
     * Add stock to a product.
     */
    public function stockIn(
        Product $product,
        float $quantity,
        float $unitCost = 0,
        string $type = 'stock_in',
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $movementDate = null,
        ?string $notes = null
    ): StockMovement {
        if ($quantity <= 0) {
            throw new InvalidArgumentException(
                'Stock-in quantity must be greater than zero.'
            );
        }

        return DB::transaction(function () use (
            $product,
            $quantity,
            $unitCost,
            $type,
            $referenceType,
            $referenceId,
            $movementDate,
            $notes
        ) {
            // Lock the product so simultaneous stock operations
            // for the same product are processed safely.
            $lockedProduct = Product::whereKey($product->id)
                ->lockForUpdate()
                ->firstOrFail();

            return StockMovement::create([
                'product_id' => $lockedProduct->id,
                'user_id' => auth()->id(),
                'type' => $type,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'movement_date' => $movementDate ?? now()->toDateString(),
                'notes' => $notes,
            ]);
        });
    }

    /**
     * Remove stock from a product.
     */
    public function stockOut(
        Product $product,
        float $quantity,
        float $unitCost = 0,
        string $type = 'stock_out',
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $movementDate = null,
        ?string $notes = null
    ): StockMovement {
        if ($quantity <= 0) {
            throw new InvalidArgumentException(
                'Stock-out quantity must be greater than zero.'
            );
        }

        return DB::transaction(function () use (
            $product,
            $quantity,
            $unitCost,
            $type,
            $referenceType,
            $referenceId,
            $movementDate,
            $notes
        ) {
            $lockedProduct = Product::whereKey($product->id)
                ->lockForUpdate()
                ->firstOrFail();

            $currentStock = $this->currentStock($lockedProduct);

            if ($quantity > $currentStock) {
                throw new RuntimeException(
                    "Insufficient stock. Available stock: {$currentStock}."
                );
            }

            return StockMovement::create([
                'product_id' => $lockedProduct->id,
                'user_id' => auth()->id(),
                'type' => $type,
                'quantity' => -$quantity,
                'unit_cost' => $unitCost,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'movement_date' => $movementDate ?? now()->toDateString(),
                'notes' => $notes,
            ]);
        });
    }

    /**
     * Adjust stock by a positive or negative quantity.
     */
    public function adjustment(
        Product $product,
        float $difference,
        float $unitCost = 0,
        ?string $movementDate = null,
        ?string $notes = null
    ): StockMovement {
        if ($difference == 0) {
            throw new InvalidArgumentException(
                'Adjustment quantity cannot be zero.'
            );
        }

        return DB::transaction(function () use (
            $product,
            $difference,
            $unitCost,
            $movementDate,
            $notes
        ) {
            $lockedProduct = Product::whereKey($product->id)
                ->lockForUpdate()
                ->firstOrFail();

            $currentStock = $this->currentStock($lockedProduct);

            $newStock = $currentStock + $difference;

            if ($newStock < 0) {
                throw new RuntimeException(
                    "Adjustment would make stock negative. Current stock: {$currentStock}."
                );
            }

            return StockMovement::create([
                'product_id' => $lockedProduct->id,
                'user_id' => auth()->id(),
                'type' => 'adjustment',
                'quantity' => $difference,
                'unit_cost' => $unitCost,
                'reference_type' => null,
                'reference_id' => null,
                'movement_date' => $movementDate ?? now()->toDateString(),
                'notes' => $notes,
            ]);
        });
    }
}