<?php

namespace App\Models;

use App\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use Auditable;

    protected $fillable = [
        'supplier_id',
        'user_id',
        'invoice_number',
        'purchase_date',
        'subtotal',
        'discount',
        'tax',
        'total_amount',
        'paid_amount',
        'due_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function paymentAllocations(): HasMany
    {
        return $this->hasMany(
            SupplierPaymentAllocation::class
        );
    }

    public function supplierPayments(): BelongsToMany
    {
        return $this->belongsToMany(
            SupplierPayment::class,
            'supplier_payment_allocations'
        )
        ->withPivot('amount')
        ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Calculations
    |--------------------------------------------------------------------------
    */

    public function getAllocatedAmount(): float
    {
        return (float) $this->paymentAllocations()
            ->sum('amount');
    }

    public function getOutstandingAmount(): float
    {
        return max(
            0,
            (float) $this->total_amount
                - $this->getAllocatedAmount()
        );
    }

    public function getOutstandingAmountAttribute(): float
    {
        return $this->getOutstandingAmount();
    }
}