<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ForexAccountStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Enums\KYCStatus;
use App\Exports\ActiveUsersExport;
use App\Exports\ibTransactionsUsersExport;
use App\Exports\DisabledUsersExport;
use App\Exports\RefferalUsersExport;
use App\Exports\TransactionsUsersExport;
use App\Exports\UsersExport;
use App\Exports\withBalanceUsersExport;
use App\Exports\withOutBalanceUsersExport;
use App\Http\Controllers\Controller;
use App\Jobs\AgentReferralJob;
use App\Models\Account;
use App\Models\Bonus;
use App\Models\Branch;
use App\Models\CustomerGroup;
use App\Models\ForexAccount;
use App\Models\ForexSchema;
use App\Models\IbGroup;
use App\Models\LevelReferral;
use App\Models\RiskProfileTag;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Admin;
use App\Models\Ledger;
use App\Models\KycLevel;
use App\Models\Lead;
use App\Scopes\ExcludeGracePeriodScope;
use App\Services\ForexApiService;
use App\Services\WalletService;
use App\Traits\ForexApiTrait;
use App\Traits\NotifyTrait;
use Brick\Math\BigDecimal;
use App\Models\Kyc;
use Carbon\Carbon;
use DataTables;
use Exception;
use Hash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Txn;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Models\Ranking;
use App\Rules\Recaptcha;
use Illuminate\Support\Facades\Artisan;
class UserController extends Controller
{
    use NotifyTrait, ForexApiTrait;
    protected $forexApiService;
    protected $walletService;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function __construct(ForexApiService $forexApiService,WalletService $walletService)
    {
        $this->middleware('permission:customer-list', ['only' => ['index', 'activeUser', 'disabled', 'withOutBalance', 'withBalance']]);
        // $this->middleware('permission:customer-basic-manage|customer-change-password|all-type-status|customer-balance-add-or-subtract|kyc-status-update|ib-partner-list|approve-ib-member', ['only' => ['edit']]);
        $this->middleware('permission:customer-login', ['only' => ['userLogin']]);
        $this->middleware('permission:customer-mail-send', ['only' => ['mailSendAll', 'mailSend']]);
        $this->middleware('permission:customer-overview-update', ['only' => ['update']]);
        $this->middleware('permission:customer-change-password', ['only' => ['passwordUpdate']]);
        $this->middleware('permission:customer-profile-toggles', ['only' => ['statusUpdate']]);
        $this->middleware('permission:customer-funds', ['only' => ['balanceUpdate']]);
        $this->middleware('permission:customer-edit|customer-overview-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:customer-create', ['only' => ['store', 'createCustomer']]);
        $this->middleware('permission:customer-master-ib-network-distribution', ['only' => ['runMasterIbDistribution']]);
        $this->middleware('permission:customer-child-ib-distribution', ['only' => ['runChildIbDistribution']]);
        $this->forexApiService = $forexApiService;
        $this->walletService = $walletService;

    }

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        $loggedInUser = auth()->user();
    $filters = $request->only(['global_search','search', 'phone', 'staff_name', 'country', 'status', 'created_at', 'tag']);
        if (!empty($filters['global_search']) ){
        if (preg_match('/^[\d\+\-\(\) ]+$/', $filters['global_search'])) {
            $filters['phone'] = $filters['global_search'];
            $filters['global_search'] = '';
        }
    }
$staffMembers = Admin::whereDoesntHave('roles', function($query) {
    $query->where('name', 'Super-Admin');
})->get();
    if ($request->ajax()) {
       $data = getAccessibleUserIds($filters);

     if (!empty($filters['staff_name'])) {
         $data = applyStaffNameFilter($data, $filters['staff_name']);
     }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    return view('backend.user.include.__user', compact('row'))->render();
            })
            ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->editColumn('balance', 'backend.user.include.__total_balance_mt5')
                ->editColumn('equity', 'backend.user.include.__total_equity_mt5')
                ->editColumn('credit', 'backend.user.include.__total_credit_mt5')
            ->addColumn('branch_name', function ($row) {
                return view('backend.user.include.__branch', compact('row'))->render();
            })
                ->addColumn('staff_name', function ($row) {
                    return view('backend.user.include.__staff')->with('staff', $row->staff);
                })
                ->addColumn('action', 'backend.user.include.__action')
            ->rawColumns(['username', 'kyc', 'balance', 'equity', 'credit', 'branch_name', 'staff_name', 'status', 'action'])
                ->make(true);
        }
        
        return view('backend.user.all', compact('staffMembers'));
    }

    public function export(Request $request, $type = null)
{
    switch ($type) {
        case 'active':
            return Excel::download(new ActiveUsersExport($request), 'active-users.xlsx');
        case 'disabled':
            return Excel::download(new DisabledUsersExport($request), 'disabled-users.xlsx');
        case 'withbalance':
            return Excel::download(new withBalanceUsersExport($request), 'withbalance-users.xlsx');
        case 'withoutbalance':
            return Excel::download(new withOutBalanceUsersExport($request), 'withoutbalance-users.xlsx');
        case 'refferal':
            // Only for referral exports we need user_id
            $userId = $request->user_id;
            if (!$userId) {
                return back()->with('error', 'User ID is required for referral export');
            }
            $user = User::find($userId);
            if (!$user) {
                return back()->with('error', 'User not found');
            }
            $fileName = strtolower(str_replace(' ', '-', $user->username)) . '-referrals.xlsx';
            return Excel::download(new RefferalUsersExport($userId), $fileName);
        case 'transaction':
            // Only for transaction exports we need user_id
            $userId = $request->user_id;
            if (!$userId) {
                return back()->with('error', 'User ID is required for transaction export');
            }
            $user = User::find($userId);
            if (!$user) {
                return back()->with('error', 'User not found');
            }
            $fileName = strtolower(str_replace(' ', '-', $user->username)) . '-transactions.xlsx';
            return Excel::download(new TransactionsUsersExport($userId), $fileName);
        case 'ibtransaction':
                // Only for transaction exports we need user_id
                $userId = $request->user_id;
                if (!$userId) {
                    return back()->with('error', 'User ID is required for transaction export');
                }
                $user = User::find($userId);
                if (!$user) {
                    return back()->with('error', 'User not found');
                }
                
            // Get filter parameters
            $filters = [
                'login' => $request->login,
                'deal' => $request->deal,
                'symbol' => $request->symbol,
                'date_filter' => $request->date_filter,
                'created_at' => $request->created_at,
            ];
                
                $fileName = strtolower(str_replace(' ', '-', $user->username)) . '-transactions-ibbonus.xlsx';
                return Excel::download(new ibTransactionsUsersExport($userId, $filters), $fileName);
        default:
            return Excel::download(new UsersExport($request), 'users.xlsx');
    }
}

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws Exception
     */
    public function activeUser(Request $request)
    {
        $loggedInUser = auth()->user();
        $filters = $request->only(['global_search', 'staff_name','phone', 'country', 'status', 'created_at', 'tag']);
        if (!empty($filters['global_search']) ){
        if (preg_match('/^[\d\+\-\(\) ]+$/', $filters['global_search'])) {
            $filters['phone'] = $filters['global_search'];
            $filters['global_search'] = '';
        }
    }
         $filters['status'] = 1;
        if ($request->ajax()) {

            $data = getAccessibleUserIds($filters);
if (!empty($filters['staff_name'])) {
            $data = applyStaffNameFilter($data, $filters['staff_name']);
        }
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    return view('backend.user.include.__user', compact('row'))->render();
                })                ->editColumn('balance', 'backend.user.include.__total_balance_mt5')
                ->editColumn('equity', 'backend.user.include.__total_equity_mt5')
                ->editColumn('credit', 'backend.user.include.__total_credit_mt5')
                ->addColumn('branch_name', function ($row) {
                    return view('backend.user.include.__branch', compact('row'))->render();
                })
                ->addColumn('staff_name', function ($row) {
                    return view('backend.user.include.__staff')->with('staff', $row->staff);
                })
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['username', 'kyc', 'balance', 'equity', 'credit', 'branch_name', 'staff_name', 'status', 'action'])
                ->make(true);
        }
        $staffMembers = Admin::whereDoesntHave('roles', function($query) {
            $query->where('name', 'Super-Admin');
        })->get();
        return view('backend.user.active_user', compact('staffMembers'));
    }

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws Exception
     */
    public function disabled(Request $request)
    {
        $loggedInUser = auth()->user();

        if ($request->ajax()) {
            $filters = $request->only(['global_search', 'phone', 'staff_name', 'country', 'status', 'created_at', 'tag']);
              if (!empty($filters['global_search']) ){
        if (preg_match('/^[\d\+\-\(\) ]+$/', $filters['global_search'])) {
            $filters['phone'] = $filters['global_search'];
            $filters['global_search'] = '';
        }
    }
            $filters['status'] = 0;
         $data = getAccessibleUserIds($filters);
 if (!empty($filters['staff_name'])) {
              $data = applyStaffNameFilter($data, $filters['staff_name']);
          }
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    return view('backend.user.include.__user', compact('row'))->render();
                })                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->editColumn('balance', 'backend.user.include.__total_balance_mt5')
                ->editColumn('equity', 'backend.user.include.__total_equity_mt5')
                ->editColumn('credit', 'backend.user.include.__total_credit_mt5')
                ->addColumn('branch_name', function ($row) {
                    return view('backend.user.include.__branch', compact('row'))->render();
                })
                ->addColumn('staff_name', function ($row) {
                    return view('backend.user.include.__staff')->with('staff', $row->staff);
                })
                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['username', 'kyc', 'balance', 'equity', 'credit', 'branch_name', 'staff_name', 'status', 'action'])
                ->make(true);
        }

        // Get staff members for the filter dropdown
        $staffMembers = Admin::whereDoesntHave('roles', function($query) {
            $query->where('name', 'Super-Admin');
        })->get();
        
        return view('backend.user.disabled_user', compact('staffMembers'));
    }

    public function withBalance(Request $request)
    {
        $loggedInUser = auth()->user();
    $filters = $request->only(['global_search', 'staff_name', 'phone', 'country', 'status', 'created_at', 'tag']);
    
    $staffMembers = Admin::whereDoesntHave('roles', function($query) {
        $query->where('name', 'Super-Admin');
    })->get();
    
        $riskProfileTags = RiskProfileTag::all();

        if ($request->ajax()) {
        // Handle phone number detection in global search

            $realForexAccounts = ForexAccount::where('status', ForexAccountStatus::Ongoing)->pluck('login');

            $forexAccountIds = DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->whereIn('Login', $realForexAccounts)
                ->where('Balance', '>', 0)
                ->pluck('Login');

        $userIdsWithBalance = ForexAccount::whereIn('login', $forexAccountIds)->pluck('user_id');

        $accessibleUsersQuery = getAccessibleUserIds($filters);

        // Apply filters
        $data = $accessibleUsersQuery->whereIn('id', $userIdsWithBalance);

          if (!empty($filters['global_search'])) {
            $searchTerm = $filters['global_search'];
            $data->where(function($query) use ($searchTerm) {
                $query->Where('username', 'like', "%$searchTerm%")
                      ->orWhere('email', 'like', "%$searchTerm%")
                      ->orWhere('phone', 'like', "%$searchTerm%");
            });
        }

        // Handle staff filter
        if (!empty($filters['staff_name'])) {
            $data = applyStaffNameFilter($data, $filters['staff_name']);
            }

        return Datatables::of($data->latest())
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    return view('backend.user.include.__user', compact('row'))->render();
            })
            ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->editColumn('balance', 'backend.user.include.__total_balance_mt5')
                ->editColumn('equity', 'backend.user.include.__total_equity_mt5')
                ->editColumn('credit', 'backend.user.include.__total_credit_mt5')
            ->addColumn('branch_name', function ($row) {
                return view('backend.user.include.__branch', compact('row'))->render();
            })
                ->addColumn('staff_name', function ($row) {
                    return view('backend.user.include.__staff')->with('staff', $row->staff);
                })
                ->addColumn('action', 'backend.user.include.__action')
            ->rawColumns(['username', 'kyc', 'status', 'balance', 'equity', 'branch_name', 'staff_name', 'credit', 'action'])
                ->make(true);
        }
        
        return view('backend.user.with_balance', [
        'riskProfileTags' => $riskProfileTags
    ], compact('staffMembers'));
    }


    public function withOutBalance(Request $request)
    {
        $loggedInUser = auth()->user();
            $filters = $request->only(['global_search', 'staff_name', 'phone', 'country', 'status', 'created_at', 'tag']);
    
    $staffMembers = Admin::whereDoesntHave('roles', function($query) {
        $query->where('name', 'Super-Admin');
    })->get();
        $riskProfileTags = RiskProfileTag::all();
        if ($request->ajax()) {
            $realForexAccounts = ForexAccount::where('status', ForexAccountStatus::Ongoing)->pluck('login');

            $forexAccountIds = DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->whereIn('Login', $realForexAccounts)
                ->where('Balance', '<=', 0)
                ->pluck('Login');
            
        $userIdsWithoutBalance = ForexAccount::whereIn('login', $forexAccountIds)->pluck('user_id');

        // ✅ Apply the helper here:
        $accessibleUsersQuery = getAccessibleUserIds();

        // ✅ Filter by users without balance
        $data = $accessibleUsersQuery->whereIn('id', $userIdsWithoutBalance)->latest();
         if (!empty($filters['global_search'])) {
            $searchTerm = $filters['global_search'];
            $data->where(function($query) use ($searchTerm) {
                $query->Where('username', 'like', "%$searchTerm%")
                      ->orWhere('email', 'like', "%$searchTerm%")
                      ->orWhere('phone', 'like', "%$searchTerm%");
            });
        }

// Handle staff filter
        if (!empty($filters['staff_name'])) {
            $data = applyStaffNameFilter($data, $filters['staff_name']);
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    return view('backend.user.include.__user', compact('row'))->render();
                })                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->editColumn('balance', 'backend.user.include.__total_balance_mt5')
                ->editColumn('equity', 'backend.user.include.__total_equity_mt5')
                ->editColumn('credit', 'backend.user.include.__total_credit_mt5')
                ->addColumn('branch_name', function ($row) {
                    return view('backend.user.include.__branch', compact('row'))->render();
                })
                ->addColumn('staff_name', function ($row) {
                    return view('backend.user.include.__staff')->with('staff', $row->staff);
                })
                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['username', 'kyc', 'status', 'balance', 'equity', 'branch_name', 'staff_name', 'credit', 'action'])
                ->make(true);
        }
        
        return view('backend.user.without_balance', [
            'riskProfileTags' => $riskProfileTags
        ], compact('staffMembers'));
    }

    public function gracePeriodUsers(Request $request)
    {
        $loggedInUser = auth()->user();
        $filters = $request->only(['global_search', 'staff_name', 'phone', 'country', 'status', 'created_at', 'tag']);
$staffMembers = Admin::whereDoesntHave('roles', function($query) {
    $query->where('name', 'Super-Admin');
})->get();
//        dd($request->all());
        if ($request->ajax()) {

            // ✅ Use helper to get accessible user query
        $accessibleUsersQuery = getAccessibleUserIds();

        // ✅ Remove global scope and filter grace period
        $data = $accessibleUsersQuery
            ->withoutGlobalScope(ExcludeGracePeriodScope::class)
            ->where('in_grace_period', true)
                        ->latest();
            if (!empty($filters['global_search'])) {
            $searchTerm = $filters['global_search'];
            $data->where(function($query) use ($searchTerm) {
                $query->Where('username', 'like', "%$searchTerm%")
                      ->orWhere('email', 'like', "%$searchTerm%")
                      ->orWhere('phone', 'like', "%$searchTerm%");
            });
        }
if (!empty($filters['staff_name'])) {
            $data = applyStaffNameFilter($data, $filters['staff_name']);
        }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    return view('backend.user.include.__user', compact('row'))->render();
                })
                ->editColumn('balance', 'backend.user.include.__total_balance_mt5')
                ->editColumn('equity', 'backend.user.include.__total_equity_mt5')
                ->editColumn('credit', 'backend.user.include.__total_credit_mt5')
                ->addColumn('branch_name', function ($row) {
                    return view('backend.user.include.__branch', compact('row'))->render();
                })
                ->addColumn('staff_name', function ($row) {
                    return view('backend.user.include.__staff')->with('staff', $row->staff);
                })
                ->editColumn('kyc', 'backend.user.include.__kyc')
//                ->editColumn('status', 'backend.user.include.__status')
               ->addColumn('action', 'backend.user.include.__grace_action')
                ->rawColumns(['username', 'kyc', 'balance', 'equity', 'credit', 'branch_name', 'staff_name', 'status', 'action'])
                ->make(true);
        }
        
        return view('backend.user.grace_users', compact('staffMembers'));
    }
    public function updateGracePeriod(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'status' => 'required|in:0,1',
    ]);

    $user = User::withoutGlobalScope(ExcludeGracePeriodScope::class)->findOrFail($request->user_id);
    $user->in_grace_period = $request->status;
    $user->save();

    notify()->success('Grace period status updated successfully.');

    return redirect()->back();
}



    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $loggedInUser = auth()->user();

        // Check if the logged-in user is a Super-Admin
        if (!$loggedInUser->hasRole('Super-Admin') && !$loggedInUser->can('show-all-users-by-default-to-staff')) {
            // Validate if the `id` exists in attached users
            $attachedUserIds = $loggedInUser->users->pluck('id');
            if ($attachedUserIds->isNotEmpty()) {
                if (!$attachedUserIds->contains($id)) {
                    // Redirect back with an error message if the user is not attached
                    return redirect()->back()->with('error', 'Unauthorized access to user details.');
                }
            }
        }

        $user = User::find($id);

        $referrals = $this->getAllReferrals($user);

        // If user not found, redirect back with an error message
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $level = LevelReferral::where('type', 'investment')->max('the_order') + 1;
        $realForexAccounts = ForexAccount::realActiveAccount($id)
            ->orderBy('balance', 'desc')
            ->get();
        $tags = RiskProfileTag::where('status', true)->get();
        $countries = getCountries();
        $customerGroups = CustomerGroup::where('status', 1)->get();
        $riskProfileTags = RiskProfileTag::all();
        $kycLevels = KycLevel::where('status', 1)->get();
        $ibGroups = IbGroup::where('status', 1)->get();
        $kycStatus = KYCStatus::cases();
        $users = User::where('id', '<>', $id)
            ->where(function ($query) use ($id, $user) {
                $query->whereNull('ref_id')
                    ->orWhere('ref_id', '<>', $id);
            })
            ->where('id', '<>', $user->ref_id)
            ->get();

        $isPartOfMasterIb = user_meta('is_part_of_master_ib', null, $user);

        $tagNames = $user->riskProfileTags()->pluck('name')->toArray();

        $globalSchemas = ForexSchema::active()
            ->traderType()
            ->where('is_global', 1)
            ->get();

        $userSchemas = ForexSchema::active()
            ->traderType()
            ->relevantForUser($user->country, $tagNames)
            ->get();

        $schemas = collect();

        if ($isPartOfMasterIb) {
            $ibGroup = IbGroup::with('rebateRules.forexSchemas')->find($isPartOfMasterIb);

            foreach ($ibGroup->rebateRules as $rule) {
                $schemas = $schemas->merge($rule->forexSchemas->where('status', true));
            }

        }

        $schemas = $schemas->merge($userSchemas)->merge($globalSchemas)
            ->unique('id')
            ->sortBy('priority')
            ->values();

        $bonuses = Bonus::where('status', '1')->where('last_date', '>=', today())->get();
        $branches = Branch::where('status', 1)->get();

        return view('backend.user.edit', compact(
            'users',
            'user',
            'level',
            'realForexAccounts',
            'tags',
            'customerGroups',
            'schemas',
            'riskProfileTags',
            'countries',
            'kycLevels',
            'kycStatus',
            'bonuses',
            'ibGroups',
            'referrals',
            'branches'
        ));
    }

    public function destroy(Request $request, $id)
    {
        try {
            // Validate the admin key input
            $request->validate([
                'admin_key' => 'required|string|min:1|max:255'
            ], [
                'admin_key.required' => 'Super-Admin key is required for user deletion.',
                'admin_key.string' => 'Super-Admin key must be a valid string.',
                'admin_key.min' => 'Super-Admin key cannot be empty.',
                'admin_key.max' => 'Super-Admin key is too long.'
            ]);

            // Fetch the Super-Admin from the database
        $superAdmin = Admin::where('name', 'Super Admin')->first();

            // Check if Super-Admin exists
            if (!$superAdmin) {
                $errorMessage = 'System error: Super-Admin not found. Please contact system administrator.';
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'type' => 'error'
                    ], 422);
                }
                
                notify()->error($errorMessage);
                return redirect()->back()->withInput();
            }

            // Check if Super-Admin has a key set
            if (empty($superAdmin->key)) {
                $errorMessage = 'System error: Super-Admin key not configured. Please contact system administrator.';
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'type' => 'error'
                    ], 422);
                }
                
                notify()->error($errorMessage);
                return redirect()->back()->withInput();
            }

            // Verify the provided key matches
            if ($request->input('admin_key') !== $superAdmin->key) {
                $errorMessage = 'Invalid Super-Admin key. Access denied.';
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'type' => 'error',
                        'errors' => ['admin_key' => ['The provided Super-Admin key is incorrect.']]
                    ], 422);
                }
                
                notify()->error($errorMessage);
                return redirect()->back()->withInput()->withErrors(['admin_key' => 'The provided Super-Admin key is incorrect.']);
            }

            // Find the user to delete
        $user = User::find($id);
            if (!$user) {
                $errorMessage = 'User not found. The user may have already been deleted.';
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'type' => 'error',
                        'redirect' => route('admin.user.index')
                    ], 404);
                }
                
                notify()->error($errorMessage);
                return redirect()->route('admin.user.index');
            }

            // Store user info for logging before deletion
            $deletedUserInfo = [
                'id' => $user->id,
                'name' => $user->name ?? 'N/A',
                'email' => $user->email ?? 'N/A',
                'created_at' => $user->created_at ?? 'N/A'
            ];

            // Begin transaction for cascade deletion
            \DB::beginTransaction();
            
            try {
                // Perform cascade deletion of all related data
                $this->cascadeDeleteUserData($user);
                
                // Finally delete the user
            $user->delete();
                
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollback();
                
                $errorMessage = 'Failed to delete user and related data. Please try again or contact system administrator.';
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'type' => 'error'
                    ], 500);
                }
                
                notify()->error($errorMessage);
                return redirect()->back();
        }

            $successMessage = 'User and all associated data deleted successfully. User: ' . ($deletedUserInfo['name'] ?? 'N/A') . ' (' . ($deletedUserInfo['email'] ?? 'N/A') . ')';
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'type' => 'success',
                    'redirect' => route('admin.user.index')
                ], 200);
            }
            
            notify()->success($successMessage);
        return redirect()->route('admin.user.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed. Please check your input and try again.',
                    'type' => 'error',
                    'errors' => $e->errors()
                ], 422);
            }
            
            notify()->error('Validation failed. Please check your input and try again.');
            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            // Handle any unexpected errors
            $errorMessage = 'An unexpected error occurred. Please try again or contact system administrator.';
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'type' => 'error'
                ], 500);
            }
            
            notify()->error($errorMessage);
            return redirect()->back()->withInput();
        }
    }

    /**
     * Cascade delete all user-related data
     * 
     * @param User $user
     * @return void
     */
    private function cascadeDeleteUserData(User $user)
    {
        $userId = $user->id;
        $deletionCounts = [];

        // First, delete ledgers that reference user's transactions and accounts
        // This is critical as ledgers have foreign key constraints to both transactions and accounts
        $userTransactionIds = $user->transaction()->pluck('id')->toArray();
        $userAccountIds = $user->accounts()->pluck('id')->toArray();
        
        // Delete ledgers referencing user's transactions
        $ledgerTransactionCount = 0;
        if (!empty($userTransactionIds)) {
            $ledgerTransactionCount = Ledger::whereIn('transaction_id', $userTransactionIds)->count();
            Ledger::whereIn('transaction_id', $userTransactionIds)->delete();
        }
        
        // Delete ledgers referencing user's accounts  
        $ledgerAccountCount = 0;
        if (!empty($userAccountIds)) {
            $ledgerAccountCount = Ledger::whereIn('account_id', $userAccountIds)->count();
            Ledger::whereIn('account_id', $userAccountIds)->delete();
        }
        
        $deletionCounts['ledgers_by_transaction'] = $ledgerTransactionCount;
        $deletionCounts['ledgers_by_account'] = $ledgerAccountCount;

        // Delete bonus_deductions that reference user's transactions or bonus_transactions
        $bonusDeductionCount = 0;
        if (\Schema::hasTable('bonus_deductions')) {
            $bonusDeductionDeleteCount = 0;
            
            // Delete bonus_deductions that reference withdraw transactions from this user
            if (!empty($userTransactionIds)) {
                $bonusDeductionDeleteCount += \DB::table('bonus_deductions')
                    ->whereIn('withdraw_transaction_id', $userTransactionIds)
                    ->count();
                \DB::table('bonus_deductions')
                    ->whereIn('withdraw_transaction_id', $userTransactionIds)
                    ->delete();
            }
            
            // Delete bonus_deductions that reference bonus_transactions from this user
            if (\Schema::hasTable('bonus_transactions') && (!empty($userTransactionIds) || !empty($userAccountIds))) {
                $userBonusTransactionIds = [];
                
                if (!empty($userTransactionIds)) {
                    $userBonusTransactionIds = array_merge($userBonusTransactionIds, 
                        \DB::table('bonus_transactions')->whereIn('transaction_id', $userTransactionIds)->pluck('id')->toArray());
                }
                
                if (!empty($userAccountIds)) {
                    $userBonusTransactionIds = array_merge($userBonusTransactionIds, 
                        \DB::table('bonus_transactions')->whereIn('account_id', $userAccountIds)->pluck('id')->toArray());
                }
                
                $userBonusTransactionIds = array_unique($userBonusTransactionIds);
                
                if (!empty($userBonusTransactionIds)) {
                    $bonusDeductionDeleteCount += \DB::table('bonus_deductions')
                        ->whereIn('bonus_transaction_id', $userBonusTransactionIds)
                        ->count();
                    \DB::table('bonus_deductions')
                        ->whereIn('bonus_transaction_id', $userBonusTransactionIds)
                        ->delete();
                }
            }
            
            $bonusDeductionCount = $bonusDeductionDeleteCount;
        }
        $deletionCounts['bonus_deductions'] = $bonusDeductionCount;

        // Delete bonus_transactions that reference user's transactions and accounts
        $bonusTransactionCount = 0;
        if (\Schema::hasTable('bonus_transactions')) {
            $bonusTransactionDeleteCount = 0;
            
            // Delete by transaction_id
            if (!empty($userTransactionIds)) {
                $bonusTransactionDeleteCount += \DB::table('bonus_transactions')
                    ->whereIn('transaction_id', $userTransactionIds)
                    ->count();
                \DB::table('bonus_transactions')
                    ->whereIn('transaction_id', $userTransactionIds)
                    ->delete();
            }
            
            // Delete by account_id 
            if (!empty($userAccountIds)) {
                $bonusTransactionDeleteCount += \DB::table('bonus_transactions')
                    ->whereIn('account_id', $userAccountIds)
                    ->count();
                \DB::table('bonus_transactions')
                    ->whereIn('account_id', $userAccountIds)
                    ->delete();
            }
            
            $bonusTransactionCount = $bonusTransactionDeleteCount;
        }
        $deletionCounts['bonus_transactions'] = $bonusTransactionCount;

        // Now safely delete Transactions
        $transactionCount = $user->transaction()->count();
        $user->transaction()->delete();
        $deletionCounts['transactions'] = $transactionCount;

        // Delete Forex Accounts (soft delete)
        $forexCount = $user->ForexAccounts()->count();
        $user->ForexAccounts()->delete(); // This will soft delete
        $deletionCounts['forex_accounts'] = $forexCount;

        // Now safely delete Accounts (wallet balances)
        $accountCount = $user->accounts()->count();
        $user->accounts()->delete();
        $deletionCounts['accounts'] = $accountCount;

        // Delete User Metas
        $metaCount = $user->user_metas()->count();
        $user->user_metas()->delete();
        $deletionCounts['user_metas'] = $metaCount;

        // Delete MetaTransactions
        $metaTransactionCount = $user->metaTransaction()->count();
        $user->metaTransaction()->delete();
        $deletionCounts['meta_transactions'] = $metaTransactionCount;

        // Delete MetaDeals
        $metaDealCount = $user->metaDeals()->count();
        $user->metaDeals()->delete();
        $deletionCounts['meta_deals'] = $metaDealCount;

        // Delete Notes
        $noteCount = $user->notes()->count();
        $user->notes()->delete();
        $deletionCounts['notes'] = $noteCount;

        // Delete Tickets (using HasTickets trait)
        $ticketCount = \DB::table('tickets')->where('user_id', $userId)->count();
        \DB::table('tickets')->where('user_id', $userId)->delete();
        $deletionCounts['tickets'] = $ticketCount;

        // Delete Leverage Updates
        $leverageCount = $user->leverageUpdates()->count();
        $user->leverageUpdates()->delete();
        $deletionCounts['leverage_updates'] = $leverageCount;

        // Delete IB Question Answers
        try {
            if ($user->ibQuestionAnswers) {
                $user->ibQuestionAnswers()->delete();
                $deletionCounts['ib_question_answers'] = 1;
            } else {
                $deletionCounts['ib_question_answers'] = 0;
            }
        } catch (\Exception $e) {
            $deletionCounts['ib_question_answers'] = 0;
        }

        // Delete Risk Profile Tag relationships (check if table exists first)
        $riskTagCount = 0;
        if (\Schema::hasTable('risk_profile_tag_user')) {
            $riskTagCount = $user->riskProfileTags()->count();
            $user->riskProfileTags()->detach();
        }
        $deletionCounts['risk_profile_tags'] = $riskTagCount;

        // Delete Referral Relationship
        try {
            if ($user->referralRelationship) {
                $user->referralRelationship()->delete();
                $deletionCounts['referral_relationship'] = 1;
            } else {
                $deletionCounts['referral_relationship'] = 0;
            }
        } catch (\Exception $e) {
            $deletionCounts['referral_relationship'] = 0;
        }

        // Delete Referral Links
        try {
            if ($user->getReferral()) {
                $user->getReferral()->delete();
                $deletionCounts['referral_links'] = 1;
            } else {
                $deletionCounts['referral_links'] = 0;
            }
        } catch (\Exception $e) {
            $deletionCounts['referral_links'] = 0;
        }

        // Update referrals to remove this user as referrer
        $referralUpdateCount = User::where('ref_id', $userId)->count();
        User::where('ref_id', $userId)->update(['ref_id' => null]);
        $deletionCounts['referral_updates'] = $referralUpdateCount;

        // Delete Bonus relationships (check if table exists first)
        $bonusCount = 0;
        if (\Schema::hasTable('bonus_user')) {
            $bonusCount = $user->bonuses()->count();
            $user->bonuses()->detach();
        }
        $deletionCounts['bonus_relationships'] = $bonusCount;

        // Delete Customer Group relationships (check if table exists first)
        $customerGroupCount = 0;
        if (\Schema::hasTable('customer_group_has_customers')) {
            $customerGroupCount = $user->customerGroups()->count();
            $user->customerGroups()->detach();
        }
        $deletionCounts['customer_groups'] = $customerGroupCount;

        // Delete Staff User relationships (check if table exists first)
        $staffCount = 0;
        if (\Schema::hasTable('staff_user')) {
            $staffCount = $user->staff()->count();
            $user->staff()->detach();
        }
        $deletionCounts['staff_relationships'] = $staffCount;

        // Delete additional user-related records that might exist
        $this->deleteAdditionalUserData($userId, $deletionCounts);

        // Cascade deletion completed
    }

    /**
     * Delete additional user-related data from junction tables and other references
     * 
     * @param int $userId
     * @param array &$deletionCounts
     * @return void
     */
    private function deleteAdditionalUserData($userId, &$deletionCounts)
    {
        // Delete from user_otps table
        $otpCount = 0;
        if (\Schema::hasTable('user_otps')) {
            $otpCount = \DB::table('user_otps')->where('user_id', $userId)->count();
            \DB::table('user_otps')->where('user_id', $userId)->delete();
        }
        $deletionCounts['user_otps'] = $otpCount;

        // Delete from user_language table
        $languageCount = 0;
        if (\Schema::hasTable('user_language')) {
            $languageCount = \DB::table('user_language')->where('user_id', $userId)->count();
            \DB::table('user_language')->where('user_id', $userId)->delete();
        }
        $deletionCounts['user_language'] = $languageCount;

        // Delete from rebate_records table
        $rebateCount = 0;
        if (\Schema::hasTable('rebate_records')) {
            $rebateCount = \DB::table('rebate_records')->where('user_id', $userId)->count();
            \DB::table('rebate_records')->where('user_id', $userId)->delete();
        }
        $deletionCounts['rebate_records'] = $rebateCount;

        // Delete from user_ib_rules table
        $ibRulesCount = 0;
        if (\Schema::hasTable('user_ib_rules')) {
            $ibRulesCount = \DB::table('user_ib_rules')->where('user_id', $userId)->count();
            \DB::table('user_ib_rules')->where('user_id', $userId)->delete();
        }
        $deletionCounts['user_ib_rules'] = $ibRulesCount;

        // Delete from old_transactions table if it exists
        if (\Schema::hasTable('old_transactions')) {
            $oldTxnCount = \DB::table('old_transactions')->where('user_id', $userId)->count();
            \DB::table('old_transactions')->where('user_id', $userId)->delete();
            $deletionCounts['old_transactions'] = $oldTxnCount;
        }

        // Delete from kyc_user pivot table if it exists
        if (\Schema::hasTable('kyc_user')) {
            $kycUserCount = \DB::table('kyc_user')->where('user_id', $userId)->count();
            \DB::table('kyc_user')->where('user_id', $userId)->delete();
            $deletionCounts['kyc_user'] = $kycUserCount;
        }

        // Delete any sessions for this user
        $sessionCount = 0;
        if (\Schema::hasTable('sessions')) {
            $sessionCount = \DB::table('sessions')->where('user_id', $userId)->count();
            \DB::table('sessions')->where('user_id', $userId)->delete();
        }
        $deletionCounts['sessions'] = $sessionCount;

        // Delete personal access tokens if using Sanctum
        if (\Schema::hasTable('personal_access_tokens')) {
            $tokenCount = \DB::table('personal_access_tokens')
                ->where('tokenable_type', User::class)
                ->where('tokenable_id', $userId)
                ->count();
            \DB::table('personal_access_tokens')
                ->where('tokenable_type', User::class)
                ->where('tokenable_id', $userId)
                ->delete();
            $deletionCounts['personal_access_tokens'] = $tokenCount;
        }
    }

    /**
     * @return RedirectResponse
     */
    public function statusUpdate($id, Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'status' => 'required',
//            'is_multi_ib' => 'required',
            'email_verified' => 'required',
//            'kyc' => 'required',
            'two_fa' => 'required',
            'deposit_status' => 'required',
            'withdraw_status' => 'required',
            'transfer_status' => 'required',
            'account_limit' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
        if (empty($input['account_limit'])) {
            $input['account_limit'] = 1;
        }
        $data = [
            'status' => $input['status'],
//            'is_multi_ib' => $input['is_multi_ib'],
//            'kyc' => $input['kyc'],
            'two_fa' => $input['two_fa'],
            'deposit_status' => $input['deposit_status'],
            'withdraw_status' => $input['withdraw_status'],
            'transfer_status' => $input['transfer_status'],
            'email_verified_at' => $input['email_verified'] == 1 ? now() : null,
            'account_limit' => $input['account_limit'],
        ];

        $user = User::find($id);

        if ($user->status != $input['status'] && !$input['status']) {

            $shortcodes = [
                '[[full_name]]' => $user->full_name,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];

            $this->mailNotify($user->email, 'user_account_disabled', $shortcodes);
            $this->smsNotify('user_account_disabled', $shortcodes, $user->phone);
        }

        User::find($id)->update($data);

        notify()->success('Status Updated Successfully', 'success');

        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    public function update($id, Request $request)
    {
        // Fetch the user
        $user = User::findOrFail($id);

        // Get setting-based flags
        $isUsername = (bool) getPageSetting('username_show');
        $isCountry = (bool) getPageSetting('country_show');
        $isPhone = (bool) getPageSetting('phone_show');

        // Validation
        $isPhoneRestricted = (bool) setting('phone_number_restriction', 'permission');
        
        $phoneRules = [Rule::requiredIf($isPhone), 'string', 'max:255'];
        
        // Add unique validation if phone restriction is enabled (regardless of phone_show)
        if ($isPhoneRestricted) {
            $phoneRules[] = Rule::unique('users', 'phone')->ignore($id);
        }
        
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'country' => [Rule::requiredIf($isCountry), 'string', 'max:255'],
            'username' => [Rule::requiredIf($isUsername), 'string', 'max:255', 'unique:users,username,' . $id],
            'phone' => $phoneRules,
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'email_verified_at' => 'nullable|date',
            'gender' => 'in:male,female,other|nullable',
            'city' => 'string|max:255|nullable',
            'zip_code' => 'string|max:10|nullable',
            'address' => 'string|max:255|nullable',
            'risk_profile_tags' => 'sometimes|array',
            'risk_profile_tags.*' => 'exists:risk_profile_tags,id',
            'comment' => 'nullable|string|max:500',
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'date_of_birth' => 'nullable|date',
        ]);

        // Cross-validate phone using helper and normalize to E.164
        $validator->after(function ($v) use ($request, $isPhone, $isCountry) {
            $rawPhone = $request->input('formatted_phone') ?: $request->input('phone');
            $countryRaw = $request->input('country');
            if (($isPhone || !is_null($rawPhone)) && !empty($rawPhone)) {
                // Backend: allow phone country to be independent from selected country
                $validation = validateAndNormalizePhone($rawPhone, $countryRaw, null, false);
                if (!$validation['valid']) {
                    $v->errors()->add('phone', $validation['error'] ?? 'Invalid phone number.');
                    return;
                }
                $request->merge(['__normalized_phone' => $validation['e164']]);
            }
        });

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back()->withInput();
        }

        // Collect input data
        $input = $request->only([
            'first_name',
            'last_name',
            'country',
            'username',
            'phone',
            'email',
            'gender',
            'date_of_birth',
            'city',
            'zip_code',
            'address',
            'risk_profile_tags',
            'comment',
            'password',
        ]);

        // Handle phone number: use normalized E.164 if present
        $phone = $request->input('__normalized_phone') ?? ($request->phone ?? $user->phone);
        

       
        $data = [
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'country' => $input['country'] ?? $user->country,
            'username' => $input['username'],
            'phone' => $phone,
            'email' => $input['email'],
            'gender' => $input['gender'] ?? $user->gender,
            'city' => $input['city'] ?? $user->city,
            'zip_code' => $input['zip_code'] ?? $user->zip_code,
            'address' => $input['address'] ?? $user->address,
            'comment' => $input['comment'] ?? $user->comment,
            'date_of_birth' => $input['date_of_birth'] ?: null, // Set to null if empty
            'email_verified_at' => $request->has('is_email_verified') ? now() : $user->email_verified_at,
        ];
        // Update basic user details
        $user->update($data);

        // Update password if provided
        if (!empty($input['password'])) {
            $user->password = Hash::make($input['password']);
            $user->save();
        }

        // Sync risk profile tags using pivot table
        if (isset($input['risk_profile_tags']) && is_array($input['risk_profile_tags'])) {
            $user->riskProfileTags()->sync($input['risk_profile_tags']);
        }

        // Handle branch assignment
        if ($request->has('branch_id')) {
            $branchId = $request->input('branch_id');
            if (!empty($branchId)) {
                setUserBranchId($user->id, $branchId);
            } else {
                // Remove branch assignment if empty
                setUserBranchId($user->id, null);
            }
        }

        // Redirect with success message
        notify()->success('User Info Updated Successfully', 'success');
        return redirect()->back();
    }

    public function kyc(Request $request, $id)
    {
        //        dd($request->all());
        // Fetch the user
        $user = User::findOrFail($id);

        $kyc = $request->kyc;

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

    /**
     * @return RedirectResponse
     */
    public function resetPassword(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => 'required|string|min:8',
        ]);

        $user = User::find($request->user_id);
        $user->password = Hash::make($request->password);
        $user->save();

        // Send an email notification
        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[email]]' => $user->email,
            '[[password]]' => $request->password,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];
        $this->mailNotify($user->email, 'reset_user_password_by_admin', $shortcodes);

        notify()->success('Password has been successfully reset and emailed to the user', 'success');
        return redirect()->back();
    }

    public function passwordUpdate($id, Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $password = $validator->validated();
        $user = User::find($id);
        $user->update([
            'password' => Hash::make($password['new_password']),
        ]);
//        dd($user);


        notify()->success('User Password Updated Successfully', 'success');

        return redirect()->back();
    }

    /**
     * @return RedirectResponse|void
     */

    public function balanceUpdate($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target_id' => 'required',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:add,subtract',
            'target_type' => 'required|in:forex,wallet',
            'comment' => 'required|string|max:255',
        ]);
//dd($request->all());
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            $targetId = $request->input('target_id');
            $targetType = $request->input('target_type');
            $amount = BigDecimal::of($request->amount);
            $type = $request->input('type');
            $comment = $request->input('comment');

            $user = User::findOrFail($id);
            $adminUser = \Auth::user();

            if ($targetType === 'forex') {
                // Fetch forex account for cent check
                $forexAccount = ForexAccount::where('login', $targetId)->firstOrFail();

                // Scale amount if it's a cent account
                $scaledAmount = apply_cent_account_adjustment($targetId, $amount->toFloat());

                // Add or subtract balance
                if ($type === 'add') {
                    $data = [
                        'login' => $targetId,
                        'Amount' => $scaledAmount,
                        'type' => 1, // Deposit
                        'TransactionComments' => $comment,
                    ];
                    $response = $this->forexApiService->balanceOperation($data);

                    if (!($response['success'] && 
                ($response['result']['responseCode'] == 10009 || $response['result']['responseCode'] === 'MT_RET_REQUEST_DONE')
            )) {
                        throw new \Exception(__('Forex deposit operation failed. Response: ') . json_encode($response));
                    }

                } else {
                    // Fetch balance from Forex API for validation
                    $balance = $this->forexApiService->getValidatedBalance(['login' => $targetId]);

                    if (BigDecimal::of($scaledAmount) > $balance) {
                        throw new \Exception(__('Insufficient funds in Forex account.'));
                    }

                    $data = [
                        'login' => $targetId,
                        'Amount' => $scaledAmount,
                        'type' => 2, // Withdraw
                        'TransactionComments' => $comment,
                    ];
                    $response = $this->forexApiService->balanceOperation($data);

                    if (!($response['success'] && 
                ($response['result']['responseCode'] == 10009 || $response['result']['responseCode'] === 'MT_RET_REQUEST_DONE')
            )) {
                        throw new \Exception(__('Forex withdrawal operation failed. Response: ') . json_encode($response));
                    }
                }

                // Log transaction in system at original amount
                $txn = Txn::new(
                    $amount->toFloat(),
                    0,
                    $amount->toFloat(),
                    'system',
                    $type === 'add' ? 'Admin Added Balance' : 'Admin Subtracted Balance',
                    $type === 'add' ? TxnType::Deposit : TxnType::Subtract,
                    TxnStatus::Success,
                    null,
                    null,
                    $user->id,
                    $adminUser->id,
                    'Admin',
                    [],
                    $comment,
                    $targetId,
                    'forex'
                );
                $txn->action_by = auth()->user()->id;
                $txn->save();
            } elseif ($targetType === 'wallet') {
                // Wallet account operations
                $account = Account::where('wallet_id', $targetId)->where('user_id', $user->id)->firstOrFail();

                if ($type === 'add') {
                    $account->amount = BigDecimal::of($account->amount)->plus($amount);
                    $account->save();

                    $txn = Txn::new(
                        $amount->toFloat(),
                        0,
                        $amount->toFloat(),
                        'system',
                        'Admin Added Balance',
                        TxnType::Deposit,
                        TxnStatus::Success,
                        null,
                        null,
                        $user->id,
                        $adminUser->id,
                        'Admin',
                        [],
                        $comment,
                        $account->wallet_id,
                        'wallet'
                    );
                    $txn->action_by = auth()->user()->id;
                    $txn->save();
                    $ledgerBalance = $this->walletService->getLedgerBalance($account->id);
                    $this->walletService->createCreditLedgerEntry($txn, $ledgerBalance);

                } else {
                    if ($amount->compareTo(BigDecimal::of($account->amount)) > 0) {
                        throw new \Exception(__('Insufficient funds in Wallet account.'));
                    }

                    $account->amount = BigDecimal::of($account->amount)->minus($amount);
                    $account->save();

                    $txn = Txn::new(
                        $amount->toFloat(),
                        0,
                        $amount->toFloat(),
                        'system',
                        'Admin Subtracted Balance',
                        TxnType::Subtract,
                        TxnStatus::Success,
                        null,
                        null,
                        $user->id,
                        $adminUser->id,
                        'Admin',
                        [],
                        $comment,
                        $account->wallet_id,
                        'wallet'
                    );
                    $txn->action_by = auth()->user()->id;
                    $txn->save();
                    $ledgerBalance = $this->walletService->getLedgerBalance($account->id);
                    $this->walletService->createDebitLedgerEntry($txn, $ledgerBalance);
                }
            }

            DB::commit();
            notify()->success(__('Balance Updated Successfully.'), 'Success');
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Balance Update Failed: ' . $e->getMessage());
            notify()->error($e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function mailSendAll()
    {
    $ibGroups = IbGroup::where('status', 1)->get();
    $forexSchemas = ForexSchema::where('status', 1)->orderBy('title')->get();
    $users = User::where('status', 1)->get(['id', 'first_name', 'last_name', 'email']);

    return view('backend.user.mail_send_all', compact('ibGroups', 'forexSchemas', 'users'));
    }

    /**
     * @return RedirectResponse
     */
    public function mailSend(Request $request)
    {
        $message = $request->input('message');

        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
        // Flash all input including the Select2 values
        $request->flash();
        return redirect()->back()->withErrors($validator);
        }

        try {
        // Prepare email content
            $input = [
                'subject' => $request->subject,
            'message' => str_replace(['{', '}'], ['<', '>'], $request->message),
            ];

            $shortcodes = [
                '[[subject]]' => $input['subject'],
                '[[message]]' => $input['message'],
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];

        // Handle single user case (from user list action)
            if (isset($request->id)) {
                $user = User::find($request->id);
            if (!$user) {
                throw new \Exception('User not found');
            }

                $shortcodes = array_merge($shortcodes, ['[[full_name]]' => $user->full_name]);

                $this->mailNotify($user->email, 'user_mail', $shortcodes);

            notify()->success(__('Email sent successfully to :name', ['name' => $user->full_name]));
            return redirect()->back();
        }

        // Bulk email case
        $ibGroups = $request->input('ib_groups', []);
        $accountTypes = $request->input('account_types', []);
        $selectedUsers = $request->input('users', []);

        // Start building the query
        $query = User::where('status', 1);

        // If specific users are selected
        if (!empty($selectedUsers)) {
            if (!in_array('all', $selectedUsers)) {
                $query->whereIn('id', $selectedUsers);
            }
        }
        // Apply IB group and account type filters
        else {
            // Filter by IB groups (including network)
            if (!empty($ibGroups)) {
                if (in_array('all', $ibGroups)) {
                    $query->where(function($q) {
                        $q->whereHas('ibGroup')
                          ->orWhereHas('user_metas', function($q) {
                              $q->where('meta_key', 'is_part_of_master_ib');
                          });
                    });
            } else {
                    $query->where(function($q) use ($ibGroups) {
                        $q->whereHas('ibGroup', function($q) use ($ibGroups) {
                            $q->whereIn('id', $ibGroups);
                        })
                        ->orWhereHas('user_metas', function($q) use ($ibGroups) {
                            $q->where('meta_key', 'is_part_of_master_ib')
                              ->whereIn('meta_value', $ibGroups);
                        });
                    });
                }
            }

            // Filter by account types
           if (!empty($accountTypes)) {
                if (in_array('all', $accountTypes)) {
                    $query->whereHas('forexAccounts');
                } else {
                    $query->whereHas('forexAccounts', function($q) use ($accountTypes) {
                        $q->whereIn('forex_schema_id', $accountTypes);
                    });
                }
            }
        }

        // Get users and send emails
        $users = $query->get();
        $sentCount = 0;

                foreach ($users as $user) {
            try {
                $userShortcodes = array_merge($shortcodes, [
                    '[[full_name]]' => $user->full_name
                ]);

                $this->mailNotify($user->email, 'user_mail', $userShortcodes);
                $sentCount++;
            } catch (\Exception $e) {
                \Log::error("Failed to send email to {$user->email}: " . $e->getMessage());
            }
        }

        notify()->success(__('Emails sent successfully to :count recipients', ['count' => $sentCount]));

    } catch (\Exception $e) {
        \Log::error('Mail sending failed: ' . $e->getMessage());
        notify()->error(__('Failed to send emails: :error', ['error' => $e->getMessage()]));
    }

        return redirect()->back();
    }

    /**
     * @return JsonResponse|void
     *
     * @throws Exception
     */
    public function transaction($id, Request $request)
    {
        if ($request->ajax()) {
            $data = Transaction::where('user_id', $id)
                ->where('type', '!=', TxnType::IbBonus->value) // Exclude ib_bonus
                ->where('status', '!=', \App\Enums\TxnStatus::None) // Exclude none status
                ->latest();

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', 'backend.user.include.__txn_status')
                ->editColumn('type', 'backend.user.include.__txn_type')
                ->editColumn('final_amount', 'backend.user.include.__txn_amount')
                ->addColumn('action_by', function ($row) {
                    return '<span class="text-nowrap">' . optional($row->staff)->name ?? '-' . '</span>';
                })
                ->rawColumns(['status', 'type','action_by', 'final_amount'])
                ->make(true);
        }
    }

    public function ibBonus($id, Request $request)
    {
        if ($request->ajax()) {
            // Prepare filters from request
            $filters = $request->only(['created_at', 'status', 'type', 'amount_min', 'amount_max', 'tnx', 'description', 'login', 'deal', 'order', 'symbol', 'date_filter']);
            
            // Get IB transactions from quarter tables (past 3 months if no filters, 1 year if filters applied)
            $data = \App\Services\IBTransactionQueryService::getUserIBTransactions($id, $filters);
            
            // Get summary data for display
            $summary = \App\Services\IBTransactionQueryService::getUserIBTransactionsSummary($id, $filters);
            
            // Get user's lifetime IB balance (already contains total of all IB transactions received)
            $user = \App\Models\User::find($id);
            $lifetimeIBBalance = $user ? $user->ib_balance : 0;
            
            // Get current IB wallet balance
            $currentIBWalletBalance = 0;
            if ($user) {
                $ibWalletAccount = get_user_account($user->id, \App\Enums\AccountBalanceType::IB_WALLET);
                $currentIBWalletBalance = $ibWalletAccount ? $ibWalletAccount->amount : 0;
            }
            
            if (!$data) {
                return Datatables::of(collect([]))->make(true);
            }

        return Datatables::of($data->orderBy('created_at', 'desc'))
            ->addIndexColumn()
            ->editColumn('status', 'backend.user.include.__txn_status')
            ->editColumn('type', 'backend.user.include.__txn_type')
            ->editColumn('final_amount', 'backend.user.include.__txn_amount')
            ->editColumn('created_at', function ($row) {
                // if (!empty($row->manual_field_data) && $row->manual_field_data !== '[]') {
                //     $manualData = json_decode($row->manual_field_data, true);
                //     if (is_array($manualData) && isset($manualData['time'])) {
                //         return \Carbon\Carbon::parse($manualData['time'])->format('M d, Y h:i A');
                //     }
                // }
                return \Carbon\Carbon::parse($row->created_at)->format('M d, Y h:i A');
            })
            ->addColumn('deal_info', function ($row) {
                if (!empty($row->manual_field_data) && $row->manual_field_data !== '[]') {
                    $manualData = json_decode($row->manual_field_data, true);
                    if (is_array($manualData)) {
                        $deal = $manualData['deal'] ?? '-';
                        $symbol = $manualData['symbol'] ?? '-';
                        $login = $manualData['login'] ?? '-';
                        return "<small>Deal: {$deal}<br>Symbol: {$symbol}<br>Login: {$login}</small>";
                    }
                }
                return '-';
            })
            ->rawColumns(['status', 'created_at', 'type', 'final_amount', 'deal_info'])
            ->with([
                'summary' => [
                    'ib_balance' => number_format($lifetimeIBBalance, 2),
                    'current_ib_wallet_balance' => number_format($currentIBWalletBalance, 2),
                    'total_amount' => number_format($summary['total_amount'], 2),
                    'total_count' => number_format($summary['total_count']),
                    'filter_start_date' => $summary['filter_start_date'] ? $summary['filter_start_date']->format('M d, Y') : null,
                    'filter_end_date' => $summary['filter_end_date'] ? $summary['filter_end_date']->format('M d, Y') : null,
                ]
            ])
            ->make(true);
    }
    }

    public function ibInfo($id, Request $request)
    {
        //dd($id);
        if ($request->ajax()) {
            $data = User::where('id', $id)->latest();

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('ib_status', 'backend.user.include.__ib_status')
                ->addColumn('action', 'backend.user.include.__ib_action')
                ->rawColumns(['ib_status', 'action'])
                ->make(true);
        }
    }

    /**
     * @return RedirectResponse
     */
    public function userLogin($id)
    {
        Auth::guard('web')->loginUsingId($id);

        return redirect()->route('user.dashboard');
    }

   public function createCustomer()
    {
        // Get the location (e.g., from a user's profile or a service like IP-based location)
        $location = auth()->user()->location ?? (object) ['country_code' => '', 'dial_code' => ''];

        // Assuming this function returns an array of countries
        $countries = getCountries();
        $kycLevels = KycLevel::where('status', 1)->get();
        $kycs = Kyc::where('kyc_sub_level_id', 3)->where('status', true)->get();
        // dd($kycLevels);
        // Get all risk profile tags
        $riskProfileTags = RiskProfileTag::all();
        $kycStatus = KYCStatus::cases();
        // dd($kycstatus);
        $staffMembers = Admin::whereDoesntHave('roles', function($query) {
    $query->where('name', 'Super-Admin');
})->get();
        return view('backend.user.create', compact('location', 'countries', 'riskProfileTags', 'kycLevels', 'kycStatus', 'kycs', 'staffMembers'));
    }
public function store(Request $request)
    {
        // Get setting-based flags
        $isUsername = (bool) getPageSetting('username_show');
        $isCountry = (bool) getPageSetting('country_show');
        $isPhone = (bool) getPageSetting('phone_show');


        // Validate the request data
        try {
            $isPhoneRestricted = (bool) setting('phone_number_restriction', 'permission');
            
            $phoneRules = [Rule::requiredIf($isPhone), 'string', 'max:255'];
            
            // Add unique validation if phone restriction is enabled (regardless of phone_show)
            if ($isPhoneRestricted) {
                $phoneRules[] = 'unique:users,phone';
            }
            
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'country' => [Rule::requiredIf($isCountry), 'string', 'max:255'],
                'username' => [Rule::requiredIf($isUsername), 'string', 'max:255', 'unique:users'],
                'phone' => $phoneRules,
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'email_verified_at' => 'nullable|date',
                'gender' => 'in:male,female,other|nullable',
                'city' => 'string|max:255|nullable',
                'zip_code' => 'string|max:10|nullable',
                'address' => 'string|max:255|nullable',
                'risk_profile_tags' => 'array|nullable',
                'risk_profile_tags.*' => 'exists:risk_profile_tags,id',
                'comment' => 'nullable|string|max:500',
                'kyc' => [
                    'nullable',
                    function ($attribute, $value, $fail) {
                        // Handle KYC Levels or KYC Status enum values
                        if (Str::startsWith($value, 'kyc_')) {
                            $statusValue = str_replace('kyc_', '', $value);
                            if (!in_array((int) $statusValue, array_column(KYCStatus::cases(), 'value'))) {
                                $fail('The selected KYC status is invalid.');
                            }
                        } elseif (!KycLevel::where('id', $value)->exists()) {
                            $fail('The selected KYC level is invalid.');
                        }
                    },
                ],
                'password' => ['required', 'string', 'min:8'],
                'date_of_birth' => 'nullable|date',
                'kyc_level' => 'nullable|in:1,3,5',
            'kyc_id' => 'nullable|exists:kycs,id',
            ]);

            // Cross-validate phone using helper and normalize to E.164
            $validator->after(function ($v) use ($request, $isPhone, $isCountry) {
                $rawPhone = $request->input('formatted_phone') ?: $request->input('phone');
                $countryRaw = (string) $request->input('country');
                if (($isPhone || !is_null($rawPhone)) && !empty($rawPhone)) {
                    $validation = validateAndNormalizePhone($rawPhone, $countryRaw, null);
                    if (!$validation['valid']) {
                        $v->errors()->add('phone', $validation['error'] ?? 'Invalid phone number.');
                        return;
                    }
                    $request->merge(['__normalized_phone' => $validation['e164']]);
                }
            });

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            // if needed elsewhere
            $validatedData = $validator->validated();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // Collect input data
        $input = $request->only([
            'first_name',
            'last_name',
            'country',
            'username',
            'phone',
            'email',
            'gender',
            'date_of_birth',
            'city',
            'zip_code',
            'address',
            'kyc',
            'risk_profile_tags',
            'comment',
            'password',
        ]);

        // Ensure date_of_birth is null if not provided
        $input['date_of_birth'] = !empty($input['date_of_birth']) ? $input['date_of_birth'] : null;

        // Get location details (e.g., from user's profile or a service)
       $location = auth()->user()->location ?? null;
       $location = is_object($location) ? $location : (object) ['country_code' => '', 'dial_code' => ''];

        // Set country and phone depending on settings and input
       $country = $isCountry && !empty($input['country']) ? explode(':', $input['country'])[0] : ($location->country_code ?? '');
       
       // Handle phone number with country code from intlTelInput using normalized value if present
       if (!empty($request->input('__normalized_phone'))) {
           $phone = $request->input('__normalized_phone');
       } elseif ($isPhone && !empty($input['phone'])) {
           if (strpos($input['phone'], '+') === 0) {
               $phone = $input['phone'];
           } else {
               $dialCode = $isCountry && !empty($input['country']) ? explode(':', $input['country'])[1] : ($location->dial_code ?? '');
               $phone = trim($dialCode . ' ' . $input['phone']);
           }
       } else {
           $phone = trim(($location->dial_code ?? '') . ' ' . ($input['phone'] ?? ''));
       }

        // Generate a username if it's not provided
        $username = $isUsername ? $input['username'] : $input['first_name'] . '.' . $input['last_name'] . '.' . rand(1000, 9999);

        // Get the ranking
        $rank = Ranking::find(1);

        // Handle the status value
       $kyc = $input['kyc'] ?? 0; // Default to 0 if not provided
    if (is_string($kyc) && Str::startsWith($kyc, 'kyc_')) {
        $kyc = (int) str_replace('kyc_', '', $kyc);
    } else {
        $kyc = (int) $kyc;
    }
  $kycLevel = (int)$request->kyc_level;
$kycId = $request->kyc_id;

// Basic validation for required fields using enum
if ($kycLevel === KYCStatus::Rejected->value) {
    if (empty($kycId)) {
        notify()->error('Please select a KYC type and upload documents.');
        return redirect()->back()->withInput();
    }
}

if ($kycLevel === KYCStatus::PendingLevel3->value) {
    notify()->error('Please Complete Level 2 first.');
    return redirect()->back()->withInput();
}


        // Create the user with exception handling
        try {
            $user = User::create([
                'ranking_id' => $rank->id,
                'rankings' => json_encode([$rank->id]),
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'country' => $country,
                'username' => $username,
                'phone' => $phone,
                'email' => $input['email'],
                'gender' => $input['gender'],
                'city' => $input['city'],
                'zip_code' => $input['zip_code'],
                'address' => $input['address'],
                'comment' => $input['comment'],
                'password' => Hash::make($input['password']),
                'date_of_birth' => $input['date_of_birth'],
                'email_verified_at' => $request->has('is_email_verified') ? now() : null,
                'kyc' => 0,
                'in_grace_period' => false,

            ]);

            // Handle risk profile tags
            if (isset($input['risk_profile_tags']) && is_array($input['risk_profile_tags'])) {
                $user->riskProfileTags()->attach($input['risk_profile_tags']);
            }
              // Handle KYC submission if level was provided
        if ($request->has('kyc_level') && $request->kyc_level) {
            // Create a new request object for the KYC submission
            $kycRequest = new \Illuminate\Http\Request();
            $kycRequest->replace($request->all());
            $kycRequest->setMethod('POST');

            // Call the kycSubmit method
            $kycController = app(\App\Http\Controllers\Backend\KycController::class);
            $kycResponse = $kycController->kycSubmit($kycRequest, $user->id);
        }
             // Assign to staff
        $staffId = auth()->user()->hasRole('Super-Admin')
            ? $request->staff_id
            : auth()->id();

        if ($staffId) {
            $staff = Admin::find($staffId);
            if ($staff) {
                $staff->users()->syncWithoutDetaching([$user->id]);
            }
        }
        // Send admin notification to configured user_site_email (supports multiple emails)
        try {
            $rawAdminEmails = (string) setting('user_site_email', 'global');
            if (!empty($rawAdminEmails)) {
                $creator = auth()->user();
                $creatorFullName = trim(($creator->first_name ?? '') . ' ' . ($creator->last_name ?? '')) ?: ($creator->name ?? '');
                $shortcodes = [
                    '[[full_name]]' => $user->first_name . ' ' . $user->last_name,
                    '[[email]]' => $user->email,
                    '[[created_by_name]]' => $creatorFullName,
                    '[[created_by_email]]' => $creator->email ?? '',
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                ];

                $emails = collect(preg_split('/[;,]/', $rawAdminEmails))
                    ->map(function ($e) { return trim($e); })
                    ->filter(function ($e) { return !empty($e); })
                    ->unique()
                    ->values();

                foreach ($emails as $email) {
                    $this->mailNotify($email, 'admin_new_user_registered_system', $shortcodes);
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to send new user admin email: ' . $e->getMessage());
        }
         notify()->success('Customer created successfully', 'success');
        // Redirect to the user index with success message
        return redirect()->route('admin.user.index')->with('success', 'Customer created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'User creation failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function createNote(Request $request, $id)
    {
        $user = User::find($id);
        $user->update(['notes' => $request->notes]);
        notify()->success('Note added successfully');

        return redirect()->back();
    }

    public function updateLeadStatus($leadId)
    {
        $lead = Lead::findOrFail($leadId);
        $lead->stage_id = 6;
        $lead->save();
    }

    public function leadAsClient(Request $request)
    {
        // Get setting-based flags
        $isUsername = (bool) getPageSetting('username_show');
        $isCountry = (bool) getPageSetting('country_show');
        $isPhone = (bool) getPageSetting('phone_show');


        // Validate the request data
        try {
            $isPhoneRestricted = (bool) setting('phone_number_restriction', 'permission');
            
            $phoneRules = [Rule::requiredIf($isPhone), 'string', 'max:255'];
            
            // Add unique validation if phone restriction is enabled (regardless of phone_show)
            if ($isPhoneRestricted) {
                $phoneRules[] = 'unique:users,phone';
            }
            
            $validatedData = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'country' => [Rule::requiredIf($isCountry), 'string', 'max:255'],
                'username' => [Rule::requiredIf($isUsername), 'string', 'max:255', 'unique:users'],
                'phone' => $phoneRules,
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'email_verified_at' => 'nullable|date',
                'gender' => 'in:male,female,other|nullable',
                'city' => 'string|max:255|nullable',
                'zip_code' => 'string|max:10|nullable',
                'address' => 'string|max:255|nullable',
                'risk_profile_tags' => 'array|nullable',
                'risk_profile_tags.*' => 'exists:risk_profile_tags,id',
                'comment' => 'nullable|string|max:500',
                'kyc' => [
                    'nullable',
                    function ($attribute, $value, $fail) {
                        // Handle KYC Levels or KYC Status enum values
                        if (Str::startsWith($value, 'kyc_')) {
                            $statusValue = str_replace('kyc_', '', $value);
                            if (!in_array((int) $statusValue, array_column(KYCStatus::cases(), 'value'))) {
                                $fail('The selected KYC status is invalid.');
                            }
                        } elseif (!KycLevel::where('id', $value)->exists()) {
                            $fail('The selected KYC level is invalid.');
                        }
                    },
                ],
                'password' => ['required', 'string', 'min:8'],
                'date_of_birth' => 'nullable|date',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // Collect input data
        $input = $request->only([
            'first_name',
            'last_name',
            'country',
            'username',
            'phone',
            'email',
            'gender',
            'date_of_birth',
            'city',
            'zip_code',
            'address',
            'kyc',
            'risk_profile_tags',
            'comment',
            'password',
            'lead_id',
        ]);

        // Ensure date_of_birth is null if not provided
        $input['date_of_birth'] = !empty($input['date_of_birth']) ? $input['date_of_birth'] : null;

        // Get location details (e.g., from user's profile or a service)
        $location = auth()->user()->location ?? (object) ['country_code' => '', 'dial_code' => ''];

        // Set country and phone depending on settings and input
        $country = $isCountry ? explode(':', $input['country'])[0] : $location->country_code;
        
        // Handle phone number with country code from intlTelInput
        if ($isPhone && !empty($input['phone'])) {
            // Check if phone already contains country code (from intlTelInput)
            if (strpos($input['phone'], '+') === 0) {
                // Phone already has country code, use as is
                $phone = $input['phone'];
            } else {
                // Add country code from country selection or location
                $dialCode = $isCountry ? explode(':', $input['country'])[1] : $location->dial_code;
                $phone = $dialCode . ' ' . $input['phone'];
            }
        } else {
            $phone = $location->dial_code . ' ' . ($input['phone'] ?? '');
        }

        // Generate a username if it's not provided
        $username = $isUsername ? $input['username'] : $input['first_name'] . '.' . $input['last_name'] . '.' . rand(1000, 9999);

        // Get the ranking
        $rank = Ranking::find(1);

        // Handle the status value
        $kyc = $input['kyc'];
        if (Str::startsWith($kyc, 'kyc_')) {
            $kyc = (int) str_replace('kyc_', '', $kyc); // Remove the prefix and convert to integer
        } else {
            $kyc = (int) $kyc; // Ensure it's an integer for KYC Level ID
        }

        DB::beginTransaction();
        // Create the user with exception handling
        try {

            $existingUser = User::where('email', $input['email'])->first();

            if ($existingUser) {
                $lead = Lead::findOrFail($input['lead_id']);
                $lead->stage_id = 6;
                $lead->save();
                DB::rollback();

                // Notify that the user already exists
                notify()->info('User already exists.', 'info');
                return redirect()->route('admin.user.index')->with('message', 'User already exists.');
            }

            $user = User::create([
                'ranking_id' => $rank->id,
                'rankings' => json_encode([$rank->id]),
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'country' => $country,
                'username' => $username,
                'phone' => $phone,
                'email' => $input['email'],
                'gender' => $input['gender'],
                'city' => $input['city'],
                'zip_code' => $input['zip_code'],
                'address' => $input['address'],
                'comment' => $input['comment'],
                'password' => Hash::make($input['password']),
                'date_of_birth' => $input['date_of_birth'],
                'email_verified_at' => $request->has('is_email_verified') ? now() : null,
                'kyc' => $kyc,
            ]);

            // Handle risk profile tags
            if (isset($input['risk_profile_tags']) && is_array($input['risk_profile_tags'])) {
                $user->riskProfileTags()->attach($input['risk_profile_tags']);
            }

            $lead = Lead::findOrFail($input['lead_id']);
            $lead->stage_id = 6;
            $lead->save();

            // Commit the transaction
            DB::commit();

        } catch (\Exception $e) {
            // Rollback transaction if something goes wrong
            DB::rollback();

            return redirect()->back()->withErrors(['error' => 'User creation failed: ' . $e->getMessage()])->withInput();
        }
        notify()->success('Customer created successfully', 'success');
        // Redirect to the user index with success message
        return redirect()->route('admin.user.index')->with('success', 'Customer created successfully');
    }

    public function searchUsers(Request $request)
    {
        $search = $request->get('q', $request->get('term')); // safe fallback

        $query = User::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
            ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->limit(10)->get();

        return response()->json([
            'results' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->first_name . ' ' . $user->last_name,
                    'avatar' => getFilteredPath($user->avatar, 'global/materials/user.png'),
                    'email' => $user->email
                ];
            })
        ]);
    }

    public function runMasterIbDistribution(Request $request, $userId)
    {
        $request->validate([
            'date' => ['required', 'date_format:Y-m-d H:i:s'],
        ]);

        $user = User::findOrFail($userId);

        try {
            Artisan::call('rebate:email-distribution', [
                'email' => $user->email,
                'start_date' => $request->input('date'),
            ]);

            notify()->success("Rebate distribution triggered successfully for {$user->username}.");

            return redirect()->back();
        } catch (\Exception $e) {
            notify()->error('Command failed: ' . $e->getMessage());

            return redirect()->back();
        }
    }
    public function  runChildIbDistribution(Request $request, $userId)
    {
        $request->validate([
            'date' => ['required', 'date_format:Y-m-d H:i:s'],
        ]);

        $user = User::findOrFail($userId);

        try {
            Artisan::call('rebate:email-distribution', [
                'email' => $user->email,
                'start_date' => $request->input('date'),
            ]);

            notify()->success("Rebate distribution triggered successfully for {$user->username}.");

            return redirect()->back();
        } catch (\Exception $e) {
            notify()->error('Command failed: ' . $e->getMessage());

            return redirect()->back();
        }
    }

    public function getAllReferrals(User $user)
    {
        $all = collect();

        foreach ($user->referrals as $referral) {
            $all->push($referral);
            $all = $all->merge($this->getAllReferrals($referral));
        }

        return $all;
    }

}
