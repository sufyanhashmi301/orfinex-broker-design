<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ForexAccountStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Jobs\AgentReferralJob;
use App\Models\CustomerGroup;
use App\Models\ForexAccount;
use App\Models\LevelReferral;
use App\Models\RiskProfileTag;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ForexApiService;
use App\Traits\ForexApiTrait;
use App\Traits\NotifyTrait;
use Brick\Math\BigDecimal;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Txn;

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
            $data = User::query();

            // Apply filters
            if ($request->has('global_search') && $request->global_search) {
                $search = $request->global_search;
                $data->where(function($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }
            if ($request->has('phone') && $request->phone) {
                $data->where('phone', 'like', "%" . $request->phone . "%");
            }
            if ($request->has('country') && $request->country) {
                $data->where('country', 'like', "%" . $request->country . "%");
            }
            if ($request->has('status') && $request->status !== '') {
                $data->where('status', $request->status);
            }
            if ($request->has('created_at') && $request->created_at) {
                $data->whereDate('created_at', $request->created_at);
            }
            if ($request->has('tag') && $request->tag) {
                $data->where('comment', 'like', "%" . $request->tag . "%");
            }

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

    public function export(Request $request)
    {
        return Excel::download(new UsersExport($request), 'users.xlsx');
    }

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws Exception
     */
    public function activeUser(Request $request)
    {
        if ($request->ajax()) {

            $data = User::where('status', 1)->latest();

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
            $data = User::where('status', 0)->latest();

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
        $customerGroups = CustomerGroup::where('status',1)->get();
        $users = User::where(function ($query) use ($id,$user) {
            $query->where(function ($subquery) use ($id,$user) {
                $subquery->whereNull('ref_id')
                    ->orWhere('ref_id', '<>', $id);
            })
                ->where('id', '<>', $id)
                ->where('id', '<>', $user->ref_id);
        })
            ->get();

        return view('backend.user.edit', compact('user', 'level', 'realForexAccounts', 'tags', 'users','customerGroups'));
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
        
        $input = $request->all();
        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
//            'phone' => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|string|max:255|email|unique:users,email,' . $id,
            'date_of_birth' => 'nullable|date_format:Y-m-d',
            'group_id' => 'nullable|exists:customer_groups,id'
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
        if (empty($input['date_of_birth'])) {
            $input['date_of_birth'] = null;
        }
        User::find($id)->update($input);
        $user = User::find($id);
        if (isset($input['group_id'])) {
            $user->customerGroups()->sync($input['group_id']);
        } else {
            $user->customerGroups()->sync([]);
        }
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
    public function createNote(Request $request,$id)
    {
        $user = User::find($id);
        $user->update(['notes'=>$request->notes]);
        notify()->success('Note added successfully');

        return redirect()->back();
        
    }
}
