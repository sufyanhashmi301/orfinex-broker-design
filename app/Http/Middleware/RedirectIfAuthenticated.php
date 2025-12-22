<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        
        foreach ($guards as $guard) {
            switch ($guard) {
                case 'admin':
                    // Only redirect if admin guard is authenticated
                    if (Auth::guard('admin')->check()) {
                        return redirect()->route('admin.dashboard');
                    }
                    break;

                default:
                    // For web guard, only check web guard specifically
                    // Don't redirect if only admin is authenticated
                    if (Auth::guard('web')->check() && !session('impersonating_admin_id')) {
                        return redirect(RouteServiceProvider::HOME);
                    }
                    break;
            }
        }

        return $next($request);
    }
}
