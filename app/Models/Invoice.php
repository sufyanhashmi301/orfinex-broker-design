<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $casts = [
        'package_discount' => 'array',
        'coupon_code_discount' => 'array',
        'addon' => 'array',
    ];

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }
}
