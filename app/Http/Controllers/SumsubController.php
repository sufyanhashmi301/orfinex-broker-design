<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Sumsub\AppTokenUsageExample\SumsubClient;

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
        if (empty($lastUpdatedTime) || $currentTime->diffInMinutes($lastUpdatedTime) > 25 && $user->kyc === 0 && $sumsubstatus === 1) {
            $externalUserId = uniqid();
            $levelName = 'Orfinex Verification';
            try {
                $testObject = new SumsubClient($sumsubscredentials->app_token, $sumsubscredentials->app_secret_id);
                $applicantId = $testObject->createApplicant($externalUserId, $levelName);
                $test = $testObject->getApplicantStatus($applicantId);
                $accessTokenInfo = $testObject->getAccessToken($externalUserId, $levelName);

                $user->update([
                    'kyc_token' => $accessTokenInfo['token'],
                    'applicant_id' => $applicantId,
                    'kyc_created_at' => Carbon::now()
                ]);
            } catch (\Throwable $th) {
                return redirect()->back();
            }
        }
        return view('frontend::user.kyc.advance.index', compact('sumsubstatus', 'currentTime', 'lastUpdatedTime'));
    }
    public function UpdateKycStatusByWebhook(Request $request)
    {
        $webhookData = $request->all();
        // Extract relevant data from webhook response
        $applicantId = $webhookData['applicantId'];
        $reviewStatus = $webhookData['reviewStatus'];
        $reviewResult = $webhookData['reviewResult']['reviewAnswer'];

// Find user by external user ID
        $user = User::where('applicant_id', $applicantId)->first();

        if ($user) {
            // Update KYC status based on review status and result
            if ($reviewStatus === 'completed' && $reviewResult === 'GREEN') {
                $user->update([
                    'kyc' => 1,
                    'kyc_credential' => $webhookData,
                ]);
            } else {
                // Handle other review statuses or results accordingly
            }
        } else {
            // Handle case where user is not found
        }

        return redirect()->route('user.kyc.advance');
//        dd($request->all());
//        $data = $request->all();
//        $user = User::where();
//        // Log the data
//        Log::info('WebHooks by Sumsub:', $data);
//        try {
////            $user = \Auth::user();
////            $user->update([
////                'kyc' => 1,
////            ]);
//            $user->update([
//                'kyc_credential' => $data,
//            ]);
//            return response()->json(['status' => 200, 'success' => 'Verification completed']);
//        } catch (\Throwable $th) {
//            return response()->json(['status' => 200, 'error' => 'Somthing went wrong.']);
//        }
    }
    public function UpdateKycStatus(Request $request)
    {
//        dd($request->all());
        $data = $request->all();
        $user = User::find(1);
        // Log the data
        Log::info('WebHooks by Sumsub:', $data);
        try {
//            $user = \Auth::user();
//            $user->update([
//                'kyc' => 1,
//            ]);
            $user->update([
                'kyc_credential' => $data,
            ]);
            return response()->json(['status' => 200, 'success' => 'Verification completed']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 200, 'error' => 'Somthing went wrong.']);
        }
    }
}
