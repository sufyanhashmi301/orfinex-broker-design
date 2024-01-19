<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\KYCStatus;
use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Illuminate\Http\Request;
use Validator;

class KycController extends Controller
{
    use ImageUpload, NotifyTrait;

    public function kyc()
    {
        return view('frontend::user.kyc.index');
    }

    public function basicKyc()
    {
        $kycs = Kyc::where('status', true)->get();

        return view('frontend::user.kyc.basic.index', compact('kycs'));
    }

    public function kycData($id)
    {
        $fields = Kyc::find($id)->fields;

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

        if ($user->kyc_credential) {
            foreach (json_decode($user->kyc_credential, true) as $key => $value) {
                self::delete($value);
            }
        }
//        $kycCredential1[] = json_decode('{"Front Side":{},"Back Side":{},"kyc_type_of_name":"National ID Card","kyc_time_of_time":"2024-01-04T22:51:47.025821Z"}');
//        dd($kycCredential['Front Page']);
//        dd($kycCredential1);
        foreach ($kycCredential as $key => $value) {
//dd($key,$value);
            if (is_file($value)) {
//                dd(self::kycImageUploadTrait($value));
                $path = self::kycImageUploadTrait($value);
                if(isset($path) && !empty($path)) {
                    $kycCredential[$key] = $path;
                }else{
                    notify()->error('kindly Set the '.$key, 'Error');
                    return redirect()->back();
                }
            }
        }
////        dd($kycCredential['Front Page']);
//        if(isset($kycCredential['Front Page'])){
//
//        }
//        dd($kycCredential);

        $user->update([
            'kyc_credential' => json_encode($kycCredential),
//            'kyc' => KYCStatus::Pending,
        ]);
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
