<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeColor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'base_color', 'shades'];
    protected $casts = ['shades' => 'array'];

}
