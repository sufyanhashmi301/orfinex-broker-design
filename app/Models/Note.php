<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'admin_id', // Store the admin's ID here
    ];

    // Define the relationship with the Admin model
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id'); // Associate admin_id with the Admin model
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
