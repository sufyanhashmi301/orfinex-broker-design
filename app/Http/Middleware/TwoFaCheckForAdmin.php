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
        // Check if email-based 2FA is enabled globally
        $admin2faEnabled = setting('admin_2fa_enabled', 'global');
        
        // Fallback: check directly from database if helper function returns empty
        if (empty($admin2faEnabled)) {
            $setting = \App\Models\Setting::where('name', 'admin_2fa_enabled')->first();
            $admin2faEnabled = $setting ? $setting->val : false;
        }
        
        if ($admin2faEnabled) {
            // If email-based 2FA is enabled, completely skip Google 2FA check
            return $next($request);
        }

        // Original Google 2FA logic - only run if email-based 2FA is disabled
        if (! $request->user()->two_fa) {
            return $next($request);
        }

        $authenticator = app(Authenticator::class)->boot($request);
        
        if ($authenticator->isAuthenticated()) {
            return $next($request);
        }

        return redirect()->route('admin.staff.2fa.pin');
    }
}
