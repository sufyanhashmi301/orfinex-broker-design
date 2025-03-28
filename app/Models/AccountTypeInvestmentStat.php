<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTypeInvestmentStat extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [    
        'account_type_investment_id',
        'balance',
        'current_equity',
        'trading_days',
        'updated_at',    
    ];

    public function accountTypeInvestment() {
        return $this->belongsTo(AccountTypeInvestment::class);
        
    }
}
