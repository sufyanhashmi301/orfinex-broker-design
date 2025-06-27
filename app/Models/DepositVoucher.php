<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code',
        'amount',
        'expiry_date',
        'description',
        'status',
        'used_by',
        'modal',
        'used_date'
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
        'used_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->status !== 'used' && $this->expiry_date < now();
    }
}
