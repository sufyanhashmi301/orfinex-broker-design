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

class KycController extends Controller
{
    use NotifyTrait;

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

        if ($request->ajax()) {
            $filters = $request->only(['global_search', 'status',  'created_at']);
            $data = User::where('kyc', KYCStatus::Pending->value)
            ->latest('updated_at');
            $data->applyFilters($filters);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('time', 'backend.kyc.include.__time')
                ->addColumn('user', 'backend.kyc.include.__user')
                ->addColumn('type', 'backend.kyc.include.__type')
                ->addColumn('status', 'backend.kyc.include.__status')
                ->addColumn('action', 'backend.kyc.include.__action')
                ->rawColumns(['time', 'user', 'type', 'status', 'action'])
                ->make(true);
        }

        return view('backend.kyc.pending');
    }
    public function KycLevel3Pending (Request $request)
    {

        if ($request->ajax()) {
            $filters = $request->only(['global_search', 'status',  'created_at']);
            $data = User::where('kyc_level3_credential','!=',NULL)
            ->where('kyc', KYCStatus::PendingLevel3->value)
            ->latest('updated_at');
            $data->applyFilters($filters);
            //->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('time', function($row) {
                    return $row->kyc_time_level3;
                })
                ->addColumn('user', 'backend.kyc.include.__user')
                ->addColumn('type', function($row) {
                    return $row->kyc_type_level3;
                })
                ->addColumn('status', 'backend.kyc.include.__statuslevel3')
                ->addColumn('action', 'backend.kyc.include.__action')
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

        if ($request->ajax()) {
            $filters = $request->only(['global_search', 'status',  'created_at']);
            $data = User::where('kyc', KYCStatus::Rejected->value)->latest();
            // $data->applyFilters($filters);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('time', 'backend.kyc.include.__time')
                ->addColumn('user', 'backend.kyc.include.__user')
                ->addColumn('type', 'backend.kyc.include.__type')
                ->addColumn('status', 'backend.kyc.include.__status')
                ->addColumn('action', 'backend.kyc.include.__action')
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

//        dd($request->all());
        $input = $request->all();
        $user = User::find($input['id']);
//        $kycLevel3Status = KycLevel::where('slug',KycLevelSlug::LEVEL3)->first();
        $kycCredential = json_decode($user->kyc_credential, true);
        $kycCredential = array_merge($kycCredential, ['Action Message' => $input['message']]);
            $user->update([
                'kyc' => $input['status'],
               'kyc_credential' => $kycCredential,
           ]);

        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[email]]' => $user->email,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
            '[[kyc_type]]' => $kycCredential['kyc_type_of_name'],
            '[[message]]' => $input['message'],
            '[[status]]' => $input['status'],
        ];
        $this->mailNotify($user->email, 'kyc_action', $shortcodes);
        $this->smsNotify('kyc_action', $shortcodes, $user->phone);
        $this->pushNotify('kyc_action', $shortcodes, route('user.kyc'), $user->id);

        notify()->success(__('KYC Update Successfully'));

        return redirect()->route('admin.kyc.all');
    }
    public function actionLevel3Now(Request $request)
    {
        $input = $request->all();
        $user = User::find($input['id']);
        $kycCredential = json_decode($user->kyc_level3_credential, true);
        $kycCredential = array_merge($kycCredential, ['Action Message' => $input['message']]);
        $user->update([
            'kyc' => $input['status'],
            'kyc_level3_credential' => $kycCredential,
        ]);
        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[email]]' => $user->email,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
            '[[kyc_type]]' => $kycCredential['kyc_type_of_name'],
            '[[message]]' => $input['message'],
            '[[status]]' => $input['status'],
        ];
        $this->mailNotify($user->email, 'kyc_action', $shortcodes);
        $this->smsNotify('kyc_action', $shortcodes, $user->phone);
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
            $filters = $request->only(['global_search', 'status',  'created_at']);
            $data = User::whereNotNull('kyc_credential')->latest();
            $data->applyFilters($filters);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('time', 'backend.kyc.include.__time')
                ->addColumn('user', 'backend.kyc.include.__user')
                ->addColumn('type', 'backend.kyc.include.__type')
                ->addColumn('status', 'backend.kyc.include.__status')
                ->addColumn('action', 'backend.kyc.include.__action')
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
}
