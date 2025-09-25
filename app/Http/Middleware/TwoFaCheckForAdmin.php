<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FALaravel\Google2FA;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class TwoFaCheckForAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Allowlist specific routes related to 2FA email flow
        $isVerifyRoute = $request->routeIs('admin.2fa.verifylogin') || $request->routeIs('admin.2fa.resend');
        $isSwitchToGaRoute = $request->routeIs('admin.2fa.switchToGa');
        // Check if email-based 2FA is enabled globally
        $admin2faEnabled = setting('admin_2fa_enabled', 'global');
        
        // Fallback: check directly from database if helper function returns empty
        if (empty($admin2faEnabled)) {
            $setting = \App\Models\Setting::where('name', 'admin_2fa_enabled')->first();
            $admin2faEnabled = $setting ? $setting->val : false;
        }
        
        // Ensure admin is authenticated for all backoffice routes
        if (!\Auth::guard('admin')->check()) {
            return redirect()->route('admin.login-view');
        }

        $user = \Auth::guard('admin')->user();

        // If switching to email OTP from GA, restrict everything except verify routes
        if (session('force_email_2fa') === true) {
            if (!$isVerifyRoute && !$isSwitchToGaRoute) {
                return redirect()->route('admin.2fa.verifylogin');
            }
            return $next($request);
        }
        
        // If admin has Google Authenticator enabled
        if ($user && $user->two_fa) {
            // Allow switching to email OTP explicitly
            // Prioritize Google Authenticator when enabled
            $authenticator = app(Authenticator::class)->boot($request);

            if ($authenticator->isAuthenticated()) {
                return $next($request);
            }
            // Wrong or missing OTP: show a warning and return to PIN page
            if ($request->isMethod('post') || $request->has(config('google2fa.otp_input'))) {
                notify()->warning(__('2Fa Authentication Wrong One Time Key'));
            }
            return redirect()->route('admin.staff.2fa.pin');
        }

        // If Google Authenticator not enabled, skip (email OTP handled in AuthController)
        return $next($request);
    }
}
