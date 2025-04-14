<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Enums\KYCStatus;
use sumsub\SumsubClient;

class SumsubController extends Controller
{
    /**
     * Starts the KYC process by generating Sumsub applicant and token
     */
    public function advanceKyc()
    {
        $sumsub = Plugin::find(8);
        $sumsubstatus = $sumsub->status;
        $sumsubscredentials = json_decode($sumsub->data);

        $user = \Auth::user();
        $currentTime = Carbon::now();
        $lastUpdatedTime = $user->kyc_created_at;

        if (
            empty($lastUpdatedTime) ||
            (
                $currentTime->diffInMinutes($lastUpdatedTime) > 25 &&
                $user->kyc < KYCStatus::Level2->value &&
            $sumsubstatus === 1
        )
    ) {
        $levelName = $sumsubscredentials->level_name;

        try {
            $client = new SumsubClient(
                $sumsubscredentials->app_token,
                $sumsubscredentials->app_secret_id
            );

            // 🔥 Step 1: Delete previous applicant if one exists
            if ($user->external_kyc_id) {
                try {
                    $client->deleteApplicant($user->external_kyc_id);
                } catch (\Throwable $e) {
                    Log::warning('No existing Sumsub applicant to delete or failed to delete', ['id' => $user->external_kyc_id, 'error' => $e->getMessage()]);
                }
            }

            // 🔁 Step 2: Generate a fresh unique externalUserId
            $newExternalId = 'unil-' . sha1($user->id . '-' . $user->email . '-' . now()->timestamp);
            $user->external_kyc_id = $newExternalId;
            $user->save();

            // ✅ Step 3: Create new applicant and token
            $client->createApplicant($newExternalId, $levelName);
            $accessTokenInfo = $client->getAccessToken($newExternalId, $levelName);

            $user->update([
                'kyc_token' => $accessTokenInfo['token'],
                'kyc_created_at' => Carbon::now(),
            ]);
        } catch (\Throwable $th) {
            Log::error('Sumsub KYC full reset failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', 'Failed to reset KYC.');
        }
    }

    return view('frontend::user.kyc.advance.index', compact('sumsubstatus', 'currentTime', 'lastUpdatedTime'));
}

    /**
     * Handles webhook updates from Sumsub (e.g. KYC status updates)
     */
    public function UpdateKycStatus(Request $request)
    {
        Log::info('Incoming Sumsub Webhook', [
            'headers' => $request->headers->all(),
            'body_raw' => $request->getContent(),
        ]);

        try {
            // Optional: Verify webhook signature
            $verifySignature = false;
            $secretKey = 'C5aFwQOsdillypAAJjBge_oMNNl-'; // Your Sumsub webhook secret

            if ($verifySignature) {
                $signature = $request->header('x-payload-digest');
                $computed = hash_hmac('sha256', $request->getContent(), $secretKey);
                if (strtolower($signature) !== strtolower($computed)) {
                    Log::warning('Webhook signature mismatch');
                    return response()->json(['status' => 403, 'error' => 'Signature verification failed.'], 403);
                }
            }

            $data = json_decode($request->getContent(), true);

            if (!$data || !isset($data['externalUserId'])) {
                Log::warning('Invalid KYC webhook data', ['data' => $data]);
                return response()->json(['status' => 400, 'error' => 'Missing externalUserId']);
            }

            $externalUserId = $data['externalUserId'];
            $user = User::where('external_kyc_id', $externalUserId)->first();

            if (!$user) {
                Log::warning('User not found for KYC webhook', ['externalUserId' => $externalUserId]);
                return response()->json(['status' => 404, 'error' => 'User not found']);
            }

            // Save full webhook payload
            $user->auto_kyc_credentials = $data;

            // ✅ Maintain current KYC status unless review is GREEN
            $kycStatus = $user->kyc;

            if (
                isset($data['reviewResult']['reviewAnswer']) &&
                $data['reviewResult']['reviewAnswer'] === 'GREEN'
            ) {
                $kycStatus = KYCStatus::Level2->value;
            }

            $user->kyc = $kycStatus;
            $user->save();

            Log::info('KYC status updated', [
                'user_id' => $user->id,
                'status' => $kycStatus
            ]);

            return response()->json(['status' => 200, 'success' => 'KYC updated']);
        } catch (\Throwable $th) {
            Log::error('Exception while processing KYC webhook', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return response()->json(['status' => 500, 'error' => 'Internal server error.'], 500);
        }
    }
}
