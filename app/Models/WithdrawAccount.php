<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawAccount extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function method(): BelongsTo
    {
        return $this->belongsTo(WithdrawMethod::class, 'withdraw_method_id')->withDefault();
    }

    /**
     * Get the logo for this withdraw account
     * 
     * @return string
     */
    public function getLogoAttribute(): string
    {
        return $this->method ? $this->method->gateway_logo : 'assets/frontend/images/default-method.png';
    }

    /**
     * Get the properly formatted logo URL for this withdraw account
     * 
     * @return string
     */
    public function getLogoUrlAttribute(): string
    {
        return $this->method ? $this->method->logo_url : asset('assets/frontend/images/default-method.png');
    }
}
