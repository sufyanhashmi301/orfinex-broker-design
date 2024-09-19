<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformGroup extends Model
{
    use HasFactory;
    protected $table = 'groups';

    protected $casts = [
        'group_id' => 'int',
        'status' => 'bool'
    ];

    protected $fillable = [
        'group_id',
        'group',
        'currency',
        'currencyDigits',
        'status'
    ];
}
