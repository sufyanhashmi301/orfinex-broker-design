<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LatestTradeLog extends Model
{
    use HasFactory;

    protected $fillable = [    
        'account_type_investment_id',
        'balance',
        'current_equity',
        'trading_days',  
    ];

    public function accountTypeInvestment() {
        return $this->belongsTo(AccountTypeInvestment::class);
    }
}
