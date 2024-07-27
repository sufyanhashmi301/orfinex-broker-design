<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyclevel extends Model
{
    use HasFactory;
    protected $table='kyc_levels';
    public function kyclevelsettings()
    {
        return $this->hasMany(Kyclevelsetting::class,'kyc_level_id');
    }
    public function kyc()
    {
        return $this->hasMany(Kyc::class,'kyc_level_id');
    }
}
