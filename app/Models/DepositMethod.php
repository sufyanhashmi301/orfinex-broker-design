<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositMethod extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'country' => 'array',
        'is_custom_bank_details' => 'boolean',
        'is_global' => 'boolean',
    ];
    protected $appends = [
        'gateway_logo',
    ];

    public function gateway(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Gateway::class, 'gateway_id');
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'deposit_method_branches', 'deposit_method_id', 'branch_id')->withTimestamps();
    }

    public function scopeCode($query, $code)
    {
        return $query->where('gateway_code', $code);
    }

    public function getGatewayLogoAttribute()
    {

        if ( isset($this->gateway_id)) {
            return $this->gateway->logo;
        }

        return asset($this->logo);
    }
//    public function getCountryAttribute($value)
//    {
//        return $value ? json_decode($value, true) : ['All'];
//    }

//    public function setCountryAttribute($value)
//    {
//        $this->attributes['country'] = json_encode($value);
//    }
}
