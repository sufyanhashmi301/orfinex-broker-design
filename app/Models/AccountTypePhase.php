<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTypePhase extends Model
{
    use HasFactory;

    protected $fillable = [
		'account_type_id',
		'phase',
		'phase_step',
		'phase_approval_method',
		'type',
        'validity_period',
		'term_type',
		'server'
	];

    public function accountType(){
        return $this->belongsTo(AccountType::class);
    }

    public function accountTypePhaseRules(){
        return $this->hasMany(AccountTypePhaseRule::class);
    }

    public function accountTypeInvestment() {
        return $this->hasMany(AccountTypeInvestment::class);
    }
}
