<?php

namespace App\Services;

use App\Models\UserOtp;
use Carbon\Carbon;
use App\Traits\NotifyTrait;
use Illuminate\Support\Facades\Session;

class OtpService
{
    use NotifyTrait;

    public function sendOtp($user, $transactionType, $otpValidityMinutes = 10)
    {
        $otp = rand(1000, 9999);  // 4-digit OTP
        $otpExpiration = Carbon::now()->addMinutes($otpValidityMinutes);

        UserOtp::updateOrCreate(
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
            return false;
        }

        if ($userOtp->expires_at < Carbon::now()) {
            return false;
        }

        if ($userOtp->otp != $otp) {
            return false;
        }

        return true;
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
