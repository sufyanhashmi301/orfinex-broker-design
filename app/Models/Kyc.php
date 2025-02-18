<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'method',
        'data',
        'status',
        'verified_at',
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
