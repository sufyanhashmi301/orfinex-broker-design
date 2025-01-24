<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTrial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_type_investment_id',
        'trial_expiry_at',
        'trial_used',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function accountTypeInvestment(){
        return $this->belongsTo(AccountTypeInvestment::class);
    }

}
