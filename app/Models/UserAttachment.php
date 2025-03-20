<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAttachment extends Model
{
    use HasFactory;
    protected $casts = [
        'ib_groups' => 'array',
        'account_types' => 'array',
    ];
    protected $fillable = [
        'ib_groups',
        'account_types',
    ];
}
