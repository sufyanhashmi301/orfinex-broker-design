<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountBalanceOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_type_investment_id',
        'amount',
        'operation',
        'affect_stats',
        'comments',
    ];

    public function account() {
        return $this->belongsTo(AccountTypeInvestment::class);
    }
}
