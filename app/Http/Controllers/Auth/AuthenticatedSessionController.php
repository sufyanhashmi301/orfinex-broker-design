<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LoginActivities;
use App\Models\Page;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $request->authenticate();
        $request->session()->regenerate();

        LoginActivities::add();
        session()->put('site-color-mode', $oldTheme);

        return redirect()->intended(RouteServiceProvider::getThemeSpecificHome());
    }

    /**
     * Destroy an authenticated session.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $oldTheme = session()->get('site-color-mode');
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        session()->put('site-color-mode', $oldTheme);

        return redirect('/');
    }
}
