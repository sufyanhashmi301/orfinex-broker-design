<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Verification Feature Flags
    |--------------------------------------------------------------------------
    |
    | These flags control the gradual rollout of new verification features.
    | Set to false in production initially, then gradually enable.
    |
    */

    // Phase 1 - New verification flow
    'use_new_flow' => env('VERIFICATION_NEW_FLOW', false),

    // Phase 2 - Rate limiting
    'rate_limiting_enabled' => env('VERIFICATION_RATE_LIMIT', false),

    // Phase 3 - Enhanced OTP (keep 4 digits initially for compatibility)
    'otp_length' => env('VERIFICATION_OTP_LENGTH', 4),

    // Progressive rollout percentage (0-100)
    'rollout_percentage' => env('VERIFICATION_ROLLOUT_PERCENT', 0),

    // Emergency kill switch
    'emergency_disable' => env('VERIFICATION_EMERGENCY_DISABLE', false),

    /*
    |--------------------------------------------------------------------------
    | OTP Settings
    |--------------------------------------------------------------------------
    */

    'otp' => [
        'length' => env('VERIFICATION_OTP_LENGTH', 4),
        'expiry_minutes' => 10,
        'max_attempts' => 3,
        'restriction_hours' => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Settings
    |--------------------------------------------------------------------------
    */

    'rate_limiting' => [
        'verify_attempts' => 5,  // per minute
        'resend_attempts' => 3,  // per minute
        'max_per_minute' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Settings
    |--------------------------------------------------------------------------
    */

    'session' => [
        'timeout_minutes' => 15,
        'encrypt_data' => true,
        'version' => '2.0',
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring Settings
    |--------------------------------------------------------------------------
    */

    'monitoring' => [
        'enabled' => env('VERIFICATION_MONITORING', true),
        'alert_threshold' => 5,  // percentage difference
        'auto_disable_on_error' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching Settings
    |--------------------------------------------------------------------------
    */

    'cache' => [
        'ttl' => 300,  // 5 minutes
        'driver' => 'redis',  // Use Redis for better performance
    ],

];

