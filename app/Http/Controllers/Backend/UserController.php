<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ForexAccountStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Enums\KYCStatus;
use App\Exports\ActiveUsersExport;
use App\Exports\DisabledUsersExport;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Jobs\AgentReferralJob;
use App\Models\CustomerGroup;
use App\Models\ForexAccount;
use App\Models\ForexSchema;
use App\Models\LevelReferral;
use App\Models\RiskProfileTag;
use App\Models\Transaction;
use App\Models\User;
use App\Models\KycLevel;
use App\Services\ForexApiService;
use App\Traits\ForexApiTrait;
use App\Traits\NotifyTrait;
use Brick\Math\BigDecimal;
use App\Models\Kyc;
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

class UserController extends Controller
{  
    use NotifyTrait, ForexApiTrait;
    protected $forexApiService;
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function __construct(ForexApiService $forexApiService)
    {
        $this->middleware('permission:customer-list|customer-login|customer-mail-send|customer-basic-manage|customer-change-password|all-type-status|customer-balance-add-or-subtract', ['only' => ['index', 'activeUser', 'disabled', 'mailSendAll', 'mailSend']]);
        $this->middleware('permission:customer-basic-manage|customer-change-password|all-type-status|customer-balance-add-or-subtract', ['only' => ['edit']]);
        $this->middleware('permission:customer-login', ['only' => ['userLogin']]);
        $this->middleware('permission:customer-mail-send', ['only' => ['mailSendAll', 'mailSend']]);
        $this->middleware('permission:customer-basic-manage', ['only' => ['update']]);
        $this->middleware('permission:customer-change-password', ['only' => ['passwordUpdate']]);
        $this->middleware('permission:all-type-status', ['only' => ['statusUpdate']]);
        $this->middleware('permission:customer-balance-add-or-subtract', ['only' => ['balanceUpdate']]);
        $this->forexApiService = $forexApiService;
    }

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
          $filters = $request->only(['global_search', 'phone', 'country', 'status', 'created_at', 'tag']);
            $data = User::applyFilters($filters);

            return Datatables::of($data)
                ->addIndexColumn()

                ->editColumn('avatar', 'backend.user.include.__avatar')
                ->addColumn('username', 'backend.user.include.__user')
                ->addColumn('email', 'backend.user.include.__email')
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->editColumn('balance', 'backend.user.include.__total_balance_mt5')
                ->editColumn('equity', 'backend.user.include.__total_equity_mt5')
                ->editColumn('credit', 'backend.user.include.__total_credit_mt5')
                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['avatar', 'username','email','kyc', 'balance','equity','credit', 'status', 'action'])
                ->make(true);
        }

        return view('backend.user.all');
    }

    public function export(Request $request, $type)
    {
        switch ($type) {
            case 'active':
                return Excel::download(new ActiveUsersExport($request), 'active-users.xlsx');
            case 'disabled':
                return Excel::download(new DisabledUsersExport($request), 'disabled-users.xlsx');
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

        if ($request->ajax()) {
            $filters = $request->only(['global_search', 'phone', 'country', 'status', 'created_at', 'tag']);
            $data = User::where('status', 1)->latest();
            $data->applyFilters($filters);
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('avatar', 'backend.user.include.__avatar')
                ->editColumn('username', 'backend.user.include.__user')
                ->editColumn('email', 'backend.user.include.__email')
                ->editColumn('balance', 'backend.user.include.__total_balance_mt5')
                ->editColumn('equity', 'backend.user.include.__total_equity_mt5')
                ->editColumn('credit', 'backend.user.include.__total_credit_mt5')
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['avatar','username','email', 'kyc', 'balance','equity','credit','status', 'action'])
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
        if ($request->ajax()) {
            $filters = $request->only(['global_search', 'phone', 'country', 'status', 'created_at', 'tag']);
            $data = User::where('status', 0)->latest();
            $data->applyFilters( $filters);
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('avatar', 'backend.user.include.__avatar')
                ->addColumn('username', 'backend.user.include.__user')
                ->addColumn('email', 'backend.user.include.__email')
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->editColumn('balance', 'backend.user.include.__total_balance_mt5')
                ->editColumn('equity', 'backend.user.include.__total_equity_mt5')
                ->editColumn('credit', 'backend.user.include.__total_credit_mt5')
                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['avatar','username','email', 'kyc','balance','equity','credit', 'status', 'action'])
                ->make(true);
        }

        return view('backend.user.disabled_user');
    }
    public function withBalance(Request $request)
    {
        if ($request->ajax()) {
            $realForexAccounts = ForexAccount::where('status', ForexAccountStatus::Ongoing)->pluck('login');
            $forexAccountIds = DB::connection('mt5_db')
            ->table('mt5_accounts')
            ->whereIn('Login', $realForexAccounts)
            ->where('Balance', '>',0)
            ->pluck('Login');
            $userIds = ForexAccount::whereIn('login', $forexAccountIds)->pluck('user_id');
            $data = User::whereIn('id', $userIds)->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('avatar', 'backend.user.include.__avatar')
                ->addColumn('username', 'backend.user.include.__user')
                ->addColumn('email', 'backend.user.include.__email')
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->editColumn('balance', 'backend.user.include.__total_balance_mt5')
                ->editColumn('equity', 'backend.user.include.__total_equity_mt5')
                ->editColumn('credit', 'backend.user.include.__total_credit_mt5')
                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['avatar','username','email', 'kyc', 'status','balance','equity','credit', 'action'])
                ->make(true);
        }

        return view('backend.user.with_balance');
    }
    public function withOutBalance(Request $request)
    {
        if ($request->ajax()) {
            $realForexAccounts = ForexAccount::where('status', ForexAccountStatus::Ongoing)->pluck('login');
            $forexAccountIds = DB::connection('mt5_db')
            ->table('mt5_accounts')
            ->whereIn('Login', $realForexAccounts)
            ->where('Balance', '<=',0)
            ->pluck('Login');

        $userIds = ForexAccount::whereIn('login', $forexAccountIds)->pluck('user_id');
        $data = User::whereIn('id', $userIds)->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('avatar', 'backend.user.include.__avatar')
                ->addColumn('username', 'backend.user.include.__user')
                ->addColumn('email', 'backend.user.include.__email')
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('status', 'backend.user.include.__status')
                ->editColumn('balance', 'backend.user.include.__total_balance_mt5')
                ->editColumn('equity', 'backend.user.include.__total_equity_mt5')
                ->editColumn('credit', 'backend.user.include.__total_credit_mt5')
                ->addColumn('action', 'backend.user.include.__action')
                ->rawColumns(['avatar','username','email', 'kyc', 'status','balance','equity','credit', 'action'])
                ->make(true);
        }

        return view('backend.user.without_balance');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {

        $user = User::find($id);
        $level = LevelReferral::where('type', 'investment')->max('the_order') + 1;
        $realForexAccounts = ForexAccount::realActiveAccount($id)
            ->orderBy('balance', 'desc')
            ->get();
        $tags = RiskProfileTag::where('status', true)
            ->get();
            $countries = getCountries();
        $customerGroups = CustomerGroup::where('status',1)->get();
         $riskProfileTags = RiskProfileTag::all();
         $kycLevels = KycLevel::where('status', 1)->get();
         $kycStatus = KYCStatus::cases();
//        $users = User::where('id', '<>', $id)
//            ->where(function ($query) use ($id, $user) {
//                $query->whereNull('ref_id')
//                    ->orWhere('ref_id', '<>', $id);
//            })
//            ->where('id', '<>', $user->ref_id)
//            ->get();

        $tagNames = $user->riskProfileTags()->pluck('name')->toArray();
        $schemas = ForexSchema::where('status', true)
            ->where(function($query) use ($tagNames) {
                $query->whereJsonContains('country', auth()->user()->country)
                    ->orWhereJsonContains('country', 'All')
                    ->orWhere(function($subQuery) use ($tagNames) {
                        foreach ($tagNames as $tagName) {
                            $subQuery->orWhereJsonContains('tags', $tagName);
                        }
                    });
            })
            ->orderBy('priority', 'asc')
            ->get();
        return view('backend.user.edit', compact('user', 'level', 'realForexAccounts', 'tags','customerGroups', 'schemas', 'riskProfileTags','countries', 'kycLevels', 'kycStatus'));

    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['success' => 'Successfully deleted!']);
    }

    /**
     * @return RedirectResponse
     */
    public function statusUpdate($id, Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'status' => 'required',
            'is_multi_ib' => 'required',
            'email_verified' => 'required',
            'kyc' => 'required',
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
            'is_multi_ib' => $input['is_multi_ib'],
            'kyc' => $input['kyc'],
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
            'status' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    // Handle KYC Levels or KYC Status enum values
                    if (Str::startsWith($value, 'status_')) {
                        $statusValue = str_replace('status_', '', $value);
                        if (!in_array($statusValue, array_column(KYCStatus::cases(), 'value'))) {
                            $fail('The selected KYC status is invalid.');
                        }
                    } elseif (!KycLevel::where('id', $value)->exists()) {
                        $fail('The selected KYC level is invalid.');
                    }
                },
            ],
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
            'status',
            'risk_profile_tags',
            'comment',
            'password',
        ]);
    
        // Handle the status value (KYC Level or KYC Status)
        $status = $input['status'];
        if (Str::startsWith($status, 'status_')) {
            $status = str_replace('status_', '', $status); // KYC Status
        }
    
        // Update basic user details
        $user->update([
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
            'status' => $status ?? $user->status,
            'email_verified_at' => $request->has('is_email_verified') ? now() : $user->email_verified_at,
        ]);
    
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
    
    
    /**
     * @return RedirectResponse
     */
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

        User::find($id)->update([
            'password' => Hash::make($password['new_password']),
        ]);
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
            'amount' => 'required',
            'type' => 'required',
            'target_type' => 'required',
            'comment' => 'required',
        ]);
        $targetId = $request->input('target_id');
        $targetType = $request->input('target_type');
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        try {
//dd($request->all());
            $amount = $request->amount;
            $type = $request->type;
            $comment = $request->comment;

            $user = User::find($id);
            $adminUser = \Auth::user();

            if ($type == 'add') {
                if ($targetType == 'forex') {
                    $data = [
                        'login' => $targetId,
                        'Amount' => $amount,
                        'type' => 1,//deposit
                        'TransactionComments' => $comment
                    ];
                     $this->forexApiService->balanceOperation($data);
                }
                Txn::new($amount, 0, $amount, 'system', 'Money added in ' . $targetId . ' Account from System', TxnType::Deposit, TxnStatus::Success, null, null, $id, $adminUser->id, 'Admin', [], $comment, $targetId, $targetType);

                $status = 'success';
                $message = __('Account Balance Update');

            } elseif ($type == 'subtract') {
                if ($targetType == 'forex') {
                    $balance = $this->forexApiService->getValidatedBalance([
                        'login' => $targetId
                    ]);
//                    $balance = $this->getForexAccountBalance($targetId);
                    if (BigDecimal::of($amount)->compareTo($balance) > 0) {
                        notify()->error(__("Sorry, you don't have sufficient funds in your account to complete this action. Please add funds to proceed."), 'Error');
                        return redirect()->back();
                    }
                    $data = [
                        'login' => $targetId,
                        'Amount' => $amount,
                        'type' => 2,//withdraw
                        'TransactionComments' => $comment
                    ];
                    $withdrawResponse = $this->forexApiService->balanceOperation($data);
//                    $withdrawResponse = $this->forexWithdraw($targetId, $amount, $comment);
                    if(!$withdrawResponse['success']){
                        return redirect()->back();
                    }
                }
                Txn::new($amount, 0, $amount, 'system', 'Money subtract in ' . $targetId . ' Account from System', TxnType::Subtract, TxnStatus::Success, null, null, $id, $adminUser->id, 'Admin', [], $comment, $targetId, $targetType);
                $status = 'success';
                $message = __('Account Balance Updated');
            }

            notify()->success($message, $status);

            return redirect()->back();

        } catch (Exception $e) {
            $status = 'warning';
            $message = __('something is wrong');
            $code = 503;
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
                'message' => $request->message,
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
            $data = Transaction::where('user_id', $id)->latest();

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', 'backend.user.include.__txn_status')
                ->editColumn('type', 'backend.user.include.__txn_type')
                ->editColumn('final_amount', 'backend.user.include.__txn_amount')
                ->rawColumns(['status', 'type', 'final_amount'])
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
                ->rawColumns(['ib_status','action'])
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

    return view('backend.user.create', compact('location', 'countries', 'riskProfileTags','kycLevels','kycStatus','kycs'));
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
            'status' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    // Handle KYC Levels or KYC Status enum values
                    if (Str::startsWith($value, 'status_')) {
                        $statusValue = str_replace('status_', '', $value);
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
        'status',
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
    $status = $input['status'];
    if (Str::startsWith($status, 'status_')) {
        $status = (int) str_replace('status_', '', $status); // Remove the prefix and convert to integer
    } else {
        $status = (int) $status; // Ensure it's an integer for KYC Level ID
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
            'status' => $status,
        ]);

        // Handle risk profile tags
        if (isset($input['risk_profile_tags']) && is_array($input['risk_profile_tags'])) {
            $user->riskProfileTags()->attach($input['risk_profile_tags']);
        }

    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'User creation failed: ' . $e->getMessage()])->withInput();
    }

    // Redirect to the user index with success message
    return redirect()->route('admin.user.index')->with('success', 'Customer created successfully');
}


     public function createNote(Request $request,$id)
     {
         $user = User::find($id);
         $user->update(['notes' => $request->notes]);
         notify()->success('Note added successfully');

         return redirect()->back();
     }

}
