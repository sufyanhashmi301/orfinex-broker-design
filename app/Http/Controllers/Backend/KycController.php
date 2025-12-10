<?php

namespace App\Http\Controllers\Backend;

use App\Enums\KycLevelSlug;
use App\Enums\KYCStatus;
use App\Http\Controllers\Controller;
use App\Exports\LevelTwoPendingExport;
use App\Exports\LevelThreePendingExport;
use App\Exports\RejectedKycExport;
use App\Exports\AllKycExport;
use App\Models\Kyc;
use App\Models\KycLevel;
use App\Models\Kyclevelsetting;
use App\Models\KycSubLevel;
use App\Models\User;
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
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use App\Traits\ImageUpload;
use Carbon\Carbon;

class KycController extends Controller
{
    use ImageUpload, NotifyTrait;
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:kyc-form-manage', ['only' => ['create', 'store', 'show', 'edit', 'update', 'destroy']]);
        $this->middleware('permission:kyc-list', ['only' => ['KycPending', 'kycAll', 'KycRejected']]);
        $this->middleware('permission:kyc-action', ['only' => ['depositAction', 'actionNow']]);
        $this->middleware('permission:kyc-export', ['only' => ['export']]);


    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $kycs = Kyc::all();

        return view('backend.kyc.index', compact('kycs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return string
     */
//    public function store(Request $request)
//    {
//        $input = $request->all();
//        $validator = Validator::make($input, [
//
//            'name' => 'required|unique:kycs,name',
//            'status' => 'required',
//            'fields' => 'required',
//        ]);
//        if ($validator->fails()) {
//            notify()->error($validator->errors()->first(), 'Error');
//
//            return redirect()->back();
//        }
//        $kycLevel = KycLevel::where('slug','level-2')->first();
//        $data = [
//            'kyc_level_id' => $kycLevel->id,
//            'name' => $input['name'],
//            'status' => $input['status'],
//            'fields' => json_encode($input['fields']),
//        ];
//
//        $kyc = Kyc::create($data);
//        $kycSettings = new Kyclevelsetting();
//        $kycSettings->title = $input['name'];
//        $kycSettings->unique_code = 'manual';
//        $kycSettings->kyc_level_id = $kycLevel->id;
//        $kycSettings->kyc_id = $kyc->id;
//        $kycSettings->status = true;
//        $kycSettings->save();
//        notify()->success($kyc->name.' '.__(' KYC Created'));
//
//        return redirect()->route('admin.kyc-form.index');
//    }
    public function storeLevel2(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:kycs,name',
            'kyc_sub_level_id' => 'required',
            'status' => 'required',
            'fields' => 'required',
        ]);
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
        $kycLevel = KycLevel::where('slug',KycLevelSlug::LEVEL2)->first();
        $data = [
            'kyc_sub_level_id' => get_hash($input['kyc_sub_level_id']),
            'name' => $input['name'],
            'status' => $input['status'],
            'fields' => json_encode($input['fields']),
        ];
        $kyc = Kyc::create($data);
        KycSubLevel::where('name',\App\Enums\KycType::MANUAL)->update(['status'=>1]);
        KycSubLevel::where('name',\App\Enums\KycType::AUTOMATIC)->update(['status'=>0]);
        notify()->success($kyc->name.' '.__(' KYC Created'));

        return redirect()->back();
    }
    public function storeLevel3(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:kycs,name',
            'kyc_sub_level_id' => 'required',
            'status' => 'required',
            'fields' => 'required',
        ]);
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
        $kycLevel = KycLevel::where('slug',KycLevelSlug::LEVEL3)->first();
        $data = [
            'kyc_sub_level_id' => get_hash($input['kyc_sub_level_id']),
            'name' => $input['name'],
            'status' => $input['status'],
            'fields' => json_encode($input['fields']),
        ];
        $kyc = Kyc::create($data);
        notify()->success($kyc->name.' '.__(' KYC Created'));

        return redirect()->back();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $levels = KycLevel::orderBy('id','desc')->get();
        return view('backend.kyc.create',get_defined_vars());
    }

    /**
     * Display the specified resource.
     *
     * @return Application|Factory|View
     */
    public function show(Kyc $kyc)
    {
        return view('backend.kyc.edit', compact('kyc'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $kyc = Kyc::find($id);

        return view('backend.kyc.edit', compact('kyc'));
    }
    public function editKycLevel2($id)
    {
        $kyc = Kyc::find($id);
        if (!$kyc) {
            return response()->json(['error' => 'KYC record not found'], 404);
        }
        return response()->json(['kyc' => $kyc]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        Kyc::find($id)->delete();
        notify()->success(__('KYC Deleted Successfully'));

        return redirect()->route('admin.kyc-form.index');
    }

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws Exception
     */
    public function KycPending(Request $request)
{
    $loggedInUser = auth()->user();

    if ($request->ajax()) {
        $filters = $request->only(['global_search', 'status', 'created_at']);

        // Use the helper to get the accessible users with filters
        $data = getAccessibleUserIds($filters)
            ->where('kyc', KYCStatus::Pending->value);
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('time', 'backend.kyc.include.__time')
            ->addColumn('user', 'backend.kyc.include.__user')
            ->addColumn('type', 'backend.kyc.include.__type')
            ->editColumn('status', 'backend.kyc.include.__status')
            ->addColumn('action', 'backend.kyc.include.__action')
            // Server-side ordering mappings
            ->orderColumn('updated_at', 'users.updated_at $1')
            ->orderColumn('time', 'users.updated_at $1')
            ->orderColumn('user', 'users.first_name $1')
            ->orderColumn('status', 'users.kyc $1')
            ->rawColumns(['time', 'user', 'type', 'status', 'action'])
            ->make(true);
    }

    return view('backend.kyc.pending');
}

    public function KycLevel3Pending(Request $request)
    {
        $loggedInUser = auth()->user();

        if ($request->ajax()) {
            $filters = $request->only(['global_search', 'status', 'created_at']);
 // Get users the logged-in user is allowed to access (based on roles/permissions/attachment)
        $data = getAccessibleUserIds($filters)
            ->whereNotNull('kyc_level3_credential')
            ->where('kyc', KYCStatus::PendingLevel3->value);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('time', function ($row) {
                    return $row->kyc_time_level3;
                })
                ->addColumn('user', 'backend.kyc.include.__user')
                ->addColumn('type', function ($row) {
                    return $row->kyc_type_level3;
                })
                ->editColumn('status', 'backend.kyc.include.__statuslevel3')
                ->addColumn('action', 'backend.kyc.include.__action')
                // Server-side ordering mappings
                ->orderColumn('updated_at', 'users.updated_at $1')
                ->orderColumn('time', 'users.updated_at $1')
                ->orderColumn('user', 'users.first_name $1')
                ->orderColumn('status', 'users.kyc $1')
                ->rawColumns(['time', 'user', 'type', 'status', 'action'])
                ->make(true);
        }

        return view('backend.kyc.level3.pending');
    }


    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws Exception
     */
    public function KycRejected(Request $request)
{
    $loggedInUser = auth()->user();

    if ($request->ajax()) {
        $filters = $request->only(['global_search', 'status', 'created_at']);

       // Use the helper to get users based on role/permissions/attachment
        $data = getAccessibleUserIds($filters)
            ->where('kyc', KYCStatus::Rejected->value);

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('time', 'backend.kyc.include.__time')
            ->addColumn('user', 'backend.kyc.include.__user')
            ->addColumn('type', 'backend.kyc.include.__type')
            ->addColumn('status', 'backend.kyc.include.__status')
            ->addColumn('action', 'backend.kyc.include.__action')
            // Server-side ordering mappings
            ->orderColumn('time', 'users.updated_at $1')
            ->orderColumn('user', 'users.first_name $1')
            ->orderColumn('status', 'users.kyc $1')
            ->rawColumns(['time', 'user', 'type', 'status', 'action'])
            ->make(true);
    }

    return view('backend.kyc.rejected');
}


    /**
     * @return string
     */
    public function depositAction($id)
    {
        $user = User::find($id);
        $kycCredential = json_decode($user->kyc_credential, true);
        unset($kycCredential['kyc_type_of_name']);
        unset($kycCredential['kyc_time_of_time']);

        $kycStatus = $user->kyc;

        return view('backend.kyc.include.__kyc_data', compact('kycCredential', 'id', 'kycStatus'))->render();
    }
    public function depositActionLevel3($id)
    {
        $user = User::find($id);
        $kycCredential = json_decode($user->kyc_level3_credential, true);
        unset($kycCredential['kyc_type_of_name']);
        unset($kycCredential['kyc_time_of_time']);

        $kycStatus = $user->kyc;

        return view('backend.kyc.include.__kyc_data_level3', compact('kycCredential', 'id', 'kycStatus'))->render();
    }
    /**
     * @return RedirectResponse
     */
    public function actionNow(Request $request)
{
    $input = $request->all();
    $user = User::find($input['id']);
    $kycCredential = json_decode($user->kyc_credential, true);
    $kycCredential = array_merge($kycCredential, ['Action Message' => $input['message']]);

    // Determine the status based on which button was clicked
    if ($request->has('approve')) {
        $status = \App\Enums\KYCStatus::Level2->value;
    } elseif ($request->has('reject')) {
        $status = \App\Enums\KYCStatus::Rejected->value;
    } else {
        // Handle cases where neither button was clicked
        notify()->error('Invalid action.');
        return redirect()->back();
    }

    $shortcodes = [
        '[[full_name]]' => $user->full_name,
        '[[email]]' => $user->email,
        '[[site_title]]' => setting('site_title', 'global'),
        '[[site_url]]' => route('home'),
        '[[kyc_type]]' => $kycCredential['kyc_type_of_name'],
        '[[message]]' => $input['message'],
        '[[status]]' => $status,
    ];

    if ($status == \App\Enums\KYCStatus::Level2->value) {
        // Approve KYC
        $user->update([
            'kyc' => \App\Enums\KYCStatus::Level2->value,
            'kyc_credential' => $kycCredential,
        ]);

        // Send approval email
        $this->mailNotify($user->email, 'kyc_approve_level_2', $shortcodes);
        notify()->success('KYC Approved Successfully');

    } elseif ($status == \App\Enums\KYCStatus::Rejected->value) {
        // Reject KYC
        $user->update([
            'kyc' => \App\Enums\KYCStatus::Rejected->value,
            'kyc_credential' => $kycCredential,
        ]);

        // Send rejection email
        $this->mailNotify($user->email, 'kyc_reject_level_2', $shortcodes);
        notify()->success('KYC Rejected Successfully');
        
    }

    // Send push and SMS notifications
    $this->pushNotify('kyc_action', $shortcodes, route('user.kyc'), $user->id);
    $this->smsNotify('kyc_action', $shortcodes, $user->phone);

    return redirect()->route('admin.kyc.all');
}
public function actionLevel3Now(Request $request)
{
    $input = $request->all();

    $user = User::find($input['id']);
    $kycCredential = json_decode($user->kyc_level3_credential, true);
    $kycCredential = array_merge($kycCredential, ['Action Message' => $input['message']]);

    // Determine the action (approve or reject)
    if ($request->has('approve')) {
        $status = \App\Enums\KYCStatus::Level3->value;
        $template = 'kyc_approve_level_3'; // Template for approval
    } elseif ($request->has('reject')) {
        $status = \App\Enums\KYCStatus::RejectLevel3->value;
        $template = 'kyc_reject_level_3'; // Template for rejection
    } else {
        // Handle cases where neither button was clicked
        notify()->error(__('Invalid action.'));
        return redirect()->back();
    }

    // Update user's KYC details
    $user->update([
        'kyc' => $status,
        'kyc_level3_credential' => $kycCredential,
    ]);

    // Prepare shortcodes for notifications
    $shortcodes = [
        '[[full_name]]' => $user->full_name,
        '[[email]]' => $user->email,
        '[[site_title]]' => setting('site_title', 'global'),
        '[[site_url]]' => route('home'),
        '[[kyc_type]]' => $kycCredential['kyc_type_of_name'],
        '[[message]]' => $input['message'],
        '[[status]]' => $status,
    ];

    // Send email to the user
    $this->mailNotify($user->email, $template, $shortcodes);

    // Send SMS to the user
    $this->smsNotify('kyc_action', $shortcodes, $user->phone);

    // Send push notification to the user
    $this->pushNotify('kyc_action', $shortcodes, route('user.kyc'), $user->id);

    notify()->success(__('KYC Update Successfully'));

    return redirect()->route('admin.kyc.all');
}
    /**
     * Update the specified resource in storage.
     *
     * @return RedirectResponse
     */
//    public function update(Request $request, $id)
//    {
//
//
//        $input = $request->all();
//        $validator = Validator::make($input, [
//            'name' => 'required|unique:kycs,name,'.$id,
//            'status' => 'required',
//            'fields' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//
//            notify()->error($validator->errors()->first(), 'Error');
//
//            return redirect()->back();
//        }
//
//        $data = [
//            'name' => $input['name'],
//            'status' => $input['status'],
//            'fields' => json_encode($input['fields']),
//        ];
//
//        $kyc = Kyc::find($id);
//        $kyc->update($data);
//        notify()->success($kyc->name.' '.__(' KYC Updated'));
//
//        return redirect()->route('admin.kyc-form.index');
//    }
    public function updateLevel2Kyc(Request $request, $id)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:kycs,name,'.$id,
            'status' => 'required',
            'fields' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $data = [
            'name' => $input['name'],
            'status' => $input['status'],
            'fields' => json_encode($input['fields']),
        ];

        $kyc = Kyc::find($id);
        $kyc->update($data);
        KycSubLevel::where('name',\App\Enums\KycType::MANUAL)->update(['status'=>1]);
        KycSubLevel::where('name',\App\Enums\KycType::AUTOMATIC)->update(['status'=>0]);
        notify()->success($kyc->name.' '.__(' KYC Updated'));
        return redirect()->back();
    }
    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws Exception
     */
    public function kycAll(Request $request)
{
    if ($request->ajax()) {
        $filters = $request->only(['global_search', 'status', 'created_at']);
      
        // Use helper to get the accessible users based on roles/permissions/attachments
        $data = getAccessibleUserIds($filters)
            ->whereNotNull('kyc_credential');

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('time', 'backend.kyc.include.__time')
            ->addColumn('user', 'backend.kyc.include.__user')
            ->addColumn('type', 'backend.kyc.include.__type')
            ->addColumn('status', 'backend.kyc.include.__status')
            ->addColumn('action', 'backend.kyc.include.__action')
            // Server-side ordering mappings
            ->orderColumn('time', 'users.updated_at $1')
            ->orderColumn('user', 'users.first_name $1')
            ->orderColumn('status', 'users.kyc $1')
            ->rawColumns(['time', 'user', 'type', 'status', 'action'])
            ->make(true);
    }

    return view('backend.kyc.all');
}


    public function export(Request $request, $type)
    {
        switch ($type) {
            case 'level2':
                return Excel::download(new LevelTwoPendingExport($request), 'level2-pending-kyc.xlsx');
            case 'level3':
                return Excel::download(new LevelThreePendingExport($request), 'level3-pending-kyc.xlsx');
            case 'rejected':
               return Excel::download(new RejectedKycExport($request), 'rejected-kyc.xlsx');
            default:
                return Excel::download(new AllKycExport($request), 'all-kyc.xlsx');
        }
    }


    public function getKycMethods(Request $request)
    {
        $kycLevel = $request->input('kyc_level');
        $kycs = Kyc::where('kyc_sub_level_id', $kycLevel)
            ->where('status', true)
            ->get();

        // Return response as JSON
        return response()->json(['kycs' => $kycs]);
    }

    public function kycData($id)
    {
        $fields = Kyc::find($id)->fields;
        return view('backend.user.include.__kyc_data', compact('fields'))->render();
    }

    public function kycSubmit(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $input = $request->all();

        if ($request->kyc_level == 1) {

            $kyc = $request->kyc_level;

            if (empty($kyc)) {
                $kyc = 0;
                $data['email_verified_at'] = null;
            }
            if ($kyc >= KYCStatus::Level1->value) {
                $data['email_verified_at'] = Carbon::now();
            }
            $data['kyc'] = $kyc;
            // Update basic user details
            $user->update($data);

            // Redirect with success message
            notify()->success('User Kyc Updated Successfully', 'success');
            return redirect()->back();
        }

        $validator = Validator::make($input, [
            'kyc_id' => 'required',
            'kyc_credential' => 'required_if:kyc_level,5',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), __('Error'));
            return redirect()->back();
        }

        $kyc = Kyc::find($input['kyc_id']);
        $kycCredential = array_merge($input['kyc_credential'], ['kyc_type_of_name' => $kyc->name, 'kyc_time_of_time' => now()]);
        $checkLevel1 = KycLevel::where('slug', KycLevelSlug::LEVEL1)->where('status', true)->first();

        if ($checkLevel1) {
            if (!isset($user->kyc ) && $user->kyc < KYCStatus::Level1->value) {
                notify()->error(__('kindly complete the level 1 first'));
                return redirect()->back();
            }
        }

        if ($request->kyc_level == 3) {
            //validate the valid type of file or text
            foreach ($input['kyc_credential'] as $key => $value) {
                if ($value instanceof \Illuminate\Http\UploadedFile) {
                    if (!$value->isValid()) {
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
                        notify()->error(__('Failed to upload ') . $key, __('Error'));
                        return redirect()->back();
                    }
                }
            }

            if ($request->is_auto_approve == true) {
                $status = KYCStatus::Level2->value;
                $template = 'kyc_approve_level_2';
            }else {
                $status = KYCStatus::Pending->value;
                $template = 'kyc_request_level_2';
            }

            $user->update([
                'kyc_credential' => json_encode($kycCredential),
                'kyc' => $status,
            ]);
        }
        elseif ($request->kyc_level == 5) {
            $checkLevel2 = KycLevel::where('slug', KycLevelSlug::LEVEL2)->first();
            if ($checkLevel2->status == 1) {
                if ($user->kyc < KYCStatus::Level2->value) {
                    notify()->error(__('kindly complete the level 2 first'));
                    return redirect()->back();
                }
            }
            if ($user->kyc_level3_credential) {
                foreach (json_decode($user->kyc_level3_credential, true) as $key => $value) {
                    self::delete($value);
                }
            }
            foreach ($kycCredential as $key => $value) {
                if (is_file($value)) {
                    $path = self::kycImageUploadTrait($value);
                    if (isset($path) && !empty($path)) {
                        $kycCredential[$key] = $path;
                    } else {
                        notify()->error(__('kindly Set the ') . $key, __('Error'));
                        return redirect()->back();
                    }
                }
            }
            if ($request->is_auto_approve == true) {
                $status = KYCStatus::Level3->value;
                $template = 'kyc_approve_level_3';
            }else {
                $status = KYCStatus::PendingLevel3;
                $template = 'kyc_request_level_3';
            }

            $user->update([
                'kyc_level3_credential' => json_encode($kycCredential),
                'kyc' => $status,
            ]);

        }

        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[email]]' => $user->email,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
            '[[kyc_type]]' => $kyc->name,
            '[[status]]' => 'Pending',
        ];

        $this->mailNotify($user->email, $template, $shortcodes);
        if ($request->kyc_level == 3){
            $adminEmails = parseEmails(setting('site_email', 'global'));
            foreach ($adminEmails as $email) {
                $this->mailNotify($email, 'admin_kyc_request', $shortcodes);
            }
        }
        elseif ($request->kyc_level == 5) {
            $adminEmails = parseEmails(setting('site_email', 'global'));
            foreach ($adminEmails as $email) {
                $this->mailNotify($email, 'admin_kyc_request_level_3', $shortcodes);
            }
        }

        $this->pushNotify('kyc_request', $shortcodes, route('admin.kyc.pending'), $user->id);
        notify()->success(__('User Kyc Updated Successfully'));
        return redirect()->back();
    }
}
