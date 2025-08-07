<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PaymentGatewayHeaders
{
    /**
     * Handle an incoming request for payment gateway routes.
     * This middleware sets appropriate headers for iframe embedding and cross-origin requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Allow embedding in iframes from same origin
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        
        // Set CSP headers to allow specific payment gateway domains
        $cspDirectives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://uniwire.com https://*.uniwire.com https://js.stripe.com https://checkout.stripe.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://uniwire.com https://*.uniwire.com",
            "font-src 'self' https://fonts.gstatic.com",
            "img-src 'self' data: https: blob:",
            "connect-src 'self' https://api.uniwire.com https://testnet-api.uniwire.com https://*.stripe.com",
            "frame-src 'self' https://uniwire.com https://*.uniwire.com https://testnet.uniwire.com https://*.testnet.uniwire.com https://js.stripe.com https://checkout.stripe.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self' https://uniwire.com https://*.uniwire.com"
        ];
        
        $response->headers->set('Content-Security-Policy', implode('; ', $cspDirectives));
        
        // Set additional headers for better browser compatibility
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Prevent caching of payment pages
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        
        return $response;
    }
} 