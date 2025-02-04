<?php

namespace App\Http\Controllers\Frontend;

use Validator;
use Carbon\Carbon;
use App\Models\Kyc;
use App\Models\User;
use App\Models\KycLevel;
use Sumsub\SumsubClient;
use App\Enums\KycLevelSlug;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KycMethod;
use Illuminate\Support\Facades\Auth;


class KycController extends Controller
{
    use ImageUpload, NotifyTrait;

    public function kyc()
    {
        
    }

    public function basicKyc()
    {
        $user = Auth::user();
        $kyc_method = KycMethod::where('status', 1)->first();
        $kyc_verified = false;

        return view('frontend::user.kyc.basic.index', get_defined_vars());
    }


    public function advanceKyc()
    {
        $SUMSUB_SECRET_KEY = 'qxjq7aQDCDVDHZihPww9PBn9eCkAgiMi';
        $SUMSUB_APP_TOKEN = 'sbx:Lv8TQG3jd87Tk67GwJNKG1vk.4kwEhe5x2qU1swGIfWw1YyipF4cmlN3U';

        $user = \Auth::user();
        if (empty($user->kyc_token)) {
            $externalUserId = $user->id;
            $levelName = 'Verification';

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
            return response()->json(['status' => 200, 'success' => 'Verification completed']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 200, 'error' => 'Somthing went wrong.']);
        }
    }

    public function kycData($option_name)
    {
        $kyc_method = KycMethod::where('slug', 'manual')->first();
        $fields = collect($kyc_method->data)->firstWhere('name', $option_name)['fields'];
    
        return view('frontend::user.kyc.data', compact('fields'))->render();
    }

    public function submit(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'kyc_id' => 'required',
            'kyc_credential' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $kyc = Kyc::find($input['kyc_id']);

        $kycCredential = array_merge($input['kyc_credential'], ['kyc_type_of_name' => $kyc->name, 'kyc_time_of_time' => now()]);
        $user = \Auth::user();
        $checkLevel1 = KycLevel::where('slug', KycLevelSlug::LEVEL1)->where('status', true)->first();
        if ($checkLevel1) {
            if ($user->email_verified_at == null) {
                notify()->error('kindly complete the level 1 first');
                return redirect()->back();
            }
        }
        if ($user->kyc_credential) {
            foreach (json_decode($user->kyc_credential, true) as $key => $value) {
                self::delete($value);
            }
        }
        foreach ($kycCredential as $key => $value) {
            if (is_file($value)) {
                $path = self::kycImageUploadTrait($value);
                if (isset($path) && !empty($path)) {
                    $kycCredential[$key] = $path;
                } else {
                    notify()->error('kindly Set the ' . $key, 'Error');
                    return redirect()->back();
                }
            }
        }

        $user->update([
            'kyc_credential' => json_encode($kycCredential),
            'kyc' => 2,
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
        $pivotData = [
            'kyc_credentials' => json_encode($kycCredential),
            'status' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

// Insert data into the pivot table
        $user->kycs()->attach($kyc->id, $pivotData);
        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[email]]' => $user->email,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
            '[[kyc_type]]' => $kyc->name,
            '[[status]]' => 'Pending',
        ];

        $this->mailNotify(setting('site_email', 'global'), 'kyc_request', $shortcodes);
        $this->pushNotify('kyc_request', $shortcodes, route('admin.kyc.pending'), $user->id);
        notify()->success(__(' KYC Updated'));
        return redirect()->route('user.kyc');
    }

}
