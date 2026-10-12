<?php

namespace App\Models;

use App\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use Auditable;

    protected $fillable = [
        'category_id',
        'unit_id',
        'name',
        'sku',
        'purchase_price',
        'sale_price',
        'minimum_stock',
        'status',
        'description',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'minimum_stock' => 'decimal:3',
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getCurrentStockAttribute(): float
    {
        return (float) $this->stockMovements()->sum('quantity');
    }
}
