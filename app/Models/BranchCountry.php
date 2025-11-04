<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchCountry extends Model
{
    protected $table = 'branch_countries';

    protected $fillable = [
        'branch_id',
        'country_code',
        'country_name',
        'dial_code',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}










