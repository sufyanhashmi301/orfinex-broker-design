<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Webhooks\WebhookRouter;

class WebhookController extends Controller
{
    public function handle(Request $request, $provider, $action = null)
    {
        // Log raw request
        Log::info("[Webhook] $provider/$action", [
            'headers' => $request->headers->all(),
            'body' => $request->getContent(),
        ]);

        try {
            $result = WebhookRouter::dispatch($provider, $action, $request);

            return response()->json(['success' => true, 'result' => $result]);
        } catch (\Throwable $e) {
            Log::error("[Webhook] $provider/$action failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }
}