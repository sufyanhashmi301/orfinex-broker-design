<?php

namespace App\Http\Middleware;

use App\Enums\IBStatus;
use Closure;
use Illuminate\Http\Request;

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
//        dd('s');
        if (auth()->user()->ib_status == IBStatus::APPROVED || auth()->user()->ib_status == IBStatus::PENDING) {
            return redirect()->route('user.ib-program');
        }


//        if (auth()->user()->ib_status == IBStatus::APPROVED || auth()->user()->ib_status == IBStatus::PENDING) {
//            return redirect()->route('user.ib-program');
//        }

        return $next($request);
    }
}
