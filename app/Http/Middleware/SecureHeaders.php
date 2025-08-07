<?php

namespace App\Http\Middleware;

use Closure;

class SecureHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    private $unwantedHeaderList = [
        'X-Powered-By',
        'Server',
    ];

    public function handle($request, Closure $next)
    {

        $this->removeUnwantedHeaders($this->unwantedHeaderList);
        $response = $next($request);
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Routes that should allow iframe embedding
        $allowIframeRoutes = [
            'user/deposit/crypto-chill',
            'gateway/uniwire',
            'gateway/coinpayments',
            'gateway/perfectmoney',
            'gateway/paypal',
            'gateway/stripe',
            'user/deposit/uniwire/proxy', // Allow proxy route to be embedded
        ];

        $allowIframe = false;
        foreach ($allowIframeRoutes as $route) {
            if (strpos($request->getPathInfo(), $route) !== false) {
                $allowIframe = true;
                break;
            }
        }
        
        // Check if this is a view that contains iframe content (like gateway views)
        if ($request->route() && $request->route()->getName()) {
            $routeName = $request->route()->getName();
            if (str_contains($routeName, 'gateway') || 
                str_contains($routeName, 'crypto-chill') || 
                str_contains($routeName, 'copy-trading') ||
                str_contains($routeName, 'terminal')) {
                $allowIframe = true;
            }
        }
        
        if (!$allowIframe) {
            $response->headers->set('X-Frame-Options', 'DENY');
        } else {
            // For payment gateways, allow framing from the same origin and trusted payment providers
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            // Add CSP header to allow specific iframe sources for payment gateways
            $response->headers->set('Content-Security-Policy', 
                "frame-src 'self' https://uniwire.com https://*.uniwire.com https://testnet.uniwire.com https://*.testnet.uniwire.com; " .
                "default-src 'self'; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval'; " .
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
                "font-src 'self' https://fonts.gstatic.com; " .
                "img-src 'self' data: https:; " .
                "connect-src 'self' https:"
            );
        }
        
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        return $response;
    }

    private function removeUnwantedHeaders($headerList)
    {
        foreach ($headerList as $header) {
            header_remove($header);
        }
    }
}
