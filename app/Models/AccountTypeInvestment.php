<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTypeInvestment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'currency',
        'account_type_phase_rule_id',
        'trader_type',
        'login',
        'total',
        'platform_group',
        'phase_started_at',
        'phase_ended_at',
        'main_password',
        'status',
    ];

    public function accountTypePhaseRule() {
        return $this->belongsTo(AccountTypePhaseRule::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function accountTypeInvestmentStat(){
        return $this->hasOne(AccountTypeInvestmentStat::class);
    }

    public function accountTypeInvestmentHourlyStatsRecord(){
        return $this->hasMany(AccountTypeInvestmentHourlyStatsRecord::class);
    }

    public function accountTypeInvestmentSnapshot() {
        return $this->hasOne(AccountTypeInvestmentSnapshot::class);
    }

    public function scopeTraderType($query) {
        return $query->where('trader_type', setting('active_trader_type', 'features'));
    }

   
    
}
