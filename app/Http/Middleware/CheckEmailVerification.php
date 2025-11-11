<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class CheckEmailVerification
{
    /**
     * Handle an incoming request.
     *
     * This middleware wraps Laravel's email verification logic and only applies it
     * when email verification is enabled in settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if email verification is enabled in settings
        if (! setting('email_verification', 'permission')) {
            // Email verification is disabled, allow request through
            return $next($request);
        }

        // Email verification is enabled, check if user needs to verify
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
            ! $request->user()->hasVerifiedEmail())) {
            
            // Don't redirect if already on verification routes to prevent loops
            if ($request->routeIs('verification.notice') || 
                $request->routeIs('verification.verify') || 
                $request->routeIs('verification.send') ||
                $request->routeIs('verification.verify.code')) {
                return $next($request);
            }

            return $request->expectsJson()
                    ? abort(403, 'Your email address is not verified.')
                    : Redirect::guest(URL::route('verification.notice'));
        }

        return $next($request);
    }
}

