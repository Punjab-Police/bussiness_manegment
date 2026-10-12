<?php

namespace App\Models;

use App\Auditable;
use Illuminate\Database\Eloquent\Model;

class TestAudit extends Model
{
    use Auditable;

    protected $fillable = [
        'name',
        'amount',
    ];
}