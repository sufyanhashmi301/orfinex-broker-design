<?php


namespace App\Traits;
use App\Enums\IBStatus;

use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait ProfileTrait
{
    /**
     * @param $data
     * @version 1.0.0
     * @since 1.0
     */
    private function updateUserInfo($data)
    {
        if (empty($data)) {
            return;
        }

        $user = User::find(auth()->user()->id);
        $user->update($data);
    }

    /**
     * @param $data
     * @version 1.0.0
     * @since 1.0
     */
    private function updateProfileInfo($data)
    {
        if (empty($data)) {
            return;
        }


//dd($data);
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
            UserMeta::updateOrCreate([
                'user_id' => auth()->user()->id,
                'meta_key' => $key
            ], ['meta_value' => strip_tags($value) ?? null]);
        }
    }

    /**
     * @param Request $request
     * @version 1.0.0
     * @since 1.0
     */
    public function savePersonalInfo(Request $request)
    {
        $userInfo = $request->only(['name']);
        $this->updateUserInfo($userInfo);

        $profileInfo = $request->only(['profile_phone', 'profile_dob', 'profile_whatsapp', 'profile_display_name','profile_address_line_1', 'profile_address_line_2', 'profile_city',  'profile_country',  'nationality']);
        $profileInfo['profile_display_full_name'] = $request->get('profile_display_full_name', 'off');
        $user = auth()->user();
        if($user->profile_phone != $profileInfo['profile_phone'] ){
            $user->update(['phone_verify'=>null,'profile_phone'=>$profileInfo['profile_phone']]);
//            $this->sendPhoneCode();
        }
        $this->updateProfileInfo($profileInfo);


    }
    public function sendPhoneCode()
    {
//        dd($request->all());
        $user = auth()->user();
        VerifyToken::updateOrCreate([
            'user_id' => $user->id,
        ], ['phone_code' =>  mt_rand(10001, 99999)]);


        $twilio = new TwilioService();
        $receiverNumber = $user->profile_phone;
        $message = __("Your :appName verification code is: :code. Don't share this code with anyone; Our employees will never ask for the code.", ['appName' => config('app.name'), 'code' => data_get($user, 'verify_token.phone_code')]);
        $twilio->sendSMS($receiverNumber, $message);

//        return response()->json(['title' => 'Resend Code', 'success' => __('Successfully sent code to your provided phone number.')]);

//        return redirect()->route('account.congrats');
    }


    /**
     * @param Request $request
     * @version 1.0.0
     * @since 1.0
     */
    public function saveAddressInfo(Request $request)
    {
        $addressInfo = $request->only(['profile_address_line_1', 'profile_address_line_2', 'profile_city',  'profile_country']);
        $this->updateProfileInfo($addressInfo);
    }
    public function updateThemeSkin(Request $request)
    {
        $addressInfo = $request->only(['theme_skin']);
        $this->updateProfileInfo($addressInfo);
    }



    public function completeProfile($name, $data)
    {
        auth()->user()->update(['name' => $name,'profile_phone' => $data['profile_phone'],'first_login_at' => now()]);

        $this->updateProfileInfo($data);
    }
    public function completeIBProfile($data)
    {
//        dd($data,IBStatus::PENDING);
        auth()->user()->update(['ib_status' => IBStatus::PENDING]);
        $this->updateProfileInfo($data);
    }
}
