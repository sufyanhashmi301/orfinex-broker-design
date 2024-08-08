<?php

return [
    'bots' => [
        'banexcapital_bot' => [
            'token' => env('TELEGRAM_BOT_TOKEN'),
            'certificate_path' => env('TELEGRAM_CERTIFICATE_PATH', null),
            'webhook_url' => env('TELEGRAM_WEBHOOK_URL', null),
        ],
    ],

    'default' => 'banexcapital_bot',

    'async_requests' => env('TELEGRAM_ASYNC_REQUESTS', false),

    'http_client_handler' => null,

    'commands' => [
        // Add your custom Telegram commands here
    ],
];
