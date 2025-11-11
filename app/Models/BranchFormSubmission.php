<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchFormSubmission extends Model
{
    protected $table = 'branch_form_submissions';

    protected $fillable = [
        'user_id',
        'branch_id',
        'fields',
        'status',
    ];

    protected $casts = [
        'fields' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}




