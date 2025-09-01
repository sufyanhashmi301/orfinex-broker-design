<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MT5 Database Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for MT5 database connection resilience, timeouts, and 
    | circuit breaker patterns.
    |
    */

    'connection' => [
        'timeout' => env('MT5_DB_TIMEOUT', 10), // Connection timeout in seconds
        'connect_timeout' => env('MT5_DB_CONNECT_TIMEOUT', 5), // MySQL connection timeout
        'wait_timeout' => env('MT5_DB_WAIT_TIMEOUT', 30), // MySQL session wait timeout
        'interactive_timeout' => env('MT5_DB_INTERACTIVE_TIMEOUT', 30), // MySQL interactive timeout
        'read_write_timeout' => env('MT5_DB_READ_WRITE_TIMEOUT', 10), // Laravel read/write timeout
    ],

    'circuit_breaker' => [
        'failure_threshold' => env('MT5_DB_CIRCUIT_BREAKER_THRESHOLD', 3), // Failures before circuit opens
        'timeout_duration' => env('MT5_DB_CIRCUIT_BREAKER_TIMEOUT', 300), // Circuit breaker timeout in seconds
        'reset_timeout' => env('MT5_DB_CIRCUIT_BREAKER_RESET', 60), // Time before attempting reset
    ],

    'health_check' => [
        'cache_duration' => env('MT5_DB_HEALTH_CACHE_DURATION', 60), // Health check cache in seconds
        'down_cache_duration' => env('MT5_DB_DOWN_CACHE_DURATION', 300), // Down status cache in seconds
        'retry_attempts' => env('MT5_DB_RETRY_ATTEMPTS', 3), // Number of retry attempts
        'retry_delay' => env('MT5_DB_RETRY_DELAY', 1000), // Retry delay in milliseconds
    ],

    'monitoring' => [
        'enable_logging' => env('MT5_DB_ENABLE_LOGGING', true), // Enable connection logging
        'log_slow_queries' => env('MT5_DB_LOG_SLOW_QUERIES', true), // Log slow queries
        'slow_query_threshold' => env('MT5_DB_SLOW_QUERY_THRESHOLD', 5), // Slow query threshold in seconds
        'log_level' => env('MT5_DB_LOG_LEVEL', 'warning'), // Log level for issues
    ],

    'fallback' => [
        'enable_graceful_degradation' => env('MT5_DB_GRACEFUL_DEGRADATION', true), // Enable graceful degradation
        'return_cached_data' => env('MT5_DB_RETURN_CACHED_DATA', true), // Return cached data on failure
        'cache_fallback_duration' => env('MT5_DB_CACHE_FALLBACK_DURATION', 600), // Fallback cache duration
    ],

    'alerts' => [
        'enable_alerts' => env('MT5_DB_ENABLE_ALERTS', true), // Enable alerting system
        'alert_threshold' => env('MT5_DB_ALERT_THRESHOLD', 5), // Failures before alerting
        'alert_cooldown' => env('MT5_DB_ALERT_COOLDOWN', 1800), // Cooldown between alerts in seconds
        'alert_channels' => [
            'log' => env('MT5_DB_ALERT_LOG', true),
            'email' => env('MT5_DB_ALERT_EMAIL', false),
            'slack' => env('MT5_DB_ALERT_SLACK', false),
        ],
    ],
];
