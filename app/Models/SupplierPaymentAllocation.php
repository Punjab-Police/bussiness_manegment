<?php

namespace App\Models;

use App\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierPaymentAllocation extends Model
{
    use Auditable;

    protected $fillable = [
        'supplier_payment_id',
        'purchase_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function supplierPayment(): BelongsTo
    {
        return $this->belongsTo(
            SupplierPayment::class
        );
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(
            Purchase::class
        );
    }
}