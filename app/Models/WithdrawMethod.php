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
    ];

    public function gateway(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Gateway::class, 'gateway_id');
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
