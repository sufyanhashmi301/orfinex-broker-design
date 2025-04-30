<?php

namespace App\Http\Middleware;

use Closure;

class SecureHeaders
{
    private $unwantedHeaderList = [
        'X-Powered-By',
        'Server',
    ];

    public function handle($request, Closure $next)
    {
        $this->removeUnwantedHeaders($this->unwantedHeaderList);

        $response = $next($request);

        // ✅ Standard secure headers
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // ✅ Correct CSP allowing CDN images, inline SVGs, animated icons
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https:; " .
            "style-src 'self' 'unsafe-inline' https:; " .
            "img-src 'self' data: blob: https: https://storage.brokeret.com https://cdn.brokeret.com; " .  // ✅ Main fix here
            "font-src 'self' data: https:; " .
            "connect-src 'self' https: wss:; " .
            "frame-src 'self' https://static.sumsub.com https://api.sumsub.com https://social.ggccfx.com; " .
            "frame-ancestors 'self' https://social.ggccfx.com; " .
            "object-src 'none'; " .
            "base-uri 'self';"
        );

        return $response;
    }

    private function removeUnwantedHeaders($headerList)
    {
        foreach ($headerList as $header) {
            header_remove($header);
        }
    }
}
