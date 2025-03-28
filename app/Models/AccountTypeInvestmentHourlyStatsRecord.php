<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTypeInvestmentHourlyStatsRecord extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'account_type_investment_id',       
        'balance',
        'current_equity',
        'trading_days',
        'created_at',    
    ];

    public function accountTypeInvestment() {
        return $this->belongsTo(AccountTypeInvestment::class);
    }
}
