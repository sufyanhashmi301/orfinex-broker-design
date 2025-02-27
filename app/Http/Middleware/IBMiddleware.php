<?php

namespace App\Http\Middleware;

use App\Enums\IBStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IBMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $user = Auth::user();

        // Redirect non-approved users without ref_id to IB request
        if ($user->ib_status != IBStatus::APPROVED && !isset($user->ref_id)) {
            return redirect()->route('user.ib.request');
        }

        return $next($request);
    }
}
