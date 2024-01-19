<?php

namespace App\Http\Middleware;

use Closure;
use Stevebauman\Location\Facades\Location;

class DetectCountryMiddleware
{
    public function handle($request, Closure $next)
    {
        $ipAddress = $request->ip();
        $country = Location::get($ipAddress)->country;

        // Store the country in the session or wherever you prefer
        session(['user_country' => $country]);

        return $next($request);
    }
}
