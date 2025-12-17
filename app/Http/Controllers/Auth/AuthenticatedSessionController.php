<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LoginActivities;
use App\Models\Page;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogService;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
//        $page = Page::where('code', 'login')->where('locale', app()->getLocale())->first();
//        $data = json_decode($page->data, true);

        $googleReCaptcha = plugin_active('Google reCaptcha');
        $cloudflareTurnstile = plugin_active('Cloudflare Turnstile');

        $cloudflareTurnstileData = [];
        if ($cloudflareTurnstile && is_string($cloudflareTurnstile->data)) {
            $cloudflareTurnstileData = json_decode($cloudflareTurnstile->data, true) ?? [];
        }

        // Pass site_key separately for clean Blade usage
        $siteKey = $cloudflareTurnstileData['site_key'] ?? null;

        return view('frontend::auth.login', compact('googleReCaptcha', 'cloudflareTurnstile', 'cloudflareTurnstileData', 'siteKey'));
    }

    public function iframeLogin()
    {

        $googleReCaptcha = plugin_active('Google reCaptcha');

        return view('frontend::auth.iframe-login', compact('googleReCaptcha'));
    }

    /**
     * Handle an incoming authentication request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
//        dd($request->all());
        $oldTheme = session()->get('site-color-mode');
        
        // Check if admin was logged in before
        $adminWasLoggedIn = Auth::guard('admin')->check();
        
        // Clear any admin-related intended URLs to prevent redirect to admin panel
        if ($adminWasLoggedIn) {
            session()->forget('url.intended');
        }

        $request->authenticate();
        $request->session()->regenerate();

        LoginActivities::add();
        ActivityLogService::log('user_login', "Login Successfully");

        session()->put('site-color-mode', $oldTheme);
        
        // Always redirect to user dashboard when logging in as user
        // This prevents redirection to admin panel when admin is logged in
        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $oldTheme = session()->get('site-color-mode');
        
        // Check if admin is impersonating a user
        $isImpersonating = session('impersonating_admin_id');
        
        // Check if admin is logged in (even without impersonation)
        $hasAdminSession = Auth::guard('admin')->check();
        
        ActivityLogService::log('user_logout', "Logout Successfully");
        
        // Logout from web guard only
        Auth::guard('web')->logout();
        
        // Only invalidate session if there's NO admin session active
        // This prevents logging out admin when user logs out
        if (!$hasAdminSession && !$isImpersonating) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            session()->put('site-color-mode', $oldTheme);
            return redirect('/');
        }
        
        // If admin session exists (with or without impersonation)
        if ($isImpersonating) {
            // Clear impersonation data
            $impersonationKey = session('impersonation_key');
            if ($impersonationKey) {
                cache()->forget($impersonationKey);
            }
            session()->forget(['impersonating_admin_id', 'impersonating_user_id', 'impersonation_key']);
        }
        
        // Regenerate token but don't invalidate entire session (preserve admin)
        $request->session()->regenerateToken();
        session()->put('site-color-mode', $oldTheme);
        
        // Redirect to admin dashboard if admin is logged in
        if ($hasAdminSession) {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect('/');
    }
}
