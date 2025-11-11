<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Exception;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        // If email verification is disabled, redirect to dashboard
        if (! setting('email_verification', 'permission')) {
            return redirect()->route('user.dashboard');
        }
        
        $user = $request->user();

        if (!isset($user->verification_code) || Carbon::now()->greaterThan($user->verification_code_expires_at ?? Carbon::now()->subMinutes(5))) {
            $verificationCode = rand(1000, 9999);
            $expiresAt = Carbon::now()->addMinutes(30);
            $user->verification_code = $verificationCode;
            $user->verification_code_expires_at = $expiresAt;
            $user->save();
        }

        // Check if user is already verified first
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // Try to send email verification notification with error handling
        $emailError = null;
        
        // Check if we've already tried to send email in this session to avoid repeated failures
        if (!session()->has('email_send_attempted')) {
            try {
                $request->user()->sendEmailVerificationNotification();
                session()->put('email_send_attempted', true);
            } catch (Exception $e) {
                // Log the error for debugging
                logger()->error('Email verification failed: ' . $e->getMessage());
                
                // Set error message to display on the view
                $emailError = 'Failed to send verification email. Please check your email settings or try again later.';
                session()->put('email_send_attempted', true);
            }
        }

        return view('frontend::auth.verify-email', compact('emailError'));
    }
}
