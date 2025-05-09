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
        '*advance/kyc/status',
        '*telegram/webhook',
        '*outgoing-call',
        '*outbound.xml',
        '*m2p/deposit/crypto-agent'
    ];
}
