<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ReferralType;
use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralLink;
use App\Models\ReferralRelationship;
use App\Models\ReferralTarget;
use App\Models\User;
use DataTables;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:target-manage', ['only' => ['target', 'targetStore', 'targetUpdate']]);
        $this->middleware('permission:referral-list|referral-create|referral-edit|referral-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:referral-create', ['only' => ['store']]);
        $this->middleware('permission:referral-edit', ['only' => ['update']]);
        $this->middleware('permission:referral-delete', ['only' => ['delete']]);
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $targets = ReferralTarget::all();
        $investments = Referral::type(ReferralType::Investment);
        $deposits = Referral::type(ReferralType::Deposit);

        return view('backend.referral.index', compact('targets', 'investments', 'deposits'));
    }

    /**
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'referral_target_id' => Rule::unique('referrals')->where(fn ($query) => $query->where('type', $request->type)),
            'target_amount' => 'required',
            'bounty' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        Referral::create([
            'type' => $input['type'],
            'referral_target_id' => $input['referral_target_id'],
            'bounty' => $input['bounty'],
            'target_amount' => $input['target_amount'],
            'description' => $input['description'],
        ]);

        notify()->success('Referral created successfully');

        return redirect()->route('admin.referral.index');
    }
    public function addDirectReferral(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'ref_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();
//        dd($input);

        $pUser = User::find($input['ref_id']);
        $pUser->getReferrals();
        $referral = ReferralLink::where('user_id',$input['ref_id'])->first();
//        dd($referral);
        if (!is_null($referral)) {
            //remove referrals & IB
            ReferralRelationship::where('user_id',$input['user_id'])->delete();
            $childUser = User::find($input['user_id']);
            remove_child_agent($childUser);

            //add referrals & IB
            ReferralRelationship::create(['referral_link_id' => $referral->id, 'user_id' => $input['user_id']]);
            User::find($input['user_id'])->update([
                'ref_id' => $referral->user->id,
            ]);
            add_child_agent($pUser);

            notify()->success('Referral created successfully');

            return redirect()->back();
        }else{
            notify()->error('Did not find referral link of parent user. Please try again');

            return redirect()->back();
        }
    }

    /**
     * @return RedirectResponse
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $referral = Referral::find($request->id);

        if (null != $referral) {
            $referral->delete();
        }
        notify()->success('Referral Delete successfully');

        return redirect()->route('admin.referral.index');

    }
    public function deleteDirectReferral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $referral = User::find($request->id);

        if (null != $referral) {
            $referral->ref_id = null;
            $referral->save();
            ReferralRelationship::where('user_id',$request->id)->delete();
            remove_child_agent($referral);
        }
        notify()->success('Referral Delete successfully');

        return redirect()->back();

    }

    /**
     * @return Application|Factory|View
     */
    public function target()
    {
        $targets = ReferralTarget::all();

        return view('backend.referral.target', compact('targets'));
    }

    /**
     * @return RedirectResponse
     */
    public function targetStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        ReferralTarget::create(['name' => $request->name]);

        notify()->success('Target created successfully');

        return redirect()->route('admin.referral.target');
    }

    /**
     * @return RedirectResponse
     */
    public function targetUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
        $input = $request->all();
        ReferralTarget::find($input['id'])->update(['name' => $input['name']]);

        notify()->success('Target Update successfully');

        return redirect()->route('admin.referral.target');
    }

    /**
     * @return RedirectResponse
     */
    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required',
            'referral_target_id' => Rule::unique('referrals')->where(fn ($query) => $query->where('type', $request->type))->ignore($request->id),
            'target_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'bounty' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        Referral::find($input['id'])->update([
            'referral_target_id' => $input['referral_target_id'],
            'target_amount' => $input['target_amount'],
            'bounty' => $input['bounty'],
            'description' => $input['description'],
        ]);

        notify()->success('Referral Updated successfully');

        return redirect()->route('admin.referral.index');
    }

    public function directList($id, Request $request)
    {

        if ($request->ajax()) {
            $data = User::where('ref_id', $id)->latest();

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('avatar', 'backend.user.include.__avatar')
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->editColumn('full_name', function ($data) {
                    return $data->first_name . ' ' . $data->last_name;
                })
                ->editColumn('balance', function ($request) {
                    return $request->balance . ' ' . setting('site_currency');
                })
                ->editColumn('email', function ($request) {
                    return safe($request->email);
                })
                ->editColumn('username', function ($request) {
                    return safe($request->username);
                })

                ->addColumn('action', 'backend.user.include.__direct_referral_action')
                ->rawColumns(['avatar', 'kyc', 'status', 'action'])
                ->make(true);
        }
    }
}
