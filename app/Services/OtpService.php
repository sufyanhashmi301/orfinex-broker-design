<?php

namespace App\Services;

use App\Models\UserOtp;
use Carbon\Carbon;
use App\Traits\NotifyTrait;
use Illuminate\Support\Facades\Session;

class OtpService
{
    use NotifyTrait;

    public function sendOtp($user, $transactionType, $otpValidityMinutes)
    {
        $otp = rand(1000, 9999);  // 4-digit OTP
        $otpExpiration = Carbon::now()->addMinutes($otpValidityMinutes);

        $otpQuery = UserOtp::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => $transactionType,
            ],
            [
                'otp' => $otp,
                'expires_at' => $otpExpiration,
                'verified' => 0,
            ]
        );

        $otp = $otpQuery->otp;

        Session::flash('otp', $otp);
        Session::flash('otp_expiration', $otpExpiration);

        $this->sendOtpEmail($user, $otp, $otpValidityMinutes, $transactionType);
    }

    private function sendOtpEmail($user, $otp, $otpValidityMinutes, $transactionType)
    {
        $shortcodes = [
            '[[code]]' => $otp,
            '[[otp_validity]]' => $otpValidityMinutes,
            '[[transaction_type]]' => ucfirst($transactionType),
            '[[full_name]]' => $user->full_name,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        $this->mailNotify($user->email, 'transaction_otp', $shortcodes);
    }

    public function validateOtp($user, $transactionType, $otp)
    {
        $userOtp = UserOtp::where('user_id', $user->id)
            ->where('type', $transactionType)
            ->first();

        if (!$userOtp) {
            return [
                'status' => 'error',
                'message' => __('OTP not found. Please request a new one.')
            ];
        }

        // Use isPast() for consistent and reliable expiration checking
        if ($userOtp->expires_at->isPast()) {
            return [
                'status' => 'error',
                'message' => __('Your OTP has expired. Please request a new one.')
            ];
        }

        // Validate OTP (ensure both are strings for comparison)
        if ((string)$userOtp->otp !== (string)$otp) {
            return [
                'status' => 'error',
                'message' => __('The OTP you entered is incorrect. Please try again.')
            ];
        }

        return [
            'status' => 'success',
            'message' => __('OTP is valid.')
        ];
    }

    public function isOtpExpired()
    {
        $otpExpiration = Session::get('otp_expiration');

        if (!$otpExpiration) {
            return true;
        }

        return Carbon::now()->gt($otpExpiration);
    }

    public function invalidateOtp($user, $transactionType)
    {
        // Delete OTP record from the database
        UserOtp::where('user_id', $user->id)
            ->where('type', $transactionType)
            ->delete();

        // Optionally, clear OTP session data
        Session::forget('otp');
        Session::forget('otp_expiration');
    }

}
