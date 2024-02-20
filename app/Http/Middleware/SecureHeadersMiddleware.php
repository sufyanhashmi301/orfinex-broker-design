<?php

namespace App\Http\Middleware;

use Closure;

class SecureHeadersMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Set Content-Security-Policy header
        $response->headers->set('Content-Security-Policy', "default-src 'self'; frame-src 'self' http://108.181.199.20:8080");

        return $response;
    }
}
