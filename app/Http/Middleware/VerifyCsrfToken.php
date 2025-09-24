<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
      
        '*gateway/coingate/callback',
        '*ipn*',
        'ipn/uniwire',
        'ipn/match2pay',
        'webhook/*',//for sumsub webhook receive,veriff webhook receive
        'webhook/sumsub',
        '*telegram/webhook',
    ];
}
