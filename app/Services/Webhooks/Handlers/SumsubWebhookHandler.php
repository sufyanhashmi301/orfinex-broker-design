<?php

namespace App\Services\Webhooks\Handlers;

use App\Models\User;
use App\Enums\KYCStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SumsubWebhookHandler
{
    public function handle(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        // Log the entire payload + type
        Log::info('[Sumsub Webhook Received]', [
            'type' => $data['type'] ?? 'unknown',
            'externalUserId' => $data['externalUserId'] ?? 'missing',
            'raw' => $data,
        ]);

        if (empty($data['externalUserId'])) {
            Log::warning('Sumsub Webhook: Missing externalUserId', ['body' => $data]);
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $user = User::where('external_kyc_id', $data['externalUserId'])->first();

        if (!$user) {
            Log::warning('Sumsub Webhook: User not found', ['externalUserId' => $data['externalUserId']]);
            return response()->json(['error' => 'User not found'], 404);
        }

        // Save raw webhook data for auditing or future use
        $user->auto_kyc_credentials = $data;

        // Default to keep existing status
        $kycStatus = $user->kyc;
        $hookType = $data['type'] ?? null;

        // Handle known review success
        if (
            $hookType === 'applicantReviewed' &&
            isset($data['reviewResult']['reviewAnswer']) &&
            $data['reviewResult']['reviewAnswer'] === 'GREEN'
        ) {
            $kycStatus = KYCStatus::Level2->value;

            Log::info('Sumsub KYC Review Approved', [
                'user_id' => $user->id,
                'new_status' => $kycStatus,
            ]);
        }

        // Optional: Handle other types (e.g. applicantOnHold, applicantPending) — you can expand this as needed
        // Example:
        if ($hookType === 'applicantOnHold') {
            Log::notice('Sumsub KYC On Hold', [
                'user_id' => $user->id,
                'reason' => $data['reviewResult']['reviewRejectType'] ?? 'unknown',
            ]);
        }
        
        if (!$user) {
            Log::warning('Sumsub Webhook: User not found', [
                'externalUserId' => $data['externalUserId'],
                'hint' => 'May be race condition — webhook arrived before DB update.',
                'ts' => now()->toDateTimeString()
            ]);
            return response()->json(['error' => 'User not found'], 404);
        }
        

        // Update if anything changed
        $user->kyc = $kycStatus;
        $user->save();

        return [
            'user_id' => $user->id,
            'status' => $kycStatus,
            'hook_type' => $hookType,
        ];
    }
}