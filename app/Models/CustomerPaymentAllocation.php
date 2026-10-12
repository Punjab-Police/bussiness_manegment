<?php

namespace App\Models;

use App\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerPaymentAllocation extends Model
{
    use Auditable;

    protected $fillable = [
        'customer_payment_id',
        'sale_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function customerPayment(): BelongsTo
    {
        return $this->belongsTo(
            CustomerPayment::class
        );
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}