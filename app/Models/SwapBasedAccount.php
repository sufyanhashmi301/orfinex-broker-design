<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwapBasedAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_type_id', 'title', 'level_order', 'group_tag', 'description', 'status'
    ];
}
