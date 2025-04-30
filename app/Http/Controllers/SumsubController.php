<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Enums\KYCStatus;
use sumsub\SumsubClient;

class SumsubController extends Controller
{
    public function advanceKyc()
    {
        $plugin = Plugin::find(8);
        if (!$plugin || $plugin->status !== 1) {
            return redirect()->back()->with('error', 'KYC system not available.');
        }

        $credentials = json_decode($plugin->data);
        $levelName = $credentials->level_name ?? null;
        $user = \Auth::user();
        $now = Carbon::now();

        try {
            if ($user->kyc < KYCStatus::Level2->value) {
                $client = new SumsubClient(
                    $credentials->app_token,
                    $credentials->app_secret_id
                );

                $externalId = $user->external_kyc_id;
                $shouldCreateNew = false;

                // ðŸ‘‡ Check if external ID exists and is valid on Sumsub
                if ($externalId) {
                    try {
                        $applicant = $client->getApplicant($externalId);
                        Log::info('Sumsub applicant reused', ['external_id' => $externalId]);

                        // Check if it's already rejected, deleted or unusable (optional logic)
                        if (!isset($applicant['review'])) {
                            $shouldCreateNew = true;
                        }
                    } catch (\Throwable $e) {
                        $shouldCreateNew = true;
                        Log::warning('Sumsub getApplicant failed. Will create new.', [
                            'external_id' => $externalId,
                            'error' => $e->getMessage(),
                        ]);
                    }
                } else {
                    $shouldCreateNew = true;
                }

                // âœ… Create new ID only if needed
                if ($shouldCreateNew) {
                    $externalId = 'unil-' . sha1($user->id . '-' . $user->email . '-' . $now->timestamp);
                    $user->external_kyc_id = $externalId;
                    $user->kyc_token = null;
                    $user->kyc_created_at = null;
                    $user->save();

                    $client->createApplicant($externalId, $levelName);
                    Log::info('New Sumsub applicant created', ['external_id' => $externalId]);
                }

                // âœ… Always generate new access token (needed per session)
                $accessTokenInfo = $client->getAccessToken($externalId, $levelName);
                $user->update([
                    'kyc_token' => $accessTokenInfo['token'],
                    'kyc_created_at' => $now,
                ]);

                Log::info('Sumsub token refreshed', ['user_id' => $user->id]);
            }
        } catch (\Throwable $th) {
            Log::error('Sumsub KYC init failed', [
                'user_id' => $user->id,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'KYC initialization failed: ' . $th->getMessage());
        }

        return view('frontend::user.kyc.advance.index', [
            'sumsubstatus' => $plugin->status,
            'currentTime' => $now,
            'lastUpdatedTime' => $user->kyc_created_at,
        ]);
    }
}
