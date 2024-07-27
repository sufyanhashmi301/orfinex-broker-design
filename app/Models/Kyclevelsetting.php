<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyclevelsetting extends Model
{
    use HasFactory;
    protected $table='kyc_level_settings';
    public function kyclevel()
    {
        return $this->belongsTo(Kyclevel::class,'kyc_level_id');
    }
}
