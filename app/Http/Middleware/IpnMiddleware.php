<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IpnMiddleware
{
    /**
     * Handle an incoming request for IPN (Instant Payment Notification) routes.
     * This middleware sets appropriate headers to allow external payment gateway requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Remove restrictive headers that might block external requests
        $response->headers->remove('X-Frame-Options');
        $response->headers->remove('Content-Security-Policy');
        $response->headers->remove('X-Content-Type-Options');
        $response->headers->remove('Referrer-Policy');
        $response->headers->remove('Strict-Transport-Security');
        
        // Set minimal security headers for IPN endpoints
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        
        // Allow cross-origin requests for payment gateways
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin');
        
        return $response;
    }
}
