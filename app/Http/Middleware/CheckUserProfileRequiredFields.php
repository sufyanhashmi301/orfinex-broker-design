<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserProfileRequiredFields
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (
            is_null($user->date_of_birth) ||
            is_null($user->city) ||
            is_null($user->address) ||
            strlen($user->phone) < 5
        ) {
            notify()->info('Complete your profile before buying account.');
            return redirect()->route('user.setting.profile');
        }

        return $next($request);
    }
}
