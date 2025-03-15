<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformLink extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'link', 'platform', 'os', 'status'];
}
