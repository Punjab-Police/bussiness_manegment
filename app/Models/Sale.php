<?php

namespace App\Models;

use App\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use Auditable;

    protected $fillable = [
        'customer_id',
        'user_id',
        'invoice_number',
        'sale_date',
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
        'sale_date' => 'date',
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Customer Payment Allocations
    |--------------------------------------------------------------------------
    */

    public function paymentAllocations(): HasMany
    {
        return $this->hasMany(
            CustomerPaymentAllocation::class
        );
    }

    public function customerPayments(): BelongsToMany
    {
        return $this->belongsToMany(
            CustomerPayment::class,
            'customer_payment_allocations'
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