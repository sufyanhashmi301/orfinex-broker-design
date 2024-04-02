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
