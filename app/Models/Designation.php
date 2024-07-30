<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'parent_id', 'status'];

    public function parent()
    {
        return $this->belongsTo(Designation::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Designation::class, 'parent_id');
    }
}
