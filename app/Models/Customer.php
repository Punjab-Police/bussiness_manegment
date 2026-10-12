<?php

namespace App\Models;

use App\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use Auditable;

    protected $fillable = [
        'name',
        'company_name',
        'phone',
        'email',
        'address',
        'opening_balance',
        'status',
        'notes',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'status' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(CustomerPayment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Balance Calculations
    |--------------------------------------------------------------------------
    */

    public function getTotalSalesAttribute(): float
    {
        return (float) $this->sales()
            ->where('status', 'posted')
            ->sum('total_amount');
    }

    public function getTotalPaymentsAttribute(): float
    {
        return (float) $this->payments()
            ->sum('amount');
    }

    public function getBalanceAttribute(): float
    {
        return max(
            0,
            (float) $this->opening_balance
            + $this->total_sales
            - $this->total_payments
        );
    }

 
}