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
    protected $appends = [
        'gateway_logo',
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
    
    public function getGatewayLogoAttribute()
    {
        // Check if gateway exists and has logo
        if ($this->gateway_id && $this->gateway && $this->gateway->logo) {
            return $this->gateway->logo;
        }

        // Fallback to method icon if available
        if ($this->icon) {
            return $this->icon;
        }

        // Final fallback to default image
        return 'assets/frontend/images/default-method.png';
    }

    /**
     * Get the properly formatted logo URL
     * 
     * @return string
     */
    public function getLogoUrlAttribute(): string
    {
        $logo = $this->gateway_logo;
        
        // If it's already a full URL, return as is
        if (filter_var($logo, FILTER_VALIDATE_URL)) {
            return $logo;
        }
        
        // Otherwise, use asset() helper for local paths
        return asset($logo);
    }
    
}

