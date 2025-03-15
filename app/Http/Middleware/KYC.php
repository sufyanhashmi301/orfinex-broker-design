<?php

namespace App\Http\Middleware;

use App\Enums\KYCStatus;
use Closure;
use Illuminate\Http\Request;

class KYC
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
//dd(auth()->user()->kyc,auth()->user()->email_verified_at);
        if (auth()->user()->kyc <= 0 && isset(auth()->user()->email_verified_at)) {
            auth()->user()->kyc = KYCStatus::Level1->value;
            auth()->user()->save();
    }

//        if ($kyc >= KYCStatus::Level2->value || ! setting('kyc_verification', 'permission')) {
            return $next($request);
//        }
//        tnotify('warning', 'Your account is unverified with Kyc.');

//        return redirect()->back();
    }
}
