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
//        dd($authenticator->isAuthenticated());
//        $guard = Auth::guard();
//        dd(Auth::guard('web')->check());
//        dd($request->user());
        if (! $request->user()->two_fa) {
            return $next($request);
        }
//        $user = Auth::user(); // Retrieve the authenticated user
//dd($user->google2fa_secret, $request->input('one_time_password'),$request->all());
//        $isValid = Google2FA::verifyKey($user->google2fa_secret, $request->input('one_time_password'));

        $authenticator = app(Authenticator::class)->boot($request);
//dd($authenticator->isAuthenticated(),$request->all());
        if ($authenticator->isAuthenticated()) {
            return $next($request);
        }
//        $adminAuthenticator = app(Authenticator::class)->guard('admin')->boot($request);
//        $webAuthenticator = app(Authenticator::class)->guard('web')->boot($request);
//
//        if ($adminAuthenticator->isAuthenticated() || $webAuthenticator->isAuthenticated()) {
//            // User is authenticated with either admin or web guard
//            return $next($request);
//        }
//dd(Auth::guard('web')->check());
//dd(Auth::guard('admin')->check());
//        dd(Auth::guard('admin')->check());
//        if(Auth::guard('admin')->check()){
            return  redirect()->route('admin.staff.2fa.pin');
//        }
//        return $authenticator->makeRequestOneTimePasswordResponse();
    }
}
