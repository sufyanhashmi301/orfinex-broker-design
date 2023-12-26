<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitDeduction extends Model
{
    use HasFactory;
    protected $fillable = [
        'plan_name',
        'start_date',
        'end_date',
        'percentage',
    ];

    public function getStartDateAttribute($value)
    {
//        dd(Carbon::parse($value)->format('m/d/Y'));
        return Carbon::parse($value)->format('m/d/Y');
    }
}
