<?php

namespace App\Services\Webhooks\Handlers;

use App\Models\User;
use App\Enums\KYCStatus;
use App\Services\Kyc\VeriffClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VeriffWebhookHandler
{
    /**
     * Handle Veriff decision webhook
     */
    public function decision(Request $request)
    {
        $raw = $request->getContent();
        $sig = $request->header('x-hmac-signature');

        // Verify webhook signature using plugin credentials
        try {
            $plugin = \App\Models\Plugin::where('name', 'Veriff (Automated KYC)')->first();
            
            if (!$plugin || $plugin->status !== 1) {
                Log::warning('Veriff webhook: plugin not found or inactive');
                return response()->json(['error' => 'service unavailable'], 503);
            }

            $credentials = json_decode($plugin->data);
            $client = new VeriffClient(
                $credentials->api_key,
                $credentials->shared_secret,
                $credentials->base_url,
                $credentials->integration_id ?? null
            );
            
            if (!$client->verifySignature($raw, $sig)) {
                Log::warning('Veriff webhook signature invalid', [
                    'signature' => $sig,
                    'body_length' => strlen($raw),
                ]);
                return response()->json(['error' => 'invalid signature'], 403);
            }
        } catch (\Exception $e) {
            Log::error('Veriff webhook signature verification failed', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'signature verification failed'], 500);
        }

        $payload = $request->json()->all();

        Log::info('Veriff decision webhook received', [
            'type' => 'decision',
            'status' => data_get($payload, 'status'),
            'verification_id' => data_get($payload, 'verification.id'),
            'verification_status' => data_get($payload, 'verification.status'),
        ]);

        // Extract data according to official Veriff webhook format
        $webhookStatus = data_get($payload, 'status'); // Top-level status (success/error)
        $sessionId = data_get($payload, 'verification.id');
        $attemptId = data_get($payload, 'verification.attemptId');
        $verificationStatus = data_get($payload, 'verification.status'); // approved/declined/etc
        $endUserId = data_get($payload, 'verification.endUserId');
        $vendorData = data_get($payload, 'verification.vendorData');
        $decisionTime = data_get($payload, 'verification.decisionTime');
        $acceptanceTime = data_get($payload, 'verification.acceptanceTime');
        $code = data_get($payload, 'verification.code');
        $reason = data_get($payload, 'verification.reason');
        $reasonCode = data_get($payload, 'verification.reasonCode');

        // Find user by multiple methods
        $user = null;

        // Try by vendor data (user ID) - handle both "user-X" format and direct ID
        if ($vendorData) {
            // If vendorData is in format "user-2", extract the number
            if (str_starts_with($vendorData, 'user-')) {
                $userId = (int) str_replace('user-', '', $vendorData);
                $user = User::find($userId);
                Log::info('Veriff webhook: trying user lookup with extracted ID', [
                    'vendor_data' => $vendorData,
                    'extracted_id' => $userId,
                    'user_found' => $user ? true : false,
                ]);
            } else {
                // Direct ID lookup
                $user = User::find($vendorData);
            }
        }

        // Try by session ID if still not found
        if (!$user && $sessionId) {
            $user = User::where('kyc_session_id', $sessionId)->first();
            Log::info('Veriff webhook: trying session ID lookup', [
                'session_id' => $sessionId,
                'user_found' => $user ? true : false,
            ]);
        }

        // Try by end user ID (UUID or external ID)
        if (!$user && $endUserId) {
            $user = User::where('uuid', $endUserId)
                       ->orWhere('external_kyc_id', $endUserId)
                       ->first();
            Log::info('Veriff webhook: trying end user ID lookup', [
                'end_user_id' => $endUserId,
                'user_found' => $user ? true : false,
            ]);
        }

        if (!$user) {
            Log::warning('Veriff webhook: user not found', [
                'sessionId' => $sessionId,
                'attemptId' => $attemptId,
                'endUserId' => $endUserId,
                'vendorData' => $vendorData,
            ]);
            return response()->json(['error' => 'user not found'], 404);
        }

        // Check webhook status first
        if ($webhookStatus !== 'success') {
            Log::warning('Veriff webhook: non-success status received', [
                'webhook_status' => $webhookStatus,
                'user_id' => $user->id,
                'session_id' => $sessionId,
            ]);
            return response()->json(['error' => 'webhook status not success'], 400);
        }

        // Save raw webhook data for auditing (including person and document data)
        $user->auto_kyc_credentials = [
            'webhook_data' => $payload,
            'decision_time' => $decisionTime,
            'acceptance_time' => $acceptanceTime,
            'attempt_id' => $attemptId,
            'code' => $code,
            'reason' => $reason,
            'reason_code' => $reasonCode,
            'person_data' => data_get($payload, 'verification.person'),
            'document_data' => data_get($payload, 'verification.document'),
            'additional_data' => data_get($payload, 'verification.additionalVerifiedData'),
            'risk_score' => data_get($payload, 'verification.riskScore'),
            'updated_at' => now(),
        ];

        // Map Veriff verification statuses to our KYC statuses
        $statusMap = [
            'approved' => KYCStatus::Level2->value,
            'declined' => KYCStatus::Rejected->value,
            'review' => KYCStatus::Pending->value,
            'resubmission_requested' => KYCStatus::Resubmit->value,
            'expired' => KYCStatus::Expired->value,
            'abandoned' => KYCStatus::Abandoned->value,
        ];

        $previousKycStatus = $user->kyc;
        $newKycStatus = $statusMap[$verificationStatus] ?? KYCStatus::Pending->value;

        // Update user KYC information
        $user->kyc_provider = 'veriff';
        $user->kyc_session_id = $sessionId;
        $user->kyc = $newKycStatus;
        $user->save();

        Log::info('Veriff KYC status updated', [
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'attempt_id' => $attemptId,
            'webhook_status' => $webhookStatus,
            'verification_status' => $verificationStatus,
            'previous_kyc_status' => $previousKycStatus,
            'new_kyc_status' => $newKycStatus,
            'decision_time' => $decisionTime,
            'code' => $code,
        ]);

        // Handle specific verification statuses with enhanced logging
        switch ($verificationStatus) {
            case 'approved':
                Log::info('Veriff KYC approved', [
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                    'attempt_id' => $attemptId,
                    'decision_code' => $code,
                    'person_name' => data_get($payload, 'verification.person.fullName'),
                    'document_type' => data_get($payload, 'verification.document.type'),
                    'document_country' => data_get($payload, 'verification.document.country'),
                    'risk_score' => data_get($payload, 'verification.riskScore.score'),
                ]);
                // Could trigger notification, email, etc.
                break;

            case 'declined':
                Log::info('Veriff KYC declined', [
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                    'attempt_id' => $attemptId,
                    'reason' => $reason,
                    'reason_code' => $reasonCode,
                    'decision_code' => $code,
                ]);
                break;

            case 'review':
                Log::info('Veriff KYC under review', [
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                    'attempt_id' => $attemptId,
                    'decision_code' => $code,
                ]);
                break;

            case 'resubmission_requested':
                Log::info('Veriff KYC resubmission requested', [
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                    'attempt_id' => $attemptId,
                    'reason' => $reason,
                    'reason_code' => $reasonCode,
                ]);
                break;

            case 'expired':
                Log::info('Veriff KYC session expired', [
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                    'attempt_id' => $attemptId,
                ]);
                break;

            case 'abandoned':
                Log::info('Veriff KYC session abandoned', [
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                    'attempt_id' => $attemptId,
                ]);
                break;
        }

        return response()->json([
            'message' => 'Webhook processed successfully',
            'user_id' => $user->id,
            'kyc_status' => $newKycStatus,
            'verification_status' => $verificationStatus,
            'session_id' => $sessionId,
            'attempt_id' => $attemptId,
            'decision_time' => $decisionTime,
        ]);
    }

    /**
     * Handle Veriff event webhook (for progress tracking)
     */
    public function event(Request $request)
    {
        $raw = $request->getContent();
        $sig = $request->header('x-hmac-signature');

        // Verify webhook signature using plugin credentials
        try {
            $plugin = \App\Models\Plugin::where('name', 'Veriff (Automated KYC)')->first();
            
            if (!$plugin || $plugin->status !== 1) {
                Log::warning('Veriff event webhook: plugin not found or inactive');
                return response()->json(['error' => 'service unavailable'], 503);
            }

            $credentials = json_decode($plugin->data);
            $client = new VeriffClient(
                $credentials->api_key,
                $credentials->shared_secret,
                $credentials->base_url,
                $credentials->integration_id ?? null
            );
            
            if (!$client->verifySignature($raw, $sig)) {
                Log::warning('Veriff event webhook signature invalid');
                return response()->json(['error' => 'invalid signature'], 403);
            }
        } catch (\Exception $e) {
            Log::error('Veriff event webhook signature verification failed', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'signature verification failed'], 500);
        }

        $payload = $request->json()->all();

        Log::info('Veriff event webhook received', [
            'type' => 'event',
            'payload' => $payload,
        ]);

        $sessionId = data_get($payload, 'verification.id');
        $eventType = data_get($payload, 'verification.eventType');

        // Find user by session ID
        $user = User::where('kyc_session_id', $sessionId)->first();

        if ($user) {
            Log::info('Veriff event processed', [
                'user_id' => $user->id,
                'session_id' => $sessionId,
                'event_type' => $eventType,
            ]);
        } else {
            Log::warning('Veriff event webhook: user not found', [
                'session_id' => $sessionId,
                'event_type' => $eventType,
            ]);
        }

        // Use for analytics, UI progress tracking, etc.
        return response()->json(['ok' => true]);
    }
}
