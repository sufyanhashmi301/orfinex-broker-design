<?php

namespace App\Http\Controllers\Backend;

use App\Enums\IBStatus;
use App\Http\Controllers\Controller;
use App\Models\ForexAccount;
use App\Models\IbGroup;
use App\Models\IbQuestion;
use App\Models\IbSchema;
use App\Exports\ApprovedIbExport;
use App\Exports\PendingIbExport;
use App\Exports\RejectedIbExport;
use App\Exports\IbExport;
use App\Models\RebateRule;
use App\Models\User;
use App\Models\UserIbRule;
use App\Services\ForexApiService;
use App\Services\UserIbNetworkService;
use App\Traits\ForexApiTrait;
use App\Traits\NotifyTrait;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class IBController extends Controller
{
    use ForexApiTrait, NotifyTrait;

    protected $forexApiService;
    protected $userIbNetworkService;

    public function __construct(ForexApiService $forexApiService, UserIbNetworkService $userIbNetworkService)
    {
        $this->middleware('permission:ib-list|ib-action|ib-form-manage|advertisement-material-edit', ['only' => ['index', 'update', 'IbPendingList', 'IbApprovedList', 'IbRejectedList', 'IbAllList']]);
        $this->middleware('permission:ib-list', ['only' => ['IbPendingList', 'IbApprovedList', 'IbRejectedList', 'IbAllList']]);
        $this->middleware('permission:ib-form-manage', ['only' => ['saveForm']]);
        $this->middleware('permission:ib-export', ['only' => ['export']]);
        $this->forexApiService = $forexApiService;
        $this->userIbNetworkService = $userIbNetworkService;
    }

    public function index(Request $request)
    {

        $questions = IbQuestion::paginate(10);
        return view('backend.ib.index', compact('questions'));
    }

    public function create()
    {
        return view('backend.ib.create');
    }

    public function edit($id)
    {
        $kyc = IbQuestion::find($id);
        $fields = json_decode($kyc->fields, true);

        return view('backend.ib.edit', compact('kyc', 'fields'));
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
//        dd($input);
        $validator = Validator::make($input, [
            'name' => 'required|unique:ib_questions,name,' . $id,
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

        $kyc = IbQuestion::find($id);
        $kyc->update($data);
        notify()->success($kyc->name . ' ' . __(' IB Updated'));

        return redirect()->route('admin.ib-form.index');
    }

    public function destroy($id)
    {
        IbQuestion::find($id)->delete();
        notify()->success(__('IB Deleted Successfully'));

        return redirect()->route('admin.ib-form.index');
    }


    public function export(Request $request, $type)
    {
        switch ($type) {
            case 'approved':
                return Excel::download(new ApprovedIbExport($request), 'approved-ib.xlsx');
            case 'pending':
                return Excel::download(new PendingIbExport($request), 'pending-ib.xlsx');
            case 'rejected':
                return Excel::download(new RejectedIbExport($request), 'rejected-ib.xlsx');
            default:
                return Excel::download(new IbExport($request), 'ib.xlsx');
        }
    }


  public function IbPendingList(Request $request)
{
    if ($request->ajax()) {
        $filters = $request->only(['global_search', 'phone', 'country', 'status', 'created_at', 'tag', 'date_filter', 'ib_group']);
       
        // Get accessible user IDs using your helper
        $accessibleUserIds = getAccessibleUserIds($filters)->pluck('id');

        // Return empty collection if no accessible users
        if ($accessibleUserIds->isEmpty()) {
            return Datatables::of(collect([]))->make(true);
        }

        // Initialize query
        $data = User::with('ibGroup')
            ->where('ib_status', IBStatus::PENDING)
            ->whereIn('id', $accessibleUserIds);

        // Apply IB Group filter if specified
        if (!empty($request->ib_group)) {
            $data->where('ib_group_id', $request->ib_group);
        }

        // Process date range filters
        $dateRanges = [];

        // 1. Created_at custom range
        if (!empty($request->created_at)) {
            $dates = explode(' to ', $request->created_at);
            if (count($dates) == 2) {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $dateRanges[] = [
                    'start' => $start,
                    'end' => $end,
                    'days' => $start->diffInDays($end)
                ];
            }
        }

        // 2. Predefined range
        if ($request->date_filter) {
            $filter = $request->date_filter;
            $dateRange = match ($filter) {
                '3_days' => [Carbon::now()->subDays(3)->startOfDay(), Carbon::now()->endOfDay()],
                '5_days' => [Carbon::now()->subDays(5)->startOfDay(), Carbon::now()->endOfDay()],
                '15_days' => [Carbon::now()->subDays(15)->startOfDay(), Carbon::now()->endOfDay()],
                '1_month' => [Carbon::now()->subMonth()->startOfDay(), Carbon::now()->endOfDay()],
                '3_months' => [Carbon::now()->subMonths(3)->startOfDay(), Carbon::now()->endOfDay()],
                default => null,
            };

            if ($dateRange) {
                $dateRanges[] = [
                    'start' => $dateRange[0],
                    'end' => $dateRange[1],
                    'days' => $dateRange[0]->diffInDays($dateRange[1])
                ];
            }
        }

        // 3. Apply the shortest date range if multiple exist
        if (count($dateRanges) > 0) {
            $shortestRange = collect($dateRanges)->sortBy('days')->first();
            $data->whereBetween('created_at', [
                $shortestRange['start'],
                $shortestRange['end']
            ]);
        }

        // Apply other filters
        $data->applyFilters($filters);

        // Prepare sortable computed columns
        $data = $data->select('users.*')
            ->selectRaw("CONCAT(COALESCE(users.first_name,''),' ',COALESCE(users.last_name,'')) as username_sort")
            ->selectSub(
                DB::table('ib_groups')
                    ->whereColumn('ib_groups.id', 'users.ib_group_id')
                    ->selectRaw('MIN(name)'),
                'ib_group_name_sort'
            );

        return Datatables::of($data)
            ->addIndexColumn()
            // Server-side ordering mappings
            ->orderColumn('username', 'username_sort $1')
            ->orderColumn('ib_group_name', 'ib_group_name_sort $1')
            ->orderColumn('ib_status', 'users.ib_status $1')
            ->addColumn('username', function ($row) {
                return view('backend.user.include.__user', compact('row'))->render();
            })
            ->addColumn('email', 'backend.user.include.__email')
            ->addColumn('ib_group_name', function ($row) {
                return '<span class="normal-case">' . ($row->ibGroup->name ?? 'N/A') . '</span>';
            })
            ->editColumn('ib_status', 'backend.ib.include.__ib_status')
            ->addColumn('action', function ($user) {
                return view('backend.ib.include.__action', ['user' => $user]);
            })
            ->rawColumns(['username', 'email', 'ib_group_name', 'ib_status', 'action'])
            ->make(true);
    }

    $ibGroups = IbGroup::where('status', 1)->get();
    return view('backend.ib.pending', compact('ibGroups'));
}
   public function IbApprovedList(Request $request)
{
    if ($request->ajax()) {
        $filters = $request->only(['global_search', 'ib_group', 'date_filter', 'status', 'created_at', 'tag']);

        // Get accessible user IDs
        $accessibleUserIds = getAccessibleUserIds($filters)->pluck('id');

        if ($accessibleUserIds->isEmpty()) {
            return Datatables::of(collect([]))->make(true);
        }

        // Initialize query
        $data = User::with('ibGroup')
            ->where('ib_status', IBStatus::APPROVED)
            ->whereIn('id', $accessibleUserIds);

        // Apply IB Group filter if specified
        if (!empty($request->ib_group)) {
            $data->where('ib_group_id', $request->ib_group);
        }

        // Process date range filters
        $dateRanges = [];

        // 1. Created_at custom range
        if (!empty($request->created_at)) {
            $dates = explode(' to ', $request->created_at);
            if (count($dates) == 2) {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $dateRanges[] = [
                    'start' => $start,
                    'end' => $end,
                    'days' => $start->diffInDays($end)
                ];
            }
        }

        // 2. Predefined range
        if ($request->date_filter) {
            $filter = $request->date_filter;
            $dateRange = match ($filter) {
                '3_days' => [Carbon::now()->subDays(3)->startOfDay(), Carbon::now()->endOfDay()],
                '5_days' => [Carbon::now()->subDays(5)->startOfDay(), Carbon::now()->endOfDay()],
                '15_days' => [Carbon::now()->subDays(15)->startOfDay(), Carbon::now()->endOfDay()],
                '1_month' => [Carbon::now()->subMonth()->startOfDay(), Carbon::now()->endOfDay()],
                '3_months' => [Carbon::now()->subMonths(3)->startOfDay(), Carbon::now()->endOfDay()],
                default => null,
            };

            if ($dateRange) {
                $dateRanges[] = [
                    'start' => $dateRange[0],
                    'end' => $dateRange[1],
                    'days' => $dateRange[0]->diffInDays($dateRange[1])
                ];
            }
        }

        // 3. Apply the shortest date range if multiple exist
        if (count($dateRanges) > 0) {
            $shortestRange = collect($dateRanges)->sortBy('days')->first();
            $data->whereBetween('created_at', [
                $shortestRange['start'],
                $shortestRange['end']
            ]);
        }

        // Prepare sortable computed columns
        $data = $data->select('users.*')
            ->selectRaw("CONCAT(COALESCE(users.first_name,''),' ',COALESCE(users.last_name,'')) as username_sort")
            ->selectSub(
                DB::table('ib_groups')
                    ->whereColumn('ib_groups.id', 'users.ib_group_id')
                    ->selectRaw('MIN(name)'),
                'ib_group_name_sort'
            );

        return Datatables::of($data)
            ->addIndexColumn()
            ->orderColumn('username', 'username_sort $1')
            ->orderColumn('ib_group_name', 'ib_group_name_sort $1')
            ->orderColumn('ib_status', 'users.ib_status $1')
            ->addColumn('username', function ($row) {
                return view('backend.user.include.__user', compact('row'))->render();
            })
            ->addColumn('email', 'backend.user.include.__email')
            ->addColumn('ib_group_name', function ($row) {
                return '<span class="normal-case">' . ($row->ibGroup->name ?? 'N/A') . '</span>';
            })
            ->editColumn('ib_status', 'backend.ib.include.__ib_status')
            ->addColumn('action', function ($user) {
                return view('backend.ib.include.__action', ['user' => $user]);
            })
            ->rawColumns(['username', 'email', 'ib_group_name', 'ib_status', 'action'])
            ->make(true);
    }

    $ibGroups = IbGroup::where('status', 1)->get();
    return view('backend.ib.approved', compact('ibGroups'));
}


   public function IbRejectedList(Request $request)
{
    if ($request->ajax()) {
        $filters = $request->only(['global_search', 'ib_group', 'phone', 'country', 'status', 'created_at', 'tag', 'date_filter']);
        
        // Get accessible user IDs using the helper
        $accessibleUserIds = getAccessibleUserIds($filters)->pluck('id');

        // Return empty datatable if no accessible users
        if ($accessibleUserIds->isEmpty()) {
            return Datatables::of(collect([]))->make(true);
        }

        // Initialize query
        $data = User::with('ibGroup')
            ->where('ib_status', IBStatus::REJECTED)
            ->whereIn('id', $accessibleUserIds);

        // Apply IB Group filter if specified
        if (!empty($request->ib_group)) {
            $data->where('ib_group_id', $request->ib_group);
        }

        // Process date range filters
        $dateRanges = [];

        // 1. Created_at custom range
        if (!empty($request->created_at)) {
            $dates = explode(' to ', $request->created_at);
            if (count($dates) == 2) {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $dateRanges[] = [
                    'start' => $start,
                    'end' => $end,
                    'days' => $start->diffInDays($end)
                ];
            }
        }

        // 2. Predefined range
        if ($request->date_filter) {
            $filter = $request->date_filter;
            $dateRange = match ($filter) {
                '3_days' => [Carbon::now()->subDays(3)->startOfDay(), Carbon::now()->endOfDay()],
                '5_days' => [Carbon::now()->subDays(5)->startOfDay(), Carbon::now()->endOfDay()],
                '15_days' => [Carbon::now()->subDays(15)->startOfDay(), Carbon::now()->endOfDay()],
                '1_month' => [Carbon::now()->subMonth()->startOfDay(), Carbon::now()->endOfDay()],
                '3_months' => [Carbon::now()->subMonths(3)->startOfDay(), Carbon::now()->endOfDay()],
                default => null,
            };

            if ($dateRange) {
                $dateRanges[] = [
                    'start' => $dateRange[0],
                    'end' => $dateRange[1],
                    'days' => $dateRange[0]->diffInDays($dateRange[1])
                ];
            }
        }

        // 3. Apply the shortest date range if multiple exist
        if (count($dateRanges) > 0) {
            $shortestRange = collect($dateRanges)->sortBy('days')->first();
            $data->whereBetween('created_at', [
                $shortestRange['start'],
                $shortestRange['end']
            ]);
        }

        // Apply other filters
        $data->applyFilters($filters);

        // Prepare sortable computed columns
        $data = $data->select('users.*')
            ->selectRaw("CONCAT(COALESCE(users.first_name,''),' ',COALESCE(users.last_name,'')) as username_sort")
            ->selectSub(
                DB::table('ib_groups')
                    ->whereColumn('ib_groups.id', 'users.ib_group_id')
                    ->selectRaw('MIN(name)'),
                'ib_group_name_sort'
            );

        return Datatables::of($data)
            ->addIndexColumn()
            ->orderColumn('username', 'username_sort $1')
            ->orderColumn('ib_group_name', 'ib_group_name_sort $1')
            ->orderColumn('ib_status', 'users.ib_status $1')
            ->addColumn('username', function ($row) {
                return view('backend.user.include.__user', compact('row'))->render();
            })
            ->addColumn('ib_group_name', function ($row) {
                return '<span class="normal-case">' . ($row->ibGroup->name ?? 'N/A') . '</span>';
            })
            ->editColumn('ib_status', 'backend.ib.include.__ib_status')
            ->editColumn('email', function ($request) {
                return safe($request->email);
            })
            ->editColumn('username', function ($request) {
                return safe($request->username);
            })
            ->addColumn('action', function ($user) {
                return view('backend.ib.include.__action', ['user' => $user]);
            })
            ->rawColumns(['username', 'ib_group_name', 'ib_status', 'action'])
            ->make(true);
    }

    $ibGroups = IbGroup::where('status', 1)->get();
    return view('backend.ib.rejected', compact('ibGroups'));
}

   public function IbAllList(Request $request)
{
    if ($request->ajax()) {
        $filters = $request->only(['global_search', 'phone', 'country', 'status', 'created_at', 'tag', 'date_filter', 'ib_group']);
        
        // Get accessible user IDs using the helper
        $accessibleUserIds = getAccessibleUserIds($filters)->pluck('id');

        // If no accessible users, return empty dataset
        if ($accessibleUserIds->isEmpty()) {
            return Datatables::of(collect([]))->make(true);
        }

        // Initialize query with accessible users
        $data = User::with('ibGroup')
            ->whereIn('id', $accessibleUserIds);

        // Apply IB Group filter if specified
        if (!empty($request->ib_group)) {
            $data->where('ib_group_id', $request->ib_group);
        }

        // Process date range filters
        $dateRanges = [];

        // 1. Created_at custom range
        if (!empty($request->created_at)) {
            $dates = explode(' to ', $request->created_at);
            if (count($dates) == 2) {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $dateRanges[] = [
                    'start' => $start,
                    'end' => $end,
                    'days' => $start->diffInDays($end)
                ];
            }
        }

        // 2. Predefined range
        if ($request->date_filter) {
            $filter = $request->date_filter;
            $dateRange = match ($filter) {
                '3_days' => [Carbon::now()->subDays(3)->startOfDay(), Carbon::now()->endOfDay()],
                '5_days' => [Carbon::now()->subDays(5)->startOfDay(), Carbon::now()->endOfDay()],
                '15_days' => [Carbon::now()->subDays(15)->startOfDay(), Carbon::now()->endOfDay()],
                '1_month' => [Carbon::now()->subMonth()->startOfDay(), Carbon::now()->endOfDay()],
                '3_months' => [Carbon::now()->subMonths(3)->startOfDay(), Carbon::now()->endOfDay()],
                default => null,
            };

            if ($dateRange) {
                $dateRanges[] = [
                    'start' => $dateRange[0],
                    'end' => $dateRange[1],
                    'days' => $dateRange[0]->diffInDays($dateRange[1])
                ];
            }
        }

        // 3. Apply the shortest date range if multiple exist
        if (count($dateRanges) > 0) {
            $shortestRange = collect($dateRanges)->sortBy('days')->first();
            $data->whereBetween('created_at', [
                $shortestRange['start'],
                $shortestRange['end']
            ]);
        }

        // Apply additional filters
        $data->applyFilters($filters);

        // Prepare sortable computed columns
        $data = $data->select('users.*')
            ->selectRaw("CONCAT(COALESCE(users.first_name,''),' ',COALESCE(users.last_name,'')) as username_sort")
            ->selectSub(
                DB::table('ib_groups')
                    ->whereColumn('ib_groups.id', 'users.ib_group_id')
                    ->selectRaw('MIN(name)'),
                'ib_group_name_sort'
            );

        return Datatables::of($data)
            ->addIndexColumn()
            ->orderColumn('username', 'username_sort $1')
            ->orderColumn('ib_group_name', 'ib_group_name_sort $1')
            ->orderColumn('ib_status', 'users.ib_status $1')
            ->addColumn('username', function ($row) {
                return view('backend.user.include.__user', compact('row'))->render();
            })
            ->addColumn('ib_group_name', function ($row) {
                return '<span class="normal-case">' . ($row->ibGroup->name ?? 'N/A') . '</span>';
            })
            ->editColumn('ib_status', 'backend.ib.include.__ib_status')
            ->addColumn('action', function ($user) {
                return view('backend.ib.include.__action', ['user' => $user]);
            })
            ->rawColumns(['username', 'ib_group_name', 'ib_status', 'action'])
            ->make(true);
    }

    $ibGroups = IbGroup::where('status', 1)->get();
    return view('backend.ib.all', compact('ibGroups'));
}

    public function answerView(User $user)
    {
        try {
            $ibData = $user->ibQuestionAnswers; // Adjust this based on your actual relationship
            
            // Check if IB data exists
            if (!$ibData) {
                return View::make('backend.ib.include.__ib_detail_render', [
                    'user' => $user, 
                    'ibData' => null,
                    'noData' => true
                ]);
            }
            
            // Check if fields is null, string "null", or empty
            if (!$ibData->fields || $ibData->fields === 'null' || $ibData->fields === '[]' || $ibData->fields === '{}') {
                return View::make('backend.ib.include.__ib_detail_render', [
                    'user' => $user, 
                    'ibData' => $ibData,
                    'isEmpty' => true
                ]);
            }
            
            return View::make('backend.ib.include.__ib_detail_render', ['user' => $user, 'ibData' => $ibData]);
        } catch (\Exception $e) {
            Log::error('Error fetching IB answer data: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return View::make('backend.ib.include.__ib_detail_render', [
                'user' => $user, 
                'ibData' => null,
                'error' => 'Error loading IB data: ' . $e->getMessage()
            ]);
        }
    }

    public function approveIbMember(Request $request)
    {
        $input = $request->all();
        $userID = ($request->get('user_id')) ? (int)$request->get('user_id') : null;
        $isReload = ($request->get('reload')) ? $request->get('reload') : false;

        $validator = Validator::make($input, [
            'user_id' => 'required',
            'ib_group_id' => 'required',
        ]);
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $user = User::find($userID);

        if (!blank($user)) {
            $ibGroup = !empty($request->ib_group_id) ? (int)$request->ib_group_id : null;

            // Validation: Check if the provided IB group is different
            if ($user->ib_group_id !== $ibGroup) {
                $this->manageUserRebateRules($user, $ibGroup);
            }

            // Add or Remove Rebate Rules

            // Update user status and IB group
            $user->ib_status = IBStatus::APPROVED;
            $user->ib_group_id = $ibGroup;
            $user->save();

            $this->userIbNetworkService->syncMeta(
                $user,
                'is_part_of_master_ib',
                $user->ib_group_id
            );

            // Notify the user
            $shortcodes = [
                '[[full_name]]' => $user->full_name,
                '[[email]]' => $user->email,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
                '[[status]]' => IBStatus::APPROVED,
            ];
            $this->mailNotify($user->email, 'ib_action', $shortcodes);
            $this->smsNotify('ib_action', $shortcodes, $user->phone);
            $this->pushNotify('ib_action', $shortcodes, route('user.referral'), $user->id);

            $message = __('User has been successfully approved as IB Member');

            if ($request->ajax()) {
                return response()->json(['title' => 'Account Approved for IB', 'success' => $message, 'reload' => $isReload]);
            } else {
                notify()->success($message, 'IB added');
                return redirect()->back();
            }
        }

        return response()->json(['error' => __('User not found or invalid user account id.'), 'reload' => false]);
    }

    protected function manageUserRebateRules($user, $ibGroup)
    {
        // Remove existing rebate rules for the user
        UserIbRule::where('user_id', $user->id)->delete();

        if ($ibGroup) {
            // Fetch all rebate rules associated with the IB Group
            $rebateRules = RebateRule::whereHas('ibGroups', function ($query) use ($ibGroup) {
                $query->where('ib_groups.id', $ibGroup);
            })->get();

            // Assign rebate rules to the user
            foreach ($rebateRules as $rebateRule) {
                UserIbRule::create([
                    'user_id' => $user->id,
                    'ib_group_id' => $ibGroup,
                    'rebate_rule_id' => $rebateRule->id,
//                    'sub_ib_share' => $rebateRule->rebate_amount // Example: Use rebate amount as sub_ib_share; modify as needed
                ]);
            }
        }
    }

    public function disableIbMember(Request $request)
    {
        $userID = ($request->get('user_id')) ? (int)$request->get('user_id') : null;

        $user = User::find($userID);

        if (!blank($user)) {
            // Update user status and IB group
            $ibUserIds = $this->userIbNetworkService->getNetworkUserIds($user);
            User::whereIn('id', $ibUserIds)->update(['ib_status' => IBStatus::DISABLED]);

            // Notify the user
            $shortcodes = [
                '[[full_name]]' => $user->full_name,
                '[[email]]' => $user->email,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
                '[[status]]' => IBStatus::DISABLED,
            ];
            $this->mailNotify($user->email, 'ib_disable_action', $shortcodes);
            $this->smsNotify('ib_disable_action', $shortcodes, $user->phone);
            $this->pushNotify('ib_disable_action', $shortcodes, route('user.referral'), $user->id);

            notify()->success('IB disabled successfully', 'success');
            return redirect()->back();
        }

        return response()->json(['error' => __('User not found or invalid user account id.'), 'reload' => false]);
    }

    public function updateIbMember(Request $request)
    {
        $input = $request->all();
        $ibLogin = $request->ib_login;
        $userID = ($request->get('user_id')) ? (int)$request->get('user_id') : (int)$request->get('user_id');

        $validator = Validator::make($input, [
            'ib_login' => 'required|unique:users,ib_login,' . $userID,
        ]);
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
//       dd($userID);
        $isReload = ($request->get('reload')) ? $request->get('reload') : false;

        $user = User::find($userID);
        if (!blank($user)) {
//            if ($user->status == UserStatus::INACTIVE) {
//                throw ValidationException::withMessages(['invalid' => __('User account may not verified or inactive.')]);
//            }
            if ($user->ib_login == $request->ib_login) {
                $message = __('Already assigns same IB number :ib', ['ib' => $request->ib_login]);
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');
                    return redirect()->back();
                }
            }
            $response = $this->forexApiService->getUserByLogin([
                'login' => $request->ib_login
            ]);
            if (!$response['success']) {
                $message = __(':ib not exist in MT5. Kindly enter the correct IB account ', ['ib' => $request->ib_login]);
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');
                    return redirect()->back();
                }
            }

//            $responseLogin = 1223
            $user->ib_login = $request->ib_login;
            $user->ib_status = IBStatus::APPROVED;
            $user->save();

            add_child_agent($user);
//                event(new NewIBEvent($user));
            $shortcodes = [
                '[[full_name]]' => $user->full_name,
                '[[email]]' => $user->email,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
                '[[status]]' => IBStatus::APPROVED,
            ];
            $this->mailNotify($user->email, 'ib_action', $shortcodes);
            $this->smsNotify('ib_action', $shortcodes, $user->phone);
            $this->pushNotify('ib_action', $shortcodes, route('user.referral'), $user->id);
            $message = __('User has been successfully updated IB account');
            if ($request->ajax()) {
                return response()->json(['title' => 'Account Updated for IB', 'success' => $message, 'reload' => $isReload]);
            } else {
                notify()->success($message, 'IB Updated successfully');
                return redirect()->back();
            }
        } else {
            $message = __('some error occurred.please try again');
            if ($request->ajax()) {
                return response()->json(['error' => $message, 'reload' => false]);
            } else {
                notify()->error($message, 'Error Log');

                return redirect()->back();
            }
        }
    }

    public function approveMIbMember(Request $request)
    {
//        dd($request->all());
        $userID = ($request->get('user_id')) ? (int)$request->get('user_id') : (int)$request->get('user_id');
//       dd($userID);
        $isReload = ($request->get('reload')) ? $request->get('reload') : false;

        $user = User::find($userID);
        if (!blank($user)) {
//            if ($user->status == UserStatus::INACTIVE) {
//                throw ValidationException::withMessages(['invalid' => __('User account may not verified or inactive.')]);
//            }
            if (isset($user->multi_ib_login)) {
                $message = __('User has already a member of MIB Program');
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');
                    return redirect()->back();
                }
            }

            $ibSchema = IbSchema::where('type', 'multi_ib')->where('status', true)->first();
            if (!$ibSchema) {
                return false;
            }
            $group = $ibSchema->group;
            $responseLogin = $this->createForexAccount($user, $group);

            if ($responseLogin) {
                $user->multi_ib_login = $responseLogin;
                $user->save();

//                event(new NewIBEvent($user));
                $shortcodes = [
                    '[[full_name]]' => $user->full_name,
                    '[[email]]' => $user->email,
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                    '[[status]]' => IBStatus::APPROVED,
                ];
                $this->mailNotify($user->email, 'mib_action', $shortcodes);
//                $this->smsNotify('mib_action', $shortcodes, $user->phone);
                $this->pushNotify('mib_action', $shortcodes, route('user.referral'), $user->id);
                $message = __('User has been successfully approved as MIB Member');
                if ($request->ajax()) {
                    return response()->json(['title' => 'Account Approved for MIB', 'success' => $message, 'reload' => $isReload]);
                } else {
                    notify()->success($message, 'MIB added');
                    return redirect()->back();
                }
            } else {
                $message = __('some error occurred.please try again');
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');

                    return redirect()->back();
                }
            }
        }
        return response()->json(['error' => __('User not found or invalid user account id.'), 'reload' => false]);

    }


    public function updateMIbMember(Request $request)
    {
        $input = $request->all();
        $ibLogin = $request->ib_login;
        $userID = ($request->get('user_id')) ? (int)$request->get('user_id') : (int)$request->get('user_id');

        $validator = Validator::make($input, [
            'multi_ib_login' => 'required|unique:users,multi_ib_login,' . $userID,
        ]);
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
//       dd($userID);
        $isReload = ($request->get('reload')) ? $request->get('reload') : false;

        $user = User::find($userID);
        if (!blank($user)) {
            if ($user->multi_ib_login == $request->multi_ib_login) {
                $message = __('Already assigns same MIB number :ib', ['ib' => $request->multi_ib_login]);
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');
                    return redirect()->back();
                }
            }
            $response = $this->forexApiService->getUserByLogin([
                'login' => $request->multi_ib_login
            ]);
            if (!$response['success']) {
                $response = $this->getUserInfoApi($request->multi_ib_login);
                if (!$response) {
                    $message = __(':ib not exist in MT5. Kindly enter the correct MIB account ', ['ib' => $request->multi_ib_login]);
                    if ($request->ajax()) {
                        return response()->json(['error' => $message, 'reload' => false]);
                    } else {
                        notify()->error($message, 'Error Log');
                        return redirect()->back();
                    }
                }

//            $responseLogin = 1223
                $user->ib_login = $request->multi_ib_login;
                $user->save();

                $shortcodes = [
                    '[[full_name]]' => $user->full_name,
                    '[[email]]' => $user->email,
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                    '[[status]]' => IBStatus::APPROVED,
                ];
                $this->mailNotify($user->email, 'mib_action', $shortcodes);
                $this->pushNotify('mib_action', $shortcodes, route('user.referral'), $user->id);
                $message = __('User has been successfully updated MIB account');
                if ($request->ajax()) {
                    return response()->json(['title' => 'Account Updated for MIB', 'success' => $message, 'reload' => $isReload]);
                } else {
                    notify()->success($message, 'MIB Updated successfully');
                    return redirect()->back();
                }
            } else {
                $message = __('some error occurred.please try again');
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');

                    return redirect()->back();
                }
            }
        }
    }

    public function createForexAccount($user, $group)
    {
        $phone = $user->phone;
        if (!$phone) {
            $phone = 12345678;
        }
        $country = $user->country;
        if (!$country) {
            $country = 'UAE';
        }
        $password = 'SNNH@2024@bol';
        $investPassword = 'SNNH@2024@bol';
        $data = [
            "login" => 0,
            "group" => $group,
            "firstName" => $user->first_name,
            "middleName" => "",
            "lastName" => $user->last_name,
            "leverage" => 1,
            "rights" => "USER_RIGHT_ALL",
            "country" => $country,
            "city" => $user->city,
            "state" => "",
            "zipCode" => $user->zip_code,
            "address" => $user->address,
            "phone" => $phone,
            "email" => $user->email,
            "agent" => 0,
            "account" => "",
            "company" => env('APP_NAME', 'Company'),
            "language" => 0,
            "phonePassword" => 'SNNH@2024@bol',
            "status" => "RE",
            "masterPassword" => $password,
            "investorPassword" => $investPassword
        ];
//        dd($data);
        $response = $this->forexApiService->createUser($data);
//        dd($response);
        if ($response['success']) {
            $resResult = $response['result'];
            $mt5Login = $resResult['login'];
            if ($mt5Login && $resResult['responseCode'] == 0) {
                $rightData = [
                    "login" => $mt5Login,
                    "rights" => 'USER_RIGHT_ENABLED',

                ];
                $this->forexApiService->setUserRights($rightData);

                return $mt5Login;

            }
            return false;
        }
        return false;
    }

    public function rejectIbMember(Request $request)
    {
        $userID = ($request->get('user_id')) ? (int)$request->get('user_id') : (int)$request->get('user_id');
        $isReload = ($request->get('reload')) ? $request->get('reload') : false;

        $user = User::find($userID);
        if (!blank($user)) {
            try {
                DB::beginTransaction();

                // Update user status
                $user->ib_status = IBStatus::REJECTED;
                $user->ib_group_id = null;
                $user->save();

                // Update is_part_of_master_ib based on nearest approved parent IB
                $updatedCount = $this->userIbNetworkService->updateMasterIbBasedOnParent($user);

                // Remove rebate rules
                $ibGroup = null;
                $this->manageUserRebateRules($user, $ibGroup);

                DB::commit();

                // Send notifications
                $shortcodes = [
                    '[[full_name]]' => $user->full_name,
                    '[[email]]' => $user->email,
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                    '[[status]]' => IBStatus::REJECTED,
                ];
                $this->mailNotify($user->email, 'ib_reject_action', $shortcodes);
                $this->smsNotify('ib_reject_action', $shortcodes, $user->phone);
                $this->pushNotify('ib_reject_action', $shortcodes, route('user.referral'), $user->id);

                return response()->json([
                    'title' => 'Account rejected for IB', 
                    'success' => __('User has been successfully rejected as IB Member.'), 
                    'reload' => $isReload
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error rejecting IB member: ' . $e->getMessage());
                
                return response()->json([
                    'title' => 'Error', 
                    'error' => __('Error rejecting IB member: ') . $e->getMessage(), 
                    'reload' => false
                ], 500);
            }
        }

        return response()->json([
            'title' => 'Error', 
            'error' => __('User not found.'), 
            'reload' => false
        ], 404);
    }

    public function saveForm(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:ib_questions,name',
            'status' => 'required',
            'fields' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $data = [
            'name' => $input['name'],
            'status' => $input['status'],
            'fields' => json_encode($input['fields']),
        ];

        $kyc = IbQuestion::create($data);
        notify()->success($kyc->name . ' ' . __(' IB Created'));

        return redirect()->route('admin.ib-form.index');
    }
}
