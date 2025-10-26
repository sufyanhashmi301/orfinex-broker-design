<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawMethod extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'country' => 'array',
        'is_global' => 'boolean',
    ];

    public function gateway(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Gateway::class, 'gateway_id');
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'withdraw_method_branches', 'withdraw_method_id', 'branch_id')->withTimestamps();
    }
    public function getCountryAttribute($value)
    {
        return $value ? json_decode($value, true) : ['All'];
    }

    public function setCountryAttribute($value)
    {
        $this->attributes['country'] = json_encode($value);
    }
}
