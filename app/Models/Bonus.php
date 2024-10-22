<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use HasFactory;

    protected $fillable = [
        "kyc_slug",
        "bonus_name",
        "start_date",
        "last_date",
        "type",
        "bonus_removal_type",
        "bonus_removal_amount",
        "amount",
        "process",
        "applicable_by",
        "is_kyc_verified",
        "first_or_every_deposit",
        "status",
        "terms_link",
        "description",
    ];

    public function forex_schemas(){
        return $this->belongsToMany(ForexSchema::class);
    }

    public function users(){
        return $this->belongsToMany(User::class)->withPivot('transaction_id', 'account_target_id', 'account_target_type', 'added_by', 'type', 'amount');
    }
}
