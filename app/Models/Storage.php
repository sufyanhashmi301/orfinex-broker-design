<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;
    
    protected $casts = [
        'details' => 'array',
        'data' => 'array',
    ];

    protected $fillable = [
        'status'
    ];
}
