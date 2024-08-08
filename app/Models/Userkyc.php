<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userkyc extends Model
{
    use HasFactory;
    protected $table='user_kycs';
    protected $guarded =[];
}
