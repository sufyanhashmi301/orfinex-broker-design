<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Exception;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            // return redirect()->intended(RouteServiceProvider::HOME);
            return redirect()->intended(RouteServiceProvider::getThemeSpecificHome());
        }
        
        // Try to send email verification notification with error handling
        try {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('status', 'verification-link-sent');
        } catch (Exception $e) {
            // Log the error for debugging
            logger()->error('Email verification resend failed: ' . $e->getMessage());
            
            // Return back with error message
            return back()->with('error', 'Failed to send verification email. Please check your email settings or try again later.');
        }
    }
}
