<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTypeInvestment extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_id',
        'user_id',
        'currency',
        'account_type_id',
        'account_type_phase_id',
        'account_type_phase_rule_id',
        'trader_type',
        'login',
        'total',
        'platform_group',
        'phase_started_at',
        'phase_ended_at',
        'main_password',
        'status',
        'violation_reason',
        'mail_sent',
    ];

    /**
     * Relations
     */
    public function accountTypePhaseRule() {
        return $this->belongsTo(AccountTypePhaseRule::class);
    }

    public function accountTypePhase() {
        return $this->belongsTo(AccountTypePhase::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function accountTypeInvestmentStat() {
        return $this->hasOne(AccountTypeInvestmentStat::class);
    }

    public function accountTypeInvestmentHourlyStatsRecord() {
        return $this->hasMany(AccountTypeInvestmentHourlyStatsRecord::class);
    }

    public function accountTypeInvestmentSnapshot() {
        return $this->hasOne(AccountTypeInvestmentSnapshot::class);
    }

    public function accountTypeInvestmentPhaseApproval() {
        return $this->hasMany(AccountTypeInvestmentPhaseApproval::class);
    }

    public function fundedBalance() {
        return $this->hasOne(FundedBalance::class);
    }

    public function contract() {
        return $this->hasOne(Contract::class);
    }


    /**
     * Scopes
     */
    public function scopeTraderType($query) {
        return $query->where('trader_type', setting('active_trader_type', 'features'));
    }

    /**
     * The following 3 methods are used to easily get phases and rules from snapshots tables of the current ($this->) investment
     */
    public function getAccountTypeSnapshotData(){
        return $this->accountTypeInvestmentSnapshot->account_types_data ?? [];
    }

    public function getPhaseSnapshotData() {
        return collect($this->accountTypeInvestmentSnapshot->account_types_phases_data)
            ->where('id', $this->account_type_phase_id)
            ->first();
    }

    public function getRuleSnapshotData() {
        return collect($this->accountTypeInvestmentSnapshot->account_types_phases_rules_data)
            ->where('id', $this->account_type_phase_rule_id)
            ->first();
    }
   
    
}
