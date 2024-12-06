<?php

namespace App\Http\Controllers;

use App\Models\Plugin;

use App\Models\User;
use Illuminate\Http\Request;

// Use the correct class for the request
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use App\Enums\KYCStatus;
use sumsub\SumsubClient;

class SumsubController extends Controller
{
    public function advanceKyc()
    {
        $sumsub = Plugin::find(8);
        $sumsubstatus = $sumsub->status;
        $sumsubscredentials = json_decode($sumsub->data);

        $user = \Auth::user();
        $currentTime = Carbon::now();
        $lastUpdatedTime = $user->kyc_created_at;

        if (empty($lastUpdatedTime) ||
            $currentTime->diffInMinutes($lastUpdatedTime) > 25 &&
            $user->kyc < KYCStatus::Level2->value && $sumsubstatus === 1
        ) {
        $externalUserId = Crypt::encrypt($user->id);
        $levelName = $sumsubscredentials->level_name;

        try {
            $testObject = new SumsubClient($sumsubscredentials->app_token, $sumsubscredentials->app_secret_id);

            $applicantId = $testObject->createApplicant($externalUserId, $levelName);

            $accessTokenInfo = $testObject->getAccessToken($externalUserId, $levelName);

            $user->update([
                'kyc_token' => $accessTokenInfo['token'],
                'kyc_created_at' => Carbon::now(),
            ]);
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }

        return view('frontend::user.kyc.advance.index', compact('sumsubstatus', 'currentTime', 'lastUpdatedTime'));
    }

    public function UpdateKycStatus(Request $request)
    {
        Log::info('Webhook received:', $request->all());
        $response = $request->getContent(); // Get raw JSON content from the request

        // Decode the JSON into an associative array
        $responseData = json_decode($response, true);

        // Check if the response contains the 'externalUserId' key
        if (isset($responseData['externalUserId'])) {
            $externalUserId = $responseData['externalUserId'];
            $userId = Crypt::decrypt($externalUserId); // Decrypt the externalUserId to get the userId

            // Log the userId correctly
//            Log::info('User ID:', ['userId' => $userId]);

            try {
                $user = User::findOrFail($userId); // Ensure the user exists
                $user->update([
                    'kyc' => KYCStatus::Level2->value
            ]);

            return response()->json([
                'status' => 200,
                'success' => __('KYC Verification completed'),
            ]);
        } catch (\Throwable $th) {
                Log::error('Error in UpdateKycStatus:', ['error' => $th->getMessage()]);

                return response()->json([
                    'status' => 500,
                    'error' => __('Something went wrong'),
                ]);
            }
        }

        return response()->json([
            'status' => 400,
            'error' => __('Invalid data received.'),
        ]);
    }
}
