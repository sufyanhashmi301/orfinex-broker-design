<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchForm extends Model
{
    protected $table = 'branch_forms';

    protected $fillable = [
        'branch_id',
        'fields',
        'status',
    ];

    protected $casts = [
        'fields' => 'array',
        'status' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function countries()
    {
        return $this->hasMany(BranchCountry::class, 'branch_id', 'branch_id');
    }
}










