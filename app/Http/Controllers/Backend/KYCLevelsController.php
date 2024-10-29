<?php

namespace App\Http\Controllers\Backend;

use App\Enums\KYCStatus;
use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Models\KycLevel;
use App\Models\Kyclevelsetting;
use App\Models\KycSubLevel;
use App\Models\Plugin;
use App\Models\RiskProfileTag;
use App\Models\User;
use App\Models\UserRiskProfileTag;
use App\Traits\NotifyTrait;
use DataTables;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Validator;

class KYCLevelsController extends Controller
{
    use NotifyTrait;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('permission:risk-profile-tag-manage', ['only' => ['create', 'store', 'show', 'edit', 'update', 'destroy']]);
//        $this->middleware('permission:kyc-list', ['only' => ['KycPending', 'kycAll', 'KycRejected']]);
//        $this->middleware('permission:kyc-action', ['only' => ['depositAction', 'actionNow']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $kycLevels = KycLevel::with('kyc_sub_levels')->get();

        return view('backend.kyc_levels.index', compact('kycLevels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return string
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:risk_profile_tags,name',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
        $data = [
            'name' => $input['name'],
            'desc' => $input['desc'],
            'status' => $input['status'],
        ];

        $riskProfileTag = RiskProfileTag::create($data);
        notify()->success($riskProfileTag->name.' '.__('Risk Profile Tag Created'));

        return redirect()->route('admin.risk-profile-tag.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.risk_profile_tag.create');
    }

    /**
     * Display the specified resource.
     *
     * @return Application|Factory|View
     */
    public function show(RiskProfileTag $riskProfileTag)
    {
        return view('backend.risk_profile_tag.edit', compact('riskProfileTag'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $kycLevel = KycLevel::find($id);
        $kycSubLevels = KycSubLevel::with('kycs')
            ->where('kyc_level_id', $id)
//            ->where('status', true)
            ->get();
//        if($kycLevel=='level-2' && $kycSubLevels['name'] == 'Automatic')
        $level2Show = true;
//        dd($level2Show);
        $level2ManualKycs = Kyc::where('kyc_sub_level_id', 3)->get();
        $level3ManualKycs = Kyc::where('kyc_sub_level_id', 5)->get();
//        dd($level3ManualKycs);
        $sumsub = Plugin::findOrFail(8);
        return view('backend.kyc_levels.edit', get_defined_vars());


    }

    /**
     * Remove the specified resource from storage.
     *
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        RiskProfileTag::find($id)->delete();
        notify()->success(__('Risk Profile Tag Deleted Successfully'));

        return redirect()->route('admin.risk-profile-tag.index');
    }

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws Exception
     */

    /**
     * @return RedirectResponse
     */
    public function kycLevelUpdate(Request $request, $id)
    {
        $kycLevel  = KycLevel::findOrFail($id);
        $kycLevel->name = $request->name;
        $kycLevel->status = $request->status;
        $kycLevel->update();

//        dd($request->all());
        if($request->level2_setting== \App\Enums\KycType::MANUAL){
            KycSubLevel::where('name',\App\Enums\KycType::MANUAL)->update(['status'=>1]);
            KycSubLevel::where('name',\App\Enums\KycType::AUTOMATIC)->update(['status'=>0]);  }
        if($request->level2_setting == \App\Enums\KycType::AUTOMATIC){
//            dd($request->level2_setting);
            KycSubLevel::where('name',\App\Enums\KycType::MANUAL)->update(['status'=>0]);
//            dd(KycSubLevel::where('name',\App\Enums\KycType::AUTOMATIC)->first());
            KycSubLevel::where('name',\App\Enums\KycType::AUTOMATIC)->update(['status'=>1]);
        }
//        dd('ss');
        notify()->success(__('KYC level settings updated Successfully'));
        return redirect()->back()->with('success', __('KYC level settings updated successfully.'));
    }
    public function kycSubLevelUpdate(Request $request,$id){

        $kycLevelSettings = KycSubLevel::findOrFail($id);
        $kycLevelSettings->status = $request->status;
        $kycLevelSettings->update();
        notify()->success(__('KYC level settings updated Successfully'));
        return redirect()->back()->with('success', __('KYC level settings updated successfully.'));
    }
    public function tagDelete($id,Request $request)
    {
        $input = $request->all();

//        dd($input);
        $user = User::find($id);
        $user->riskProfileTags()->detach($request->input('risk_profile_tag_id'));
        // $user->riskProfileTags()->sync($selectedTags);
//        dd($input,$user,json_encode($input['risk_profile_tags'])    );
//        $user->update([
//            'risk_profile_tags' => isset($input['risk_profile_tags'])? json_encode($input['risk_profile_tags']):[],
//        ]);

//        $shortcodes = [
//            '[[full_name]]' => $user->full_name,
//            '[[email]]' => $user->email,
//            '[[site_title]]' => setting('site_title', 'global'),
//            '[[site_url]]' => route('home'),
//            '[[message]]' => $input['message'],
//            '[[status]]' => $input['status'],
//        ];
//        $this->mailNotify($user->email, 'kyc_action', $shortcodes);
//        $this->smsNotify('kyc_action', $shortcodes, $user->phone);
//        $this->pushNotify('kyc_action', $shortcodes, route('user.kyc'), $user->id);

        notify()->success(__('Risk Profile Tag Deleted Successfully'));

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:risk_profile_tags,name,'.$id,
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $data = [
            'name' => $input['name'],
            'desc' => $input['desc'],
            'status' => $input['status'],

        ];

        $riskProfileTag = RiskProfileTag::find($id);
        $riskProfileTag->update($data);
        notify()->success($riskProfileTag->name.' '.__(' Risk Profile Tag Updated'));

        return redirect()->route('admin.risk-profile-tag.index');
    }

}
