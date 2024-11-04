<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTypeInvestmentSnapshot extends Model
{
    use HasFactory;

    protected $casts = [
        'account_types_data' => 'array',
        'account_types_phases_data' => 'array',
        'account_types_phases_rules_data' => 'array',
    ];

    protected $fillable = [
        'account_type_investment_id',
        'account_types_data',
        'account_types_phases_data',
        'account_types_phases_rules_data'
    ];

    public function accountTypeInvestment() {
        return $this->belongsTo(AccountTypeInvestment::class);
    }
}
