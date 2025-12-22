<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\KycLevelSlug;
use App\Enums\KYCStatus;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;

use App\Models\Kyc;
use App\Models\KycLevel;
use App\Models\KycSubLevel;
use App\Models\Userkyc;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Sumsub\AppTokenUsageExample\SumsubClient;
use Validator;
use App\Services\ActivityLogService;

class KycController extends Controller
{
    use ImageUpload, NotifyTrait;

    public function kyc()
    {
        $kycLevels = kyclevel::with('kyc_sub_levels')->where('status', true)->get();
        $totalActiveLevels = $kycLevels->count();
    //    dd($totalActiveLevels);
        $level1Settings = KycSubLevel::where('kyc_level_id', 1)
            ->get();
        $level2Settings = KycSubLevel::where('kyc_level_id', 2)
            ->get();

        // Get automatic KYC providers status
        $sumsubPlugin = \App\Models\Plugin::find(8); // Sumsub plugin
        $veriffPlugin = \App\Models\Plugin::where('name', 'Veriff (Automated KYC)')->first(); // Veriff plugin
        
        // Determine available automatic KYC providers
        $automaticProviders = [];
        if ($sumsubPlugin && $sumsubPlugin->status == 1) {
            $automaticProviders[] = [
                'name' => 'sumsub',
                'title' => 'Sumsub',
                'description' => 'AI-powered document verification',
                'route' => 'user.kyc.automatic',
                'icon' => 'mdi:id-card-outline'
            ];
        }
        if ($veriffPlugin && $veriffPlugin->status == 1) {
            $automaticProviders[] = [
                'name' => 'veriff',
                'title' => 'Veriff',
                'description' => 'Advanced identity verification with real-time fraud detection',
                'route' => 'user.kyc.veriff',
                'icon' => 'mdi:shield-check-outline'
            ];
        }

//        $userKyc = ::where('user_id',Auth::id())->get();
        return view('frontend::user.kyc.index', get_defined_vars());
    }

    public function basicKyc()
    {
        $user = Auth::user();
        $checkLevel1 = KycLevel::where('slug', KycLevelSlug::LEVEL1)->where('status', true)->first();
//       dd($user->kyc );
        if ($checkLevel1) {
            if ($user->kyc < 1) {
                notify()->error(__('kindly complete the level 1 first'));
                return redirect()->back();
            }
        }
        if ($user->kyc >= kyc_required_completed_level()) {
            notify()->error(__('Your Kyc already completed!'));
            return redirect()->back();
        }

        $kycs = Kyc::where('kyc_sub_level_id', 3)->where('status', true)->get();

        return view('frontend::user.kyc.basic.index', compact('kycs'));
    }

    public function kycLevel3()
    {
        $kycs = Kyc::where('status', true)->where('kyc_sub_level_id', 5)->get();

        return view('frontend::user.kyc.basic.level3', compact('kycs'));
    }

    public function advanceKyc()
    {
        $SUMSUB_SECRET_KEY = 'qxjq7aQDCDVDHZihPww9PBn9eCkAgiMi';
        $SUMSUB_APP_TOKEN = 'sbx:Lv8TQG3jd87Tk67GwJNKG1vk.4kwEhe5x2qU1swGIfWw1YyipF4cmlN3U';

        $user = \Auth::user();
        if (empty($user->kyc_token)) {
            $externalUserId = $user->id;
            $levelName = 'Kyc Verification';

            $testObject = new SumsubClient($SUMSUB_APP_TOKEN, $SUMSUB_SECRET_KEY);

            $applicantId = $testObject->createApplicant($externalUserId, $levelName);

            $testObject->getApplicantStatus($applicantId);

            $accessTokenInfo = $testObject->getAccessToken($externalUserId, $levelName);

            $user->update([
                'kyc_token' => $accessTokenInfo['token'],
            ]);
        }
        return view('frontend::user.kyc.advance.index');
    }

    public function UpdateKycStatus(Request $req)
    {
        try {
            $user = \Auth::user();
            $user->update([
                'kyc' => 1,
            ]);
            return response()->json(['status' => 200, 'success' => __('Verification completed')]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 200, 'error' => __('Somthing went wrong.')]);
        }
    }

    public function kycData($id)
    {
        $fields = Kyc::find($id)->fields;

        return view('frontend::user.kyc.data', compact('fields'))->render();
    }

    public function submit(Request $request)
    {
        $input = $request->all();
//        dd($input);
        $validator = Validator::make($input, [
            'kyc_id' => 'required',
//            'kyc_credential' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), __('Error'));

            return redirect()->back();
        }

        $kyc = Kyc::find($input['kyc_id']);

        $kycCredential = array_merge($input['kyc_credential'], ['kyc_type_of_name' => $kyc->name, 'kyc_time_of_time' => now()]);
        $user = \Auth::user();
        $checkLevel1 = KycLevel::where('slug', KycLevelSlug::LEVEL1)->where('status', true)->first();
        if ($checkLevel1) {
            if (!isset($user->kyc ) && $user->kyc < KYCStatus::Level1->value) {
                ActivityLogService::log('kyc_submission', "KYC submission failed due to level 1 not completed", [
                    'KYC Level' => 'Level 2',
                    'Status' => 'Failed',
                ]);
                notify()->error(__('kindly complete the level 1 first'));
                return redirect()->back();
            }
        }

        //validate the valid type of file or text
        foreach ($input['kyc_credential'] as $key => $value) {
            if ($value instanceof \Illuminate\Http\UploadedFile) {
                if (!$value->isValid()) {
                    ActivityLogService::log('kyc_submission', "KYC submission failed due to invalid file", [
                        'KYC Level' => 'Level 2',
                        'Status' => 'Failed',
                    ]);

                    notify()->error(__('The file in "' . $key . '" is not valid.'), __('Error'));
                    return redirect()->back();
                }
            }
        }

        if ($user->kyc_credential) {
            foreach (json_decode($user->kyc_credential, true) as $key => $value) {
                self::delete($value);
            }
        }
        foreach ($kycCredential as $key => $value) {
            if ($value instanceof \Illuminate\Http\UploadedFile && $value->isValid()) {
                $path = self::kycImageUploadTrait($value);
                if (!empty($path)) {
                    $kycCredential[$key] = $path;
                } else {
                    ActivityLogService::log('kyc_submission', "KYC submission failed due to invalid file", [
                        'KYC Level' => 'Level 2',
                        'Status' => 'Failed',
                        'Error' => 'Failed to upload file',
                    ]);

                    notify()->error(__('Failed to upload ') . $key, __('Error'));
                    return redirect()->back();
                }
            }
        }

        $user->update([
            'kyc_credential' => json_encode($kycCredential),
            'kyc' => KYCStatus::Pending->value,
        ]);
//        DB::table('user_kycs')->insert([
//            'user_id' => $user->id,
//            'kyclevel_id' => $kyc->kyc_level_id,
//            'kyclevelsetting_id' => $kycSettingsId->id,
//            'kyc_credential' => json_encode($kycCredential),
//            'kyc' => KYCStatus::Pending,
//            'is_level_2_completed' => 1,
//            'created_at' => now(),
//            'updated_at' => now(),
//        ]);
        // Data to insert into the pivot table
//        $pivotData = [
//            'kyc_credentials' => json_encode($kycCredential),
//            'status' => 2,
//            'created_at' => Carbon::now(),
//            'updated_at' => Carbon::now(),
//        ];
//
//// Insert data into the pivot table
//        $user->kycs()->attach($kyc->id, $pivotData);
        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[email]]' => $user->email,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
            '[[kyc_type]]' => $kyc->name,
            '[[status]]' => 'Pending',
        ];

        $this->mailNotify($user->email, 'kyc_request_level_2', $shortcodes);

        ActivityLogService::log('kyc_submission', "KYC submission successful", [
            'KYC Level' => 'Level 2',
            'Status' => 'Success',
            'KYC Type' => $kyc->name,
        ]);

        // Notify admin
        $adminEmails = parseEmails(setting('site_email', 'global'));
        foreach ($adminEmails as $email) {
            $this->mailNotify($email, 'admin_kyc_request_level_2', $shortcodes);
        }

        // Also notify all staff attached to this user (if any)
        try {
            $emails = getAttachedStaffAdminEmails($user->id);
            foreach ($emails as $email) {
                $this->mailNotify($email, 'admin_kyc_request_level_2', $shortcodes, true);
            }
        } catch (\Throwable $e) {
            \Log::warning('Failed to notify staff for KYC level 2 request', ['user_id' => $user->id, 'error' => $e->getMessage()]);
        }
        $this->pushNotify('kyc_request', $shortcodes, route('admin.kyc.pending'), $user->id);
        notify()->success(__(' KYC Updated'));
        return redirect()->route('user.kyc');
    }

    public function submitLevel3(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'kyc_id' => 'required',
            'kyc_credential' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), __('Error'));
            return redirect()->back();
        }

        $kyc = Kyc::find($input['kyc_id']);
        $kycCredential = array_merge($input['kyc_credential'], [
            'kyc_type_of_name' => $kyc->name,
            'kyc_time_of_time' => now(),
        ]);

        $user = \Auth::user();

        $checkLevel1 = KycLevel::where('slug', KycLevelSlug::LEVEL1)->first();
        if ($checkLevel1 && $checkLevel1->status == 1) {
            if (!isset($user->kyc) || $user->kyc < KYCStatus::Level1->value) {
                ActivityLogService::log('kyc_submission', "KYC submission failed due to level 1 not completed", [
                    'KYC Level' => 'Level 3',
                    'Status' => 'Failed',
                ]);

                notify()->error(__('kindly complete the level 1 first'));
                return redirect()->back();
            }
    }

        $checkLevel2 = KycLevel::where('slug', KycLevelSlug::LEVEL2)->first();
        if ($checkLevel2 && $checkLevel2->status == 1) {
            if ($user->kyc < KYCStatus::Level2->value) {
                ActivityLogService::log('kyc_submission', "KYC submission failed due to level 2 not completed", [
                    'KYC Level' => 'Level 3',
                    'Status' => 'Failed',
                ]);
                
                notify()->error(__('kindly complete the level 2 first'));
                return redirect()->back();
            }
    }

        // Validate the uploaded files
        foreach ($input['kyc_credential'] as $key => $value) {
            if ($value instanceof \Illuminate\Http\UploadedFile) {
                if (!$value->isValid()) {
                    ActivityLogService::log('kyc_submission', "KYC submission failed due to invalid file", [
                        'KYC Level' => 'Level 3',
                        'Status' => 'Failed',
                        'Error' => 'Invalid file',
                    ]);

                    notify()->error(__('The file in "' . $key . '" is not valid.'), __('Error'));
                    return redirect()->back();
                }
            }
        }

        // Delete previous uploaded files
        if ($user->kyc_level3_credential) {
            foreach (json_decode($user->kyc_level3_credential, true) as $key => $value) {
                self::delete($value);
            }
        }

        // Upload and replace with path
        foreach ($kycCredential as $key => $value) {
            if ($value instanceof \Illuminate\Http\UploadedFile && $value->isValid()) {
                $path = self::kycImageUploadTrait($value);
                if (!empty($path)) {
                    $kycCredential[$key] = $path;
                } else {
                    ActivityLogService::log('kyc_submission', "KYC submission failed due to failed to upload file", [
                        'KYC Level' => 'Level 3',
                        'Status' => 'Failed',
                        'Error' => 'Failed to upload file',
                    ]);

                    notify()->error(__('Failed to upload ') . $key, __('Error'));
                    return redirect()->back();
                }
            }
        }

        $user->update([
            'kyc_level3_credential' => json_encode($kycCredential),
            'kyc' => KYCStatus::PendingLevel3,
        ]);

        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[email]]' => $user->email,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
            '[[kyc_type]]' => $kyc->name,
            '[[status]]' => 'Pending',
        ];

        $this->mailNotify($user->email, 'kyc_request_level_3', $shortcodes);
        
        // Notify admin
        $adminEmails = parseEmails(setting('site_email', 'global'));
        foreach ($adminEmails as $email) {
            $this->mailNotify($email, 'admin_kyc_request_level_3', $shortcodes);
        }

        // Also notify all staff attached to this user (if any) for Level 3
        try {
            $emails = getAttachedStaffAdminEmails($user->id);
            foreach ($emails as $email) {
                $this->mailNotify($email, 'admin_kyc_request_level_3', $shortcodes, true);
            }
        } catch (\Throwable $e) {
            \Log::warning('Failed to notify staff for KYC level 3 request', ['user_id' => $user->id, 'error' => $e->getMessage()]);
        }
        $this->pushNotify('kyc_request', $shortcodes, route('admin.kyc.pending'), $user->id);
        ActivityLogService::log('kyc_submission', "KYC submission successful", [
            'KYC Level' => 'Level 3',
            'Status' => 'Success',
            'KYC Type' => $kyc->name,
        ]);

        notify()->success(__(' KYC Updated'));
        return redirect()->route('user.kyc');
    }
}
