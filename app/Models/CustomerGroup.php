<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug','status'];

    public function customers()
    {
        return $this->belongsToMany(User::class, 'customer_group_has_customers');
    }
}
