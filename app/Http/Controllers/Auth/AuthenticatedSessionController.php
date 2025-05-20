<?php

namespace App\Http\Controllers\Auth;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Models\LoginActivities;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;

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

        return view('frontend::auth.login', compact('googleReCaptcha'));
    }

    /**
     * Handle an incoming authentication request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
    //    dd($request->all());
       $request->validate([
            
            'cf-turnstile-response' => [
                Rule::requiredIf(function () {
                    return !empty(config('services.turnstile.secret'));
                }),
            ],

        ]);
        $oldTheme = session()->get('site-color-mode');

        $request->authenticate();
        $request->session()->regenerate();

        LoginActivities::add();
        session()->put('site-color-mode', $oldTheme);

        return redirect()->intended(RouteServiceProvider::HOME);
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
