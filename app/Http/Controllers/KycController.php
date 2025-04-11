<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Kyc;
use App\Models\User;
use App\Models\Plugin;
use sumsub\SumsubClient;
use App\Models\KycMethod;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Illuminate\Http\Request;
use App\Enums\KycStatusEnums;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class KycController extends Controller
{
    use ImageUpload, NotifyTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $kycs_filter = false;
        if(isset($request->status)){
            // Filter users wrt status when status exists
            if (in_array($request->status, (new \ReflectionClass(KycStatusEnums::class))->getConstants())) {
                // Handle the logic here if the status is valid
                $kycs = KYC::where('status', $request->status)->paginate(15);
                $title = ucfirst($request->status) . ' KYCs';
                $kycs_filter = true;
            }
        }

        // If search
        if(isset($request->search)) {
            
            $kycs = Kyc::whereHas('user', function ($query) use ($request) {
                        $query->where('first_name', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('email', 'LIKE', '%' . $request->search . '%');
                        })
                        ->paginate(15);
            
            $kycs_filter = true;
            $title = 'Search results for: ' . $request->search;
        }

        // if status is unknown then show all users
        if(!$kycs_filter) {
            $kycs = Kyc::paginate(15);
            $title = 'All KYCs';
            if($request->status != 'all') {
                return redirect()->route('admin.kyc.index', ['status' => 'all']);
            }
        }

        return view('backend.kyc.index', get_defined_vars());
    }

    /**
     * Admin KYC action
     */
    public function action(Request $request) {
        
        if($request->action == 'approve') {
            $kyc = Kyc::findorFail($request->id);
            $kyc->update([
                'method' => 'Manual',
                'status' => KycStatusEnums::VERIFIED,
                'verified_at' => Carbon::now()
            ]);

            $shortcodes = [
                '[[site_url]]' => route('home'),
                '[[full_name]]' => $kyc->user->first_name . ' ' . $kyc->user->last_name,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[message]]' => $request->message
            ];
            $this->mailNotify($kyc->user->email, 'kyc_approve', $shortcodes);

            notify()->success('KYC marked as approved successfully');
        }

        if($request->action == 'reject') {
            Kyc::findorFail($request->id)->update([
                'method' => '',
                'status' => KycStatusEnums::UNVERIFIED,
                'verified_at' => null,
                'data' => null
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
                $path = self::imageUploadTrait($value, null, 'user/kycs/' . Auth::id());
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
            $sumsub = $this->sumsub($user);

            if(!$sumsub) {
                notify()->error('Unknown Error Ocurred!');
                return redirect()->back();
            }

            return view('frontend::user.kyc.automatic', $sumsub);
        } else {
            return redirect()->route('user.verification.index');
        }

    }

    /**
     * Sumsub helper fn()
     */
    public function sumsub($user) {

        $sumsub_plugin = Plugin::find(8);
        $sumsub_status = $sumsub_plugin->status;
        $sumsub_credentials = json_decode($sumsub_plugin->data);
        $current_time = Carbon::now();
        $last_updated_time = $user->kyc_created_at;

        $encypted_user_id = Crypt::encrypt($user->id);


        if (empty($user->kyc_token) || $current_time->diffInMinutes($last_updated_time) > 25) {
            
            try {
                $sumsub = new SumsubClient($sumsub_credentials->app_token, $sumsub_credentials->app_secret_id);
                $applicantId = $sumsub->createApplicant($encypted_user_id, $sumsub_credentials->level_name);
                $sumsub->getApplicantStatus($applicantId);
                $accessTokenInfo = $sumsub->getAccessToken($encypted_user_id, $sumsub_credentials->level_name);
                $user->update([
                    'kyc_token' => $accessTokenInfo['token'],
                    'kyc_created_at' => Carbon::now(),
                ]);
            } catch (\Throwable $th) {
                return false;
            }
        }
        return get_defined_vars();
    }

    /**
     * User: Automatic KYC Updater
     * Later use webhook when the site is live
     */
    public function updateAutomaticKyc(Request $request) {
        $user = Auth::user();
        $response = $request->all(); 

        if (isset($response['reviewAnswer']) && $response['reviewAnswer'] == 'GREEN') {
            // Success Case
            $user->kyc->update([
                'method' => 'Sumsub',
                'status' => KycStatusEnums::VERIFIED,
                'verified_at' => Carbon::now()
            ]);
        } elseif (isset($response['reviewAnswer']) && $response['reviewAnswer'] == 'RED') {
            // Rejection Case
            $user->kyc->update([
                'status' => KycStatusEnums::UNVERIFIED
            ]);
        }

        return true;
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
