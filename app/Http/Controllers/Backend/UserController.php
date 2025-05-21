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
use App\Models\CustomerGroup;
use App\Models\ForexAccount;
use App\Models\ForexSchema;
use App\Models\IbGroup;
use App\Models\LevelReferral;
use App\Models\RiskProfileTag;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Admin;
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
        $filters = $request->only(['global_search', 'phone', 'staff_name', 'country', 'status', 'created_at', 'tag']);

        if ($request->ajax()) {
         // Check if the logged-in user is a Super-Admin
         if ($loggedInUser->hasRole('Super-Admin')) {
            $data = User::applyFilters($filters);
        }
        // Check if the user has the "show-all-users-by-default-to-staff" permission
        elseif ($loggedInUser->can('show-all-users-by-default-to-staff')) {
            $data = User::applyFilters($filters);
        }
        else {
            // Get the attached users if the user is not a Super-Admin
            $attachedUserIds = $loggedInUser->users->pluck('id');

            if ($attachedUserIds->isNotEmpty()) {
                // Show only attached users
                $data = User::whereIn('id', $attachedUserIds)->applyFilters($filters);
            } else {
                // If no users are attached, show nothing (empty dataset)
                $data = User::where('id', -1); // Return an empty result set
            }
        }
 // Apply staff name filter if present
 if (!empty($filters['staff_name'])) {
    $data->whereHas('staff', function($query) use ($filters) {
        $searchTerm = $filters['staff_name'];

        // Check if search term contains a space (possible first + last name)
        if (str_contains($searchTerm, ' ')) {
            $nameParts = explode(' ', $searchTerm, 2);
            $query->where(function($subQuery) use ($nameParts) {
                $subQuery->where(function($q) use ($nameParts) {
                        $q->where('first_name', 'like', '%'.$nameParts[0].'%')
                          ->where('last_name', 'like', '%'.$nameParts[1].'%');
                    })
                    ->orWhere(function($q) use ($nameParts) {
                        $q->where('first_name', 'like', '%'.$nameParts[1].'%')
                          ->where('last_name', 'like', '%'.$nameParts[0].'%');
                    });
            });
        } else {
            // Single term search
            $query->where(function($subQuery) use ($searchTerm) {
                $subQuery->where('first_name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('last_name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('email', 'like', '%'.$searchTerm.'%');
            });
        }
    });
}
        $data->applyFilters($filters);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    return view('backend.user.include.__user', compact('row'))->render();
                })                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->editColumn('balance', 'backend.user.include.__total_balance_mt5')
                ->editColumn('equity', 'backend.user.include.__total_equity_mt5')
                ->editColumn('credit', 'backend.user.include.__total_credit_mt5')
                ->addColumn('staff_name', function ($row) {
                    return view('backend.user.include.__staff')->with('staff', $row->staff);
                })

                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['username', 'kyc', 'balance', 'equity', 'credit','staff_name', 'status', 'action'])
                ->make(true);
        }

        return view('backend.user.all');
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
                $fileName = strtolower(str_replace(' ', '-', $user->username)) . '-transactions-ibbonus.xlsx';
                return Excel::download(new ibTransactionsUsersExport($userId), $fileName);
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
        if ($request->ajax()) {

            // Check if the logged-in user is a Super-Admin
            if ($loggedInUser->hasRole('Super-Admin')) {
                $data = User::where('status', 1)->latest();
            }
            // If user has permission "show-all-users-by-default-to-staff", show all users
            elseif ($loggedInUser->can('show-all-users-by-default-to-staff')) {
                $data = User::where('status', 1)->latest();
            }
            // Otherwise, show only attached users
            else {
                $attachedUserIds = $loggedInUser->users->pluck('id');

                if ($attachedUserIds->isNotEmpty()) {
                    $data = User::where('status', 1)
                        ->whereIn('id', $attachedUserIds)
                        ->latest();
                } else {
                    // No attached users = No results
                    $data = User::where('id', -1); // Returns an empty dataset
                }
            }
// Apply staff name filter if present
if (!empty($filters['staff_name'])) {
    $data->whereHas('staff', function($query) use ($filters) {
        $searchTerm = $filters['staff_name'];

        // Check if search term contains a space (possible first + last name)
        if (str_contains($searchTerm, ' ')) {
            $nameParts = explode(' ', $searchTerm, 2);
            $query->where(function($subQuery) use ($nameParts) {
                $subQuery->where(function($q) use ($nameParts) {
                        $q->where('first_name', 'like', '%'.$nameParts[0].'%')
                          ->where('last_name', 'like', '%'.$nameParts[1].'%');
                    })
                    ->orWhere(function($q) use ($nameParts) {
                        $q->where('first_name', 'like', '%'.$nameParts[1].'%')
                          ->where('last_name', 'like', '%'.$nameParts[0].'%');
                    });
            });
        } else {
            // Single term search
            $query->where(function($subQuery) use ($searchTerm) {
                $subQuery->where('first_name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('last_name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('email', 'like', '%'.$searchTerm.'%');
            });
        }
    });
}

            // Apply additional filters if any
            $data->applyFilters($filters);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    return view('backend.user.include.__user', compact('row'))->render();
                })                ->editColumn('balance', 'backend.user.include.__total_balance_mt5')
                ->editColumn('equity', 'backend.user.include.__total_equity_mt5')
                ->editColumn('credit', 'backend.user.include.__total_credit_mt5')
                ->addColumn('staff_name', function ($row) {
                    return view('backend.user.include.__staff')->with('staff', $row->staff);
                })
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['username', 'kyc', 'balance', 'equity', 'credit', 'staff_name', 'status', 'action'])
                ->make(true);
        }

        return view('backend.user.active_user');
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

             // Super-Admin sees all disabled users
        if ($loggedInUser->hasRole('Super-Admin')) {
            $data = User::where('status', 0)->latest();
        }
        // If user has permission "show-all-users-by-default-to-staff", show all disabled users
        elseif ($loggedInUser->can('show-all-users-by-default-to-staff')) {
            $data = User::where('status', 0)->latest();
        }
        // Otherwise, show only attached disabled users
        else {
            $attachedUserIds = $loggedInUser->users->pluck('id');

            if ($attachedUserIds->isNotEmpty()) {
                $data = User::where('status', 0)
                    ->whereIn('id', $attachedUserIds)
                    ->latest();
            } else {
                // No attached users = No results
                $data = User::where('id', -1); // Returns an empty dataset
            }
        }
        // Apply staff name filter if present
 if (!empty($filters['staff_name'])) {
    $data->whereHas('staff', function($query) use ($filters) {
        $searchTerm = $filters['staff_name'];

        // Check if search term contains a space (possible first + last name)
        if (str_contains($searchTerm, ' ')) {
            $nameParts = explode(' ', $searchTerm, 2);
            $query->where(function($subQuery) use ($nameParts) {
                $subQuery->where(function($q) use ($nameParts) {
                        $q->where('first_name', 'like', '%'.$nameParts[0].'%')
                          ->where('last_name', 'like', '%'.$nameParts[1].'%');
                    })
                    ->orWhere(function($q) use ($nameParts) {
                        $q->where('first_name', 'like', '%'.$nameParts[1].'%')
                          ->where('last_name', 'like', '%'.$nameParts[0].'%');
                    });
            });
        } else {
            // Single term search
            $query->where(function($subQuery) use ($searchTerm) {
                $subQuery->where('first_name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('last_name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('email', 'like', '%'.$searchTerm.'%');
            });
        }
    });
}
            $data->applyFilters($filters);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    return view('backend.user.include.__user', compact('row'))->render();
                })                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->editColumn('balance', 'backend.user.include.__total_balance_mt5')
                ->editColumn('equity', 'backend.user.include.__total_equity_mt5')
                ->editColumn('credit', 'backend.user.include.__total_credit_mt5')
                ->addColumn('staff_name', function ($row) {
                    return view('backend.user.include.__staff')->with('staff', $row->staff);
                })
                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['username', 'kyc', 'balance', 'equity', 'credit','staff_name', 'status', 'action'])
                ->make(true);
        }

        return view('backend.user.disabled_user');
    }

    public function withBalance(Request $request)
    {
        $loggedInUser = auth()->user();
        $riskProfileTags = RiskProfileTag::all();
        if ($request->ajax()) {
            $realForexAccounts = ForexAccount::where('status', ForexAccountStatus::Ongoing)->pluck('login');
            $forexAccountIds = DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->whereIn('Login', $realForexAccounts)
                ->where('Balance', '>', 0)
                ->pluck('Login');
            $userIds = ForexAccount::whereIn('login', $forexAccountIds)->pluck('user_id');

            // Super-Admin sees all users with balance
            if ($loggedInUser->hasRole('Super-Admin')) {
                $data = User::whereIn('id', $userIds)->latest();
            }
            // If user has permission "show-all-users-by-default-to-staff", show all users with balance
            elseif ($loggedInUser->can('show-all-users-by-default-to-staff')) {
                $data = User::whereIn('id', $userIds)->latest();
            }
            // Otherwise, show only attached users with balance
            else {
                $attachedUserIds = $loggedInUser->users->pluck('id');

                if ($attachedUserIds->isNotEmpty()) {
                    $data = User::whereIn('id', $userIds)
                        ->whereIn('id', $attachedUserIds)
                        ->latest();
                } else {
                    // No attached users = No results
                    $data = User::where('id', -1); // Returns an empty dataset
                }
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
                ->addColumn('staff_name', function ($row) {
                    return view('backend.user.include.__staff')->with('staff', $row->staff);
                })
                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['username', 'kyc', 'status', 'balance', 'equity', 'staff_name', 'credit', 'action'])
                ->make(true);
        }

        return view('backend.user.with_balance', [
            'riskProfileTags' => $riskProfileTags
        ]);
    }


    public function withOutBalance(Request $request)
    {
        $loggedInUser = auth()->user();
        $riskProfileTags = RiskProfileTag::all();
        if ($request->ajax()) {
            $realForexAccounts = ForexAccount::where('status', ForexAccountStatus::Ongoing)->pluck('login');
            $forexAccountIds = DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->whereIn('Login', $realForexAccounts)
                ->where('Balance', '<=', 0)
                ->pluck('Login');
            $userIds = ForexAccount::whereIn('login', $forexAccountIds)->pluck('user_id');

            // Super-Admin sees all users without balance
            if ($loggedInUser->hasRole('Super-Admin')) {
                $data = User::whereIn('id', $userIds)->latest();
            }
            // If user has permission "show-all-users-by-default-to-staff", show all users without balance
            elseif ($loggedInUser->can('show-all-users-by-default-to-staff')) {
                $data = User::whereIn('id', $userIds)->latest();
            }
            // Otherwise, show only attached users without balance
            else {
                $attachedUserIds = $loggedInUser->users->pluck('id');

                if ($attachedUserIds->isNotEmpty()) {
                    $data = User::whereIn('id', $userIds)
                        ->whereIn('id', $attachedUserIds)
                        ->latest();
                } else {
                    // No attached users = No results
                    $data = User::where('id', -1); // Returns an empty dataset
                }
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
                ->addColumn('staff_name', function ($row) {
                    return view('backend.user.include.__staff')->with('staff', $row->staff);
                })
                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['username', 'kyc', 'status', 'balance', 'equity', 'staff_name', 'credit', 'action'])
                ->make(true);
        }

        return view('backend.user.without_balance', [
            'riskProfileTags' => $riskProfileTags
        ]);
    }

    public function gracePeriodUsers(Request $request)
    {
        $loggedInUser = auth()->user();
        $filters = $request->only(['global_search', 'staff_name', 'phone', 'country', 'status', 'created_at', 'tag']);
//        dd($request->all());
        if ($request->ajax()) {

            // Check if the logged-in user is a Super-Admin
            if ($loggedInUser->hasRole('Super-Admin')) {
                $data = User::withoutGlobalScope(ExcludeGracePeriodScope::class)
                    ->where('in_grace_period', true)->latest();
            }
            // If user has permission "show-all-users-by-default-to-staff", show all users
            elseif ($loggedInUser->can('show-all-users-by-default-to-staff')) {
                $data = User::withoutGlobalScope(ExcludeGracePeriodScope::class)
                    ->where('in_grace_period', true)->latest();
            }
            // Otherwise, show only attached users
            else {
                $attachedUserIds = $loggedInUser->users->pluck('id');

                if ($attachedUserIds->isNotEmpty()) {
                    $data = User::withoutGlobalScope(ExcludeGracePeriodScope::class)
                        ->where('in_grace_period', true)->where('status', 1)
                        ->whereIn('id', $attachedUserIds)
                        ->latest();
                } else {
                    // No attached users = No results
                    $data = User::where('id', -1); // Returns an empty dataset
                }
            }
// Apply staff name filter if present
if (!empty($filters['staff_name'])) {
    $data->whereHas('staff', function($query) use ($filters) {
        $searchTerm = $filters['staff_name'];

        // Check if search term contains a space (possible first + last name)
        if (str_contains($searchTerm, ' ')) {
            $nameParts = explode(' ', $searchTerm, 2);
            $query->where(function($subQuery) use ($nameParts) {
                $subQuery->where(function($q) use ($nameParts) {
                        $q->where('first_name', 'like', '%'.$nameParts[0].'%')
                          ->where('last_name', 'like', '%'.$nameParts[1].'%');
                    })
                    ->orWhere(function($q) use ($nameParts) {
                        $q->where('first_name', 'like', '%'.$nameParts[1].'%')
                          ->where('last_name', 'like', '%'.$nameParts[0].'%');
                    });
            });
        } else {
            // Single term search
            $query->where(function($subQuery) use ($searchTerm) {
                $subQuery->where('first_name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('last_name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('email', 'like', '%'.$searchTerm.'%');
            });
        }
    });
}

            // Apply additional filters if any
            $data->applyFilters($filters);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    return view('backend.user.include.__user', compact('row'))->render();
                })
                ->editColumn('balance', 'backend.user.include.__total_balance_mt5')
                ->editColumn('equity', 'backend.user.include.__total_equity_mt5')
                ->editColumn('credit', 'backend.user.include.__total_credit_mt5')
                ->addColumn('staff_name', function ($row) {
                    return view('backend.user.include.__staff')->with('staff', $row->staff);
                })
                ->editColumn('kyc', 'backend.user.include.__kyc')
//                ->editColumn('status', 'backend.user.include.__status')
               ->addColumn('action', 'backend.user.include.__grace_action')
                ->rawColumns(['username', 'kyc', 'balance', 'equity', 'credit', 'staff_name', 'status', 'action'])
                ->make(true);
        }

        return view('backend.user.grace_users');
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

        $referrer = $user->referrer;
        $isPartOfMasterIb = user_meta('is_part_of_master_ib', null, $referrer);

        $globalSchemas = ForexSchema::where('is_global', 1)->where('status', 1)->get();

        if ($referrer && $isPartOfMasterIb) {
            $ibGroup = IbGroup::with('rebateRules.forexSchemas')->find($isPartOfMasterIb);

            $forexSchemas = collect();

            foreach ($ibGroup->rebateRules as $rule) {
                $forexSchemas = $forexSchemas->merge($rule->forexSchemas);
            }

            // Remove duplicates and sort if needed
            $schemas = $forexSchemas->merge($globalSchemas)->unique('id')->sortBy('priority')->values();
        }else {
            $tagNames = $user->riskProfileTags()->pluck('name')->toArray();
            $personalSchemas = ForexSchema::where('status', true)
                ->where(function ($query) use ($tagNames) {
                    $query->whereJsonContains('country', auth()->user()->country)
                        ->orWhereJsonContains('country', 'All')
                        ->orWhere(function ($subQuery) use ($tagNames) {
                            foreach ($tagNames as $tagName) {
                                $subQuery->orWhereJsonContains('tags', $tagName);
                            }
                        });
                })
                ->orderBy('priority', 'asc')
                ->get();

            $schemas = $personalSchemas->merge($globalSchemas)->unique('id')->sortBy('priority')->values();
        }

        $bonuses = Bonus::where('status', '1')->where('last_date', '>=', today())->get();

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
            'ibGroups'
        ));
    }

    public function destroy(Request $request, $id)
    {
        // Fetch the Super-Admin's key from the database (assuming only one Super-Admin exists)
        $superAdmin = Admin::where('name', 'Super Admin')->first();

        // Check if the Super-Admin key exists in the database and the input matches
        if (!$superAdmin || $request->input('admin_key') !== $superAdmin->key) {
            // If the key doesn't match, notify error
            notify()->error('Invalid Super-Admin key. Deletion denied.');
            return redirect()->back();  // Redirect back to the previous page
        }

        // Proceed with deleting the user if the key matches
        $user = User::find($id);

        // Ensure the user exists before attempting to delete
        if ($user) {
            $user->delete();
            notify()->success('User deleted successfully');
        } else {
            notify()->error('User not found.');
        }

        // Redirect to the user listing page after the operation
        return redirect()->route('admin.user.index');
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
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'country' => [Rule::requiredIf($isCountry), 'string', 'max:255'],
            'username' => [Rule::requiredIf($isUsername), 'string', 'max:255', 'unique:users,username,' . $id],
            'phone' => [Rule::requiredIf($isPhone), 'string', 'max:255'],
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
        $data = [
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'country' => $input['country'] ?? $user->country,
            'username' => $input['username'],
            'phone' => $input['phone'] ?? $user->phone,
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

                    if (!($response['success'] && ($response['result']['responseCode'] == 10009))) {
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

                    if (!($response['success'] && ($response['result']['responseCode'] == 10009))) {
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
        return view('backend.user.mail_send_all');
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
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        try {

            $input = [
                'subject' => $request->subject,
                'message' => str_replace(['{', '}'], ['<', '>'], $message),
            ];

            $shortcodes = [
                '[[subject]]' => $input['subject'],
                '[[message]]' => $input['message'],
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];

            if (isset($request->id)) {
                $user = User::find($request->id);

                $shortcodes = array_merge($shortcodes, ['[[full_name]]' => $user->full_name]);

                $this->mailNotify($user->email, 'user_mail', $shortcodes);
            } else {
                $users = User::where('status', 1)->get();

                foreach ($users as $user) {
                    $shortcodes = array_merge($shortcodes, ['[[full_name]]' => $user->full_name]);

                    $this->mailNotify($user->email, 'user_mail', $shortcodes);
                }
            }
            $status = 'success';
            $message = __('Mail Send Successfully');
        } catch (Exception $e) {

            $status = 'warning';
            $message = __('something is wrong');
        }

        notify()->$status($message, $status);

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
            $data = Transaction::where('user_id', $id)
                ->where('type', TxnType::IbBonus->value);

        // Date Range Filter
        if (!empty($request->created_at)) {
            $dates = explode(' to ', $request->created_at);
            if (count($dates) == 2) {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $data->where(function ($query) use ($start, $end) {
                    $query->where(function ($q) use ($start, $end) {
                        $q->whereRaw("JSON_EXTRACT(manual_field_data, '$.time') IS NOT NULL")
                            ->whereBetween(DB::raw("STR_TO_DATE(JSON_UNQUOTE(JSON_EXTRACT(manual_field_data, '$.time')), '%Y-%m-%dT%H:%i:%s.000000Z')"), [$start, $end]);
                    })->orWhereBetween('created_at', [$start, $end]);
                });
            }
        }


        // Field filters
        foreach (['login', 'deal', 'order', 'symbol'] as $field) {
            if (!empty($request->$field)) {
                $value = $request->$field;
//                dd($value);

                if (in_array($field, ['login', 'deal', 'order'])) {
                    $data->whereRaw("CAST(JSON_UNQUOTE(JSON_EXTRACT(manual_field_data, '$.\"$field\"')) AS UNSIGNED) = ?", [$value]);
                } else {
                    $data->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(manual_field_data, '$.\"$field\"')) LIKE ?", ["%$value%"]);
                }
            }
        }

        return Datatables::of($data->latest())
            ->addIndexColumn()
            ->editColumn('status', 'backend.user.include.__txn_status')
            ->editColumn('type', 'backend.user.include.__txn_type')
            ->editColumn('final_amount', 'backend.user.include.__txn_amount')
            ->editColumn('created_at', function ($row) {
                if (!empty($row->manual_field_data) && $row->manual_field_data !== '[]') {
                    $manualData = json_decode($row->manual_field_data, true);
                    if (is_array($manualData) && isset($manualData['time'])) {
                        return \Carbon\Carbon::parse($manualData['time'])->format('M d, Y h:i A');
                    }
                }
                return $row->created_at;
            })
            ->addColumn('action', function ($row) {
                return '<span type="button" data-id="' . $row->id . '" id="deposit-action">
                            <button class="action-btn" data-bs-toggle="tooltip" title="Approval Process">
                                <iconify-icon icon="lucide:eye"></iconify-icon>
                            </button>
                        </span>';
            })
            ->rawColumns(['status', 'created_at','type', 'final_amount', 'action'])
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
        $staffMembers = Admin::all();
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
            $validatedData = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'country' => [Rule::requiredIf($isCountry), 'string', 'max:255'],
                'username' => [Rule::requiredIf($isUsername), 'string', 'max:255', 'unique:users'],
                'phone' => [Rule::requiredIf($isPhone), 'string', 'max:255'],
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
        $location = auth()->user()->location ?? (object) ['country_code' => '', 'dial_code' => ''];

        // Set country and phone depending on settings and input
        $country = $isCountry ? explode(':', $input['country'])[0] : $location->country_code;
        $phone = $isPhone ? (($isCountry ? explode(':', $input['country'])[1] : $location->dial_code) . ' ' . $input['phone']) : $location->dial_code . ' ' . $input['phone'];

        // Generate a username if it’s not provided
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
            $validatedData = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'country' => [Rule::requiredIf($isCountry), 'string', 'max:255'],
                'username' => [Rule::requiredIf($isUsername), 'string', 'max:255', 'unique:users'],
                'phone' => [Rule::requiredIf($isPhone), 'string', 'max:255'],
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
        $phone = $isPhone ? (($isCountry ? explode(':', $input['country'])[1] : $location->dial_code) . ' ' . $input['phone']) : $location->dial_code . ' ' . $input['phone'];

        // Generate a username if it’s not provided
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
        $search = $request->get('q');

        // Fetch users based on the search query, ordered by first name
        $users = User::where('first_name', 'LIKE', "%{$search}%")
            ->orWhere('last_name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get();

        // Format the response for Select2
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
}
