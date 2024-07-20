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
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
//        $this->middleware('google2fa')->only('admin.login-view', 'enable2fa', 'disable2fa', 'verify2fa');

    }

    /**
     * @return Application|Factory|View
     */
    public function loginView()
    {
        $googleReCaptcha = plugin_active('Google reCaptcha');
        return view('backend.auth.login',compact('googleReCaptcha'));
    }

    /**
     * Handle an authentication attempt.
     *
     * @return RedirectResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],

            'g-recaptcha-response' => Rule::requiredIf(plugin_active('Google reCaptcha')), new Recaptcha(),

        ]);

        $credentials = Arr::except($credentials, ['g-recaptcha-response']);
//dd($request->all());

        if ($this->guard()->attempt($credentials)) {
            $request->session()->regenerate();
            AdminLoginActivity::add();
//            smilify('success', 'Successfully login your account 🔥 !');
            return redirect()->route('admin.dashboard');
//            return redirect()->intended('admin');
        }

        notify()->warning('The provided credentials do not match our records.. ⚡️');

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
