<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;
use Log;
use Sumsub\AppTokenUsageExample\SumsubClient;

class SumsubController extends Controller
{
    public function advanceKyc()
    {
        $sumsub = Plugin::find(8);
//        dd($sumsub);
        $sumsubstatus = $sumsub->status;
        $sumsubscredentials = json_decode($sumsub->data);

        $user = \Auth::user();
        $currentTime = Carbon::now();
        $lastUpdatedTime = $user->kyc_created_at;
        if (empty($lastUpdatedTime) || $currentTime->diffInMinutes($lastUpdatedTime) > 25 && $user->kyc === 0 && $sumsubstatus === 1) {

            $externalUserId = uniqid();
            $levelName = $sumsubscredentials->level_name;
//            dd($externalUserId);
            try {
//            dd($sumsubscredentials->app_token);
                $testObject = new SumsubClient($sumsubscredentials->app_token, $sumsubscredentials->app_secret_id);
//                dd($testObject);
                $applicantId = $testObject->createApplicant($externalUserId, $levelName);
//                dd($applicantId);
                $test = $testObject->getApplicantStatus($applicantId);
                $accessTokenInfo = $testObject->getAccessToken($externalUserId, $levelName);

                $user->update([
                    'kyc_token' => $accessTokenInfo['token'],
                    'kyc_created_at' => Carbon::now()
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
        try {
            $user = \Auth::user();
            $user->update([
                'kyc' => 1,
            ]);
            return response()->json(['status' => 200, 'success' => 'Verification completed']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 200, 'error' => 'Somthing went wrong.']);
        }
    }
}
