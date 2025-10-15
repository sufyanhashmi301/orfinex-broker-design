<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class VerificationFlowService
{
    /**
     * Determine which verification flow to use
     *
     * @param User $user
     * @param string $context - 'withdraw' or 'account_creation'
     * @return array
     */
    public function determineFlow(User $user, string $context = 'withdraw'): array
    {
        $otpEnabled = $this->isOtpEnabled($context);
        $twoFaEnabled = $this->isTwoFaEnabled($user);

        // Scenario 1: Both disabled → No verification
        if (!$otpEnabled && !$twoFaEnabled) {
            return [
                'type' => 'none',
                'message' => __('No verification required.'),
                'proceed' => true,
                'send_otp' => false,
                'show_modal' => null
            ];
        }

        // Scenario 2: Only OTP enabled → Send OTP and show modal
        if ($otpEnabled && !$twoFaEnabled) {
            return [
                'type' => 'otp',
                'message' => __('OTP verification required.'),
                'send_otp' => true,
                'show_modal' => 'otp',
                'proceed' => false
            ];
        }

        // Scenario 3: Only 2FA enabled → Show 2FA modal
        if (!$otpEnabled && $twoFaEnabled) {
            return [
                'type' => '2fa',
                'message' => __('Two-factor authentication required.'),
                'send_otp' => false,
                'show_modal' => '2fa',
                'proceed' => false
            ];
        }

        // Scenario 4: Both enabled → Show choice modal
        return [
            'type' => 'choice',
            'message' => __('Choose your verification method.'),
            'send_otp' => false, // Only send after user chooses
            'show_modal' => 'choice',
            'proceed' => false,
            'options' => [
                'otp' => __('Email OTP'),
                '2fa' => __('Google Authenticator')
            ]
        ];
    }

    /**
     * Check if OTP is enabled for context
     *
     * @param string $context
     * @return bool
     */
    private function isOtpEnabled(string $context): bool
    {
        $settingKey = match($context) {
            'withdraw' => 'withdraw_otp',
            'account_creation' => 'withdraw_account_otp',
            default => null
        };

        if (!$settingKey) {
            return false;
        }

        return Cache::remember("verification.{$settingKey}", 300, function() use ($settingKey) {
            return (bool) setting($settingKey, 'withdraw_settings');
        });
    }

    /**
     * Check if 2FA is enabled for user
     *
     * @param User $user
     * @return bool
     */
    private function isTwoFaEnabled(User $user): bool
    {
        $globalEnabled = Cache::remember('verification.2fa_enabled', 300, function() {
            return (bool) setting('fa_verification', 'permission');
        });

        return $globalEnabled && (bool) $user->two_fa && !empty($user->google2fa_secret);
    }

    /**
     * Handle verification choice
     *
     * @param User $user
     * @param string $choice
     * @param string $context
     * @return array
     */
    public function handleChoice(User $user, string $choice, string $context): array
    {
        if ($choice === 'otp') {
            return [
                'type' => 'otp',
                'send_otp' => true,
                'show_modal' => 'otp',
                'message' => __('Sending OTP to your email...')
            ];
        }

        if ($choice === '2fa') {
            return [
                'type' => '2fa',
                'send_otp' => false,
                'show_modal' => '2fa',
                'message' => __('Please enter your authenticator code.')
            ];
        }

        return [
            'type' => 'error',
            'message' => __('Invalid verification choice.')
        ];
    }

    /**
     * Clear verification settings cache
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget('verification.withdraw_otp');
        Cache::forget('verification.withdraw_account_otp');
        Cache::forget('verification.2fa_enabled');
    }
}

