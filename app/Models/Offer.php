<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    // protected $casts = [
    //     'applied_to' => 'array'
    // ];

    public function discount() {
        return $this->belongsTo(Discount::class);
    }

    public function userOffers() {
        return $this->hasMany(UserOffer::class);
    }
}
