<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party modules such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'paytm-wallet' => [
        'env' => env('PAYTM_ENVIRONMENT'), // values : (local | production)
        'merchant_id' => env('PAYTM_MERCHANT_ID'),
        'merchant_key' => env('PAYTM_MERCHANT_KEY'),
        'merchant_website' => env('PAYTM_MERCHANT_WEBSITE'),
        'channel' => env('PAYTM_CHANNEL',),
        'industry_type' => env('PAYTM_INDUSTRY_TYPE'),
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', 'your_google_client_id'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', 'your_google_client_secret'),
        'redirect' => env('GOOGLE_REDIRECT', 'https://yourapp.com/auth/google/callback'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID', 'your_facebook_client_id'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET', 'your_facebook_client_secret'),
        'redirect' => env('FACEBOOK_REDIRECT', 'https://yourapp.com/auth/facebook/callback'),
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID', 'your_twitter_client_id'),
        'client_secret' => env('TWITTER_CLIENT_SECRET', 'your_twitter_client_secret'),
        'redirect' => env('TWITTER_REDIRECT', 'https://yourapp.com/auth/twitter/callback'),
    ],

    'instagram' => [
        'client_id' => env('INSTAGRAM_CLIENT_ID', 'your_instagram_client_id'),
        'client_secret' => env('INSTAGRAM_CLIENT_SECRET', 'your_instagram_client_secret'),
        'redirect' => env('INSTAGRAM_REDIRECT', 'https://yourapp.com/auth/instagram/callback'),
    ],

    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID', 'your_linkedin_client_id'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET', 'your_linkedin_client_secret'),
        'redirect' => env('LINKEDIN_REDIRECT', 'https://yourapp.com/auth/linkedin/callback'),
    ],

    'discord' => [
        'client_id' => env('DISCORD_CLIENT_ID', 'your_discord_client_id'),
        'client_secret' => env('DISCORD_CLIENT_SECRET', 'your_discord_client_secret'),
        'redirect' => env('DISCORD_REDIRECT', 'https://yourapp.com/auth/discord/callback'),
    ],


];
