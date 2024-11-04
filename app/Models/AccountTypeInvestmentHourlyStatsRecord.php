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
        'account_name',
        'platform_group',
        'balance',
        'current_equity',
        'credit',
        'prev_day_balance',
        'prev_day_equity',
        'today_pnl_realized',
        'today_pnl_unrealized',
        'total_pnl',
        'trading_days',
        'max_balance',
        'created_at',    
    ];

    public function accountTypeInvestment() {
        return $this->belongsTo(AccountTypeInvestment::class);
    }
}
