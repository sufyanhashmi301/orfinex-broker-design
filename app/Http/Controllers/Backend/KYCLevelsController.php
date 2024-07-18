<?php

namespace App\Http\Controllers\Backend;

use App\Enums\KYCStatus;
use App\Http\Controllers\Controller;
use App\Models\Kyclevel;
use App\Models\Kyclevelsetting;
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
        $kycLevels = Kyclevel::all();
        return view('backend.kyclevels.index', compact('kycLevels'));
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
        $kycLevel = Kyclevel::find($id);
        $kycLevelSettings = Kyclevelsetting::with('kyclevel')->where('kyc_level_id', $id)
            ->get();

        return view('backend.kyclevels.edit', get_defined_vars());

        
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
        //dd($request->all());
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:50',
            'settings' => 'array', // Ensures 'permissions' is an array
            'settings.*' => 'integer|exists:kyclevelsettings,id', // Ensures each permission is a valid kyclevelsetting ID
        ]);

        // Update the Kyclevel
        $kycLevel = Kyclevel::findOrFail($id);
        $kycLevel->name = $request->name;
        $kycLevel->save();

        // Get all KycLevelSetting IDs that belong to this KycLevel
        $kycLevelSettingIds = Kyclevelsetting::where('kyc_level_id', $id)->pluck('id')->toArray();

        // Determine which settings are checked
        $checkedSettings = $request->permissions ?? []; // Default to an empty array if no permissions are set

        // Update the status of each KycLevelSetting
        foreach ($kycLevelSettingIds as $settingId) {
            $setting = Kyclevelsetting::find($settingId);
            $setting->status = in_array($settingId, $checkedSettings);
            $setting->save();
        }
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
