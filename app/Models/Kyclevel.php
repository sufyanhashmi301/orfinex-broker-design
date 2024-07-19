<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyclevel extends Model
{
    use HasFactory;

    public function kyclevelsettings()
    {
        return $this->hasMany(Kyclevelsetting::class);
    }
    public function kyc()
    {
        return $this->hasMany(Kyc::class);
    }
}
