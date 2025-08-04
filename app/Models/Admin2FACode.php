<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Admin2FACode extends Model
{
    use HasFactory;

    protected $table = 'admin_2fa_codes';

    protected $fillable = [
        'admin_id',
        'email',
        'code',
        'expires_at',
        'used',
        'resend_attempts',
        'restricted_until'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
        'restricted_until' => 'datetime'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function isValid()
    {
        return !$this->used && $this->expires_at->isFuture();
    }

    public function markAsUsed()
    {
        $this->update(['used' => true]);
    }

    public static function generateCode($adminId, $email)
    {
        // Check if admin is restricted
        $restriction = self::where('admin_id', $adminId)
            ->where('restricted_until', '>', Carbon::now())
            ->first();

        \Log::info('Generating code', [
            'admin_id' => $adminId,
            'email' => $email,
            'is_restricted' => $restriction ? 'yes' : 'no'
        ]);

        if ($restriction) {
            \Log::info('Admin is restricted, cannot generate code', [
                'admin_id' => $adminId,
                'restricted_until' => $restriction->restricted_until
            ]);
            return null; // Admin is restricted
        }

        // Get or create tracking record for this admin
        $trackingRecord = self::where('admin_id', $adminId)
            ->where('used', false)
            ->first();

        \Log::info('Tracking record status', [
            'admin_id' => $adminId,
            'tracking_record_found' => $trackingRecord ? 'yes' : 'no',
            'current_attempts' => $trackingRecord ? $trackingRecord->resend_attempts : 'N/A'
        ]);

        if (!$trackingRecord) {
            // Create initial tracking record
            $trackingRecord = self::create([
                'admin_id' => $adminId,
                'email' => $email,
                'code' => str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),
                'expires_at' => Carbon::now()->addMinutes(1),
                'used' => false,
                'resend_attempts' => 0,
                'restricted_until' => null
            ]);
            
            \Log::info('Created new tracking record', [
                'admin_id' => $adminId,
                'code' => $trackingRecord->code,
                'attempts' => $trackingRecord->resend_attempts
            ]);
        } else {
            // Update existing record with new code
            $oldCode = $trackingRecord->code;
            $trackingRecord->update([
                'code' => str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),
                'expires_at' => Carbon::now()->addMinutes(1),
                'email' => $email
            ]);
            
            \Log::info('Updated existing tracking record', [
                'admin_id' => $adminId,
                'old_code' => $oldCode,
                'new_code' => $trackingRecord->code,
                'attempts' => $trackingRecord->resend_attempts
            ]);
        }

        return $trackingRecord;
    }

    public static function verifyCode($adminId, $code)
    {
        $codeRecord = self::where('admin_id', $adminId)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($codeRecord) {
            $codeRecord->markAsUsed();
            return true;
        }

        return false;
    }

    public static function incrementResendAttempts($adminId)
    {
        $codeRecord = self::where('admin_id', $adminId)
            ->where('used', false)
            ->first();

        \Log::info('Incrementing resend attempts', [
            'admin_id' => $adminId,
            'code_record_found' => $codeRecord ? 'yes' : 'no',
            'current_attempts' => $codeRecord ? $codeRecord->resend_attempts : 'N/A'
        ]);

        if ($codeRecord) {
            $newAttempts = $codeRecord->resend_attempts + 1;
            
            \Log::info('Updating attempts', [
                'admin_id' => $adminId,
                'old_attempts' => $codeRecord->resend_attempts,
                'new_attempts' => $newAttempts
            ]);
            
            if ($newAttempts >= 3) {
                // Restrict for 2 hours after 3 attempts
                $codeRecord->update([
                    'resend_attempts' => $newAttempts,
                    'restricted_until' => Carbon::now()->addHours(2)
                ]);
                
                \Log::info('Restriction applied', [
                    'admin_id' => $adminId,
                    'attempts' => $newAttempts,
                    'restricted_until' => Carbon::now()->addHours(2)
                ]);
            } else {
                $codeRecord->update(['resend_attempts' => $newAttempts]);
                
                \Log::info('Attempts updated', [
                    'admin_id' => $adminId,
                    'attempts' => $newAttempts
                ]);
            }
            
            return $codeRecord;
        }

        \Log::warning('No code record found for admin', ['admin_id' => $adminId]);
        return null;
    }

    public static function isRestricted($adminId)
    {
        // First, clear any expired restrictions
        self::clearExpiredRestrictions();
        
        $restriction = self::where('admin_id', $adminId)
            ->where('restricted_until', '>', Carbon::now())
            ->first();

        return $restriction ? $restriction->restricted_until : false;
    }

    public static function clearExpiredRestrictions()
    {
        // Clear restrictions that have expired
        $expiredCount = self::where('restricted_until', '<=', Carbon::now())
            ->where('restricted_until', '!=', null)
            ->update([
                'restricted_until' => null,
                'resend_attempts' => 0
            ]);
        
        if ($expiredCount > 0) {
            \Log::info('Cleared expired restrictions', ['count' => $expiredCount]);
        }
    }

    public static function getRemainingRestrictionTime($adminId)
    {
        $restriction = self::where('admin_id', $adminId)
            ->where('restricted_until', '>', Carbon::now())
            ->first();

        if ($restriction) {
            return Carbon::now()->diffInSeconds($restriction->restricted_until);
        }

        return 0;
    }

    public static function getResendAttempts($adminId)
    {
        $codeRecord = self::where('admin_id', $adminId)
            ->where('used', false)
            ->first();

        return $codeRecord ? $codeRecord->resend_attempts : 0;
    }

    public static function clearRestrictions($adminId)
    {
        // Clear all restrictions and reset attempts for this admin
        self::where('admin_id', $adminId)
            ->where('used', false)
            ->update([
                'resend_attempts' => 0,
                'restricted_until' => null
            ]);
        
        \Log::info('Cleared restrictions for admin', ['admin_id' => $adminId]);
    }
} 