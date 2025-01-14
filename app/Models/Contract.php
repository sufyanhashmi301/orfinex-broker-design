<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'contract_id', 'file_name',
    ];

    public function accountTypeInvestment() {
        return $this->belongsTo(AccountTypeInvestment::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
