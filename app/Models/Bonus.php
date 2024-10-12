<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use HasFactory;

    protected $fillable = [
        "bonus_name",
        "start_date",
        "last_date",
        "type",
        "amount",
        "process",
        "applicable_by",
        "kyc_verified",
        "first_deposit",
        "status",
        "terms_link",
        "description",
    ];
}
