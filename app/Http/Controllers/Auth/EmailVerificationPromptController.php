<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @return mixed
     */
    public function __invoke(Request $request)
    {

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


        $request->user()->sendEmailVerificationNotification();

        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(RouteServiceProvider::HOME)
            : view('frontend::auth.verify-email');
    }
}
