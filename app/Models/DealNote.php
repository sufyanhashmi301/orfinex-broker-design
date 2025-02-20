<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealNote extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'details',
        'deal_id',
        'added_by',
    ];
}
