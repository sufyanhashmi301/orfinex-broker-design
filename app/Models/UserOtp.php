<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserOtp extends Model
{
    use HasFactory;

    protected $table = 'user_otps';

    protected $fillable = [
        'user_id',
        'otp',
        'type',
        'expires_at',
        'verified',
        'failed_attempts',
        'restricted_until'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'restricted_until' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isRestricted()
    {
        return $this->restricted_until && $this->restricted_until->isFuture();
    }

    public function getRemainingRestrictionTime()
    {
        if (!$this->restricted_until) {
            return 0;
        }
        
        $remaining = $this->restricted_until->diffInSeconds(Carbon::now());
        return max(0, $remaining);
    }

    public function incrementFailedAttempts()
    {
        $this->increment('failed_attempts');
        
        // If 3 or more failed attempts, restrict for 2 hours
        if ($this->failed_attempts >= 3) {
            $this->update([
                'restricted_until' => Carbon::now()->addHours(2)
            ]);
        }
    }

    public function clearRestrictions()
    {
        $this->update([
            'failed_attempts' => 0,
            'restricted_until' => null
        ]);
    }
}
