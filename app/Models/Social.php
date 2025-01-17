<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    protected $table = 'socials';

    protected $casts = [
        'status' => 'int'
    ];


    protected $fillable = [
        'title',
        'driver',
        'client_id',
        'client_secret',
        'redirect',
        'status'
    ];
    public static function activePlatforms()
    {
        return self::where('status', 1)->get();
    }

    public static function getProviderConfig($provider)
    {
        return self::where('driver', $provider)->where('status', 1)->first();
    }
}
