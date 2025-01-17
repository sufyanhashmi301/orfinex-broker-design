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
        \Log::info('Incoming KYC request:', [
            'headers' => $request->headers->all(),
            'content' => $request->getContent(),
        ]);

        $response = $request->getContent(); // Get raw JSON content
        $responseData = json_decode($response, true);

        if (isset($responseData['externalUserId'])) {
            $externalUserId = $responseData['externalUserId'];
            $userId = Crypt::decrypt($externalUserId); // Decrypt to get userId

            try {
                $user = User::findOrFail($userId);

                // Update auto_kyc_credentials column with the response
                $user->auto_kyc_credentials = $responseData;

                // Determine KYC status based on response
                $kycStatus = KYCStatus::Level1->value; // Default

            if (isset($responseData['type']) && $responseData['type'] === 'applicantReviewed') {
                if (isset($responseData['reviewResult']['reviewAnswer']) &&
                    $responseData['reviewResult']['reviewAnswer'] === 'GREEN') {
                    $kycStatus = KYCStatus::Level2->value; // Verified
                }
            }

            // Update KYC status
            $user->kyc = $kycStatus;
            $user->save();

            return response()->json([
                'status' => 200,
                'success' => __('KYC status updated successfully'),
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
