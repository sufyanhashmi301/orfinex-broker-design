<?php

namespace App\Http\Middleware;

use Closure;

class SecureHeaders1Middleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // ✅ Updated and aligned Content-Security-Policy
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https:; " .
            "style-src 'self' 'unsafe-inline' https:; " .
            "img-src 'self' data: blob: https: https://storage.brokeret.com https://cdn.brokeret.com; " . // ✅ Updated here
            "font-src 'self' data: https:; " .
            "connect-src 'self' https: wss:; " .
            "frame-src 'self' https://static.sumsub.com https://api.sumsub.com https://social.ggccfx.com; " .
            "frame-ancestors 'self' https://social.ggccfx.com; " .
            "object-src 'none'; " .
            "base-uri 'self';"
        );

        return $response;
    }
}
