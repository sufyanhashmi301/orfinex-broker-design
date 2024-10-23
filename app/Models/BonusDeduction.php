<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusDeduction extends Model
{
    use HasFactory;

    public function bonus_transaction(){
        return $this->belongsTo(BonusTransaction::class);
    }
}
