<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'bonus_amount_left'
    ];

    public function transaction(){
        return $this->belongsTo(Transaction::class);        
    }

    public function bonus_deductions(){
        return $this->hasMany(BonusDeduction::class);
    }
}
