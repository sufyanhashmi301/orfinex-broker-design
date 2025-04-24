<?php

namespace App\Services\Webhooks\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZeptoMailWebhookHandler
{
    public function handle(Request $request)
    {
        $payload = json_decode($request->getContent(), true);

        Log::info('[ZeptoMail Webhook Received]', [
            'event_names' => $payload['event_name'] ?? [],
            'request_id' => $payload['webhook_request_id'] ?? 'N/A',
            'email_summary' => collect($payload['event_message'] ?? [])
                ->map(function ($event) {
                    return [
                        'type' => $event['event_data'][0]['object'] ?? 'unknown',
                        'to' => collect($event['email_info']['to'] ?? [])->pluck('email_address.address')->toArray(),
                        'time' => $event['email_info']['processed_time'] ?? null,
                    ];
                })
        ]);

        return response()->json(['received' => true]);
    }
}