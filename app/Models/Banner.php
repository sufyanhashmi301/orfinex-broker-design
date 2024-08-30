<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';
    protected $fillable = [
        'title',
        'subtitle',
        'primary_link',
        'button_text',
        'button_link',
        'status',
    ];

    // The attributes that should be cast to native types.
    protected $casts = [
        'status' => 'boolean',
    ];

    // Optional: Set timestamps if needed (defaults to true if `timestamps` field exists)
    public $timestamps = true;
}
