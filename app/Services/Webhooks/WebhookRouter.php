<?php

namespace App\Services\Webhooks;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebhookRouter
{
    public static function dispatch(string $provider, ?string $action, Request $request)
    {
        // If action is given, attempt to resolve combined handler (e.g. SumsubKycWebhookHandler)
        if ($action) {
            $specificClass = '\\App\\Services\\Webhooks\\Handlers\\' . Str::studly($provider) . Str::studly($action) . 'WebhookHandler';
            if (class_exists($specificClass)) {
                return app($specificClass)->handle($request);
            }
        }

        // Fallback to general provider handler (e.g. SumsubWebhookHandler, ZeptomailWebhookHandler)
        $baseClass = '\\App\\Services\\Webhooks\\Handlers\\' . Str::studly($provider) . 'WebhookHandler';
        if (class_exists($baseClass)) {
            return app($baseClass)->handle($request);
        }

        throw new \Exception("Webhook handler not found for provider [$provider]" . ($action ? " and action [$action]" : ''));
    }
}