<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserOtp;
use App\Traits\NotifyTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserAccountCreationService
{
    use NotifyTrait;

    /**
     * Send OTP for withdraw account creation
     */
    public function sendWithdrawAccountOtp(User $user, int $otpValidityMinutes = 5): array
    {
        try {
            // Check if user is already restricted
            $existingOtp = UserOtp::where('user_id', $user->id)
                ->where('type', 'withdraw_account_creation')
                ->first();

            if ($existingOtp && $existingOtp->isRestricted()) {
                $remainingTime = $existingOtp->getRemainingRestrictionTime();
                $formattedTime = $this->formatRemainingTime($remainingTime);
                return [
                    'status' => 'error',
                    'message' => __('Account creation is temporarily restricted. Please try again in :time.', ['time' => $formattedTime])
                ];
            }

            // Check resend attempts - if 3 or more, apply restriction
            $resendAttempts = $this->getResendAttempts($user);
            if ($resendAttempts >= 3) {
                // Apply 2-hour restriction
                $this->applyRestriction($user);
                $formattedTime = $this->formatRemainingTime(7200); // 2 hours = 7200 seconds
                return [
                    'status' => 'error',
                    'message' => __('Too many resend attempts. Account creation is restricted for :time.', ['time' => $formattedTime])
                ];
            }

            // Generate new OTP
            $otp = $this->generateOtp();
            $expiresAt = Carbon::now()->addMinutes($otpValidityMinutes);

            // Delete existing OTP if any
            if ($existingOtp) {
                $existingOtp->delete();
            }

            // Create new OTP record
            $createdOtp = UserOtp::create([
                'user_id' => $user->id,
                'type' => 'withdraw_account_creation',
                'otp' => $otp,
                'expires_at' => $expiresAt,
                'verified' => 0,
                'failed_attempts' => 0,
                'restricted_until' => null,
            ]);

            // Increment resend attempts
            $this->incrementResendAttempts($user);

            // Send OTP email
            $this->sendOtpEmail($user, $otp, $otpValidityMinutes);

            return [
                'status' => 'success',
                'message' => __('OTP sent successfully to your email.')
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => __('Failed to send OTP. Please try again.')
            ];
        }
    }

    /**
     * Validate OTP for withdraw account creation
     */
    public function validateWithdrawAccountOtp(User $user, string $otpInput): array
    {
        try {
            $userOtp = UserOtp::where('user_id', $user->id)
                ->where('type', 'withdraw_account_creation')
                ->where('verified', 0)
                ->first();

            if (!$userOtp) {
                return [
                    'status' => 'error',
                    'message' => __('No pending OTP verification found.')
                ];
            }

            // Log OTP validation attempt for debugging
            $currentTime = Carbon::now();
            $secondsUntilExpiry = $userOtp->expires_at->diffInSeconds($currentTime, false);

            // Check if OTP is expired
            // Use isPast() which is more semantic and reliable for checking expiration
            if ($userOtp->expires_at->isPast()) {
                $userOtp->delete();
                return [
                    'status' => 'error',
                    'message' => __('OTP has expired. Please request a new one.')
                ];
            }

            // Check if account is restricted
            if ($userOtp->isRestricted()) {
                $remainingTime = $userOtp->getRemainingRestrictionTime();
                $formattedTime = $this->formatRemainingTime($remainingTime);
                return [
                    'status' => 'error',
                    'message' => __('Account creation is temporarily restricted. Please try again in :time.', ['time' => $formattedTime])
                ];
            }

            // Validate OTP (ensure both are strings for comparison)
            if ((string)$userOtp->otp !== (string)$otpInput) {
                $userOtp->incrementFailedAttempts();
                
                // Check if 3 failed attempts reached
                if ($userOtp->failed_attempts >= 3) {
                    $userOtp->update([
                        'restricted_until' => Carbon::now()->addHours(2)
                    ]);
                    
                    $formattedTime = $this->formatRemainingTime(7200); // 2 hours = 7200 seconds
                    return [
                        'status' => 'error',
                        'message' => __('Too many failed attempts. Account creation is restricted for :time.', ['time' => $formattedTime])
                    ];
                }

                // Return error but don't delete OTP - let user try again
                return [
                    'status' => 'error',
                    'message' => __('Invalid OTP. You have :attempts attempts remaining.', [
                        'attempts' => 3 - $userOtp->failed_attempts
                    ])
                ];
            }

            // OTP is valid - mark as verified, clear restrictions, and reset resend attempts
            $userOtp->update([
                'verified' => 1,
                'failed_attempts' => 0,
                'restricted_until' => null,
            ]);

            // Reset resend attempts after successful verification
            $this->resetResendAttempts($user);

            return [
                'status' => 'success',
                'message' => __('OTP verified successfully.')
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => __('OTP validation failed. Please try again.')
            ];
        }
    }

    /**
     * Check if user can create withdraw account
     */
    public function canCreateWithdrawAccount(User $user): array
    {
        $existingOtp = UserOtp::where('user_id', $user->id)
            ->where('type', 'withdraw_account_creation')
            ->first();

        if ($existingOtp && $existingOtp->isRestricted()) {
            $remainingTime = $existingOtp->getRemainingRestrictionTime();
            $formattedTime = $this->formatRemainingTime($remainingTime);
            return [
                'can_create' => false,
                'is_restricted' => true,
                'remaining_time' => $remainingTime,
                'formatted_time' => $formattedTime,
                'message' => __('Account creation is temporarily restricted. Please try again in :time.', ['time' => $formattedTime])
            ];
        }

        return [
            'can_create' => true,
            'is_restricted' => false,
            'remaining_time' => 0,
            'message' => null
        ];
    }

    /**
     * Get user's OTP status for withdraw account creation
     */
    public function getOtpStatus(User $user): array
    {
        $existingOtp = UserOtp::where('user_id', $user->id)
            ->where('type', 'withdraw_account_creation')
            ->first();

        if (!$existingOtp) {
            return [
                'has_otp' => false,
                'is_restricted' => false,
                'remaining_time' => 0,
                'failed_attempts' => 0,
                'resend_attempts' => $this->getResendAttempts($user),
                'can_resend' => $this->getResendAttempts($user) < 3
            ];
        }

        $isRestricted = $existingOtp->isRestricted();
        $remainingTime = $isRestricted ? $existingOtp->getRemainingRestrictionTime() : 0;
        $formattedTime = $isRestricted ? $this->formatRemainingTime($remainingTime) : '';
        $failedAttempts = $existingOtp->failed_attempts;
        $resendAttempts = $this->getResendAttempts($user);

        return [
            'has_otp' => true,
            'is_restricted' => $isRestricted,
            'remaining_time' => $remainingTime,
            'formatted_time' => $formattedTime,
            'failed_attempts' => $failedAttempts,
            'resend_attempts' => $resendAttempts,
            'can_resend' => !$isRestricted && $resendAttempts < 3
        ];
    }

    /**
     * Generate a 4-digit OTP
     */
    private function generateOtp(): string
    {
        return str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get resend attempts for user (per session, not per account creation)
     */
    private function getResendAttempts(User $user): int
    {
        $resendKey = 'withdraw_account_resend_attempts_' . $user->id;
        return session($resendKey, 0);
    }

    /**
     * Increment resend attempts for user
     */
    private function incrementResendAttempts(User $user): void
    {
        $resendKey = 'withdraw_account_resend_attempts_' . $user->id;
        $attempts = session($resendKey, 0);
        session([$resendKey => $attempts + 1]);
    }

    /**
     * Reset resend attempts for user
     */
    public function resetResendAttempts(User $user): void
    {
        $resendKey = 'withdraw_account_resend_attempts_' . $user->id;
        session([$resendKey => 0]);
    }

    /**
     * Apply restriction for user
     */
    private function applyRestriction(User $user): void
    {
        UserOtp::create([
            'user_id' => $user->id,
            'type' => 'withdraw_account_creation',
            'otp' => 'RESTRICTED',
            'expires_at' => Carbon::now()->addHours(2),
            'verified' => 0,
            'failed_attempts' => 0,
            'restricted_until' => Carbon::now()->addHours(2),
        ]);
    }

    /**
     * Format remaining time in hours and minutes
     */
    private function formatRemainingTime(int $seconds): string
    {
        if ($seconds <= 0) {
            return '0 minutes';
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}m";
        }
    }

    /**
     * Send OTP email to user
     */
    private function sendOtpEmail(User $user, string $otp, int $validityMinutes): void
    {
        $emailTemplate = 'user_withdraw_account_otp';
        
        $shortCodes = [
            '[[verification_code]]' => $otp,
            '[[otp_validity]]' => $validityMinutes,
            '[[full_name]]' => $user->full_name ?? $user->name,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => url('/'),
        ];

        $this->mailNotify($user->email, $emailTemplate, $shortCodes);
    }
} 