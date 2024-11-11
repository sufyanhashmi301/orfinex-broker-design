<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTypeInvestmentPhaseApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_type_investment_id',
        'account_type_phase_id',
        'phase_type',
        'status',
        'action',
    ];

    public function accountTypeInvestment() {
        return $this->belongsTo(AccountTypeInvestment::class);
    }
}
