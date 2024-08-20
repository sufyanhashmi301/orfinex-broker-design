<?php

namespace App\Http\Middleware;

use Closure;

class SecureHeaders1Middleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Set Content-Security-Policy header
//        $response->headers->set('Content-Security-Policy', "default-src 'self'; style-src 'self' https://fonts.googleapis.com; style-src-elem 'self' https://fonts.googleapis.com");

        return $response;
    }
}
