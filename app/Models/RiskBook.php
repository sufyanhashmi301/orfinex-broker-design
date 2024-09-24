<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskBook extends Model
{
    use HasFactory;

    public function groups()
    {
        return $this->hasMany(PlatformGroup::class, 'risk_book_id');
    }
}
