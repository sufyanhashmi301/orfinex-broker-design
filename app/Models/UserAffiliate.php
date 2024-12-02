<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAffiliate extends Model
{
    use HasFactory;

    protected $casts = [
        'user_ids_used' => 'array',
        'commission_pending' => 'array'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
