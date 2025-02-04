<?php

namespace App\Http\Controllers;

use App\Models\Kyc;
use App\Models\User;
use sumsub\SumsubClient;
use App\Models\KycMethod;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use App\Enums\KycStatusEnums;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KycController extends Controller
{
    use ImageUpload;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $kycs = Kyc::paginate(15);
        $title = 'All KYC Logs';

        return view('backend.kyc.index', get_defined_vars());
    }

    /**
     * Admin KYC action
     */
    public function action(Request $request) {
        
        if($request->action == 'approve') {
            Kyc::findorFail($request->id)->update([
                'status' => KycStatusEnums::VERIFIED
            ]);

            notify()->success('KYC marked as approved successfully');
        }

        if($request->action == 'reject') {
            Kyc::findorFail($request->id)->update([
                'status' => KycStatusEnums::UNVERIFIED
            ]);

            notify()->success('KYC marked as rejected successfully');
        }

        return redirect()->back();
    }

    /**
     * User: Manual KYC Page
     */
    public function manualKyc() {
        $user = Auth::user();
        $kyc_method = KycMethod::where('status', 1)->first();
        
        if($kyc_method->slug != 'manual') {
            return redirect()->back();
        }
        
        if($user->kyc->status == KycStatusEnums::UNVERIFIED) {
            return view('frontend::user.kyc.manual', get_defined_vars());
        } else {
            return redirect()->route('user.verification.index');
        }

    }


    /**
     * User: Manual KYC Get Fields using AJAX
     */
    public function manualKycData($option_name) {
        $kyc_method = KycMethod::where('slug', 'manual')->first();
        $fields = collect($kyc_method->data)->firstWhere('name', $option_name)['fields'];
    
        return view('frontend::user.kyc.data', compact('fields'))->render();
    }

    /**
     * User: Manual KYC Get Fields using AJAX
     */
    public function updateManualKyc(Request $request) {

        $input = $request->all();

        $validator = Validator::make($input, [
            'kyc_option' => 'required',
            'kyc_credential' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        // Kyc Crednetials
        $kyc_credential = $request->kyc_credential;

        // Handle files
        foreach ($kyc_credential as $key => $value) {
            if (is_file($value)) {
                $path = self::kycImageUploadTrait($value);
                if (isset($path) && !empty($path)) {
                    $kyc_credential[$key] = $path;
                } else {
                    notify()->error('kindly Set the ' . $key, 'Error');
                    return redirect()->back();
                }
            }
        }

        $user = User::find(Auth::id());
        $user->kyc->update([
            'data' => $kyc_credential,
            'method' => 'manual',
            'status' => KycStatusEnums::PENDING
        ]);

        // Email and Push notifications remaining

        notify()->success('KYC details sent for review');
        return redirect()->route('user.verification.index');
    }   

    /**
     * User: Automatic KYC Page
     */
    public function automaticKyc() {
        $user = Auth::user();
        $kyc_method = KycMethod::where('status', 1)->first();

        if($kyc_method->slug != 'sumsub') {
            return redirect()->back();
        }
        
        if($user->kyc->status == KycStatusEnums::UNVERIFIED) {
            
            $SUMSUB_SECRET_KEY = 'qxjq7aQDCDVDHZihPww9PBn9eCkAgiMi';
            $SUMSUB_APP_TOKEN = 'sbx:Lv8TQG3jd87Tk67GwJNKG1vk.4kwEhe5x2qU1swGIfWw1YyipF4cmlN3U';

            if (empty($user->kyc_token)) {
                
                $levelName = 'basic-kyc-level';

                $sumsub = new SumsubClient($SUMSUB_APP_TOKEN, $SUMSUB_SECRET_KEY);

                $applicantId = $sumsub->createApplicant($user->id . '2', $levelName);

                $sumsub->getApplicantStatus($applicantId);

                $accessTokenInfo = $sumsub->getAccessToken($user->id . '2', $levelName);

                $user->update([
                    'kyc_token' => $accessTokenInfo['token'],
                ]);
            }
            return view('frontend::user.kyc.automatic');

        } else {
            return redirect()->route('user.verification.index');
        }

    }

    /**
     * User: Automatic KYC Updater
     */
    public function updateAutomaticKyc(Request $request) {
        $user = Auth::user();

        try {
            $user->kyc->update([
                'status' => KycStatusEnums::VERIFIED,
            ]);
            return response()->json(['status' => 200, 'success' => 'Verification completed']);
        } catch (\Throwable $th) {
            $user->kyc->update([
                'status' => KycStatusEnums::UNVERIFIED,
            ]);
            return response()->json(['status' => 200, 'error' => 'Somthing went wrong.']);
        }
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
