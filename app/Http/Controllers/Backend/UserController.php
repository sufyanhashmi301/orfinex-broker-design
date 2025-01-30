<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ForexAccountStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Exports\ActiveUsersExport;
use App\Exports\DisabledUsersExport;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Jobs\AgentReferralJob;
use App\Models\AccountType;
use App\Enums\AccountType as AccountTypeEnums;
use App\Enums\WalletType;
use App\Models\AccountTypeInvestment;
use App\Models\Country;
use App\Models\CustomerGroup;
use App\Models\ForexAccount;
use App\Models\ForexSchema;
use App\Models\LevelReferral;
use App\Models\RiskProfileTag;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
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
        $this->middleware('permission:customer-list|customer-login|customer-mail-send|customer-basic-manage|customer-change-password|all-type-status', ['only' => ['index', 'activeUser', 'disabled', 'mailSendAll', 'mailSend']]);
        $this->middleware('permission:customer-basic-manage|customer-change-password|all-type-status', ['only' => ['edit']]);
        $this->middleware('permission:customer-login', ['only' => ['userLogin']]);
        $this->middleware('permission:customer-mail-send', ['only' => ['mailSendAll', 'mailSend']]);
        $this->middleware('permission:customer-basic-manage', ['only' => ['update']]);
        $this->middleware('permission:customer-change-password', ['only' => ['passwordUpdate']]);
        $this->middleware('permission:all-type-status', ['only' => ['statusUpdate']]);

        $this->forexApiService = $forexApiService;
    }

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        $users = User::paginate(15);

        return view('backend.user.all', compact('users'));
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
        $all_account_types = AccountType::where('status', 1)->get();
        $accounts = AccountTypeInvestment::where('user_id', $id)->orderBy('id', 'DESC')->paginate(10);
        $all_accounts = AccountTypeInvestment::where('user_id', $id)->orderBy('id', 'DESC')->get();
        $wallets_balance = Wallet::where('user_id', $id)->sum('available_balance');
        $total_approved_withdraws = Transaction::where('user_id', $id)->where('type', 'withdraw')->where('status', TxnStatus::Success)->sum('amount');
        $payout_wallet_balance = Wallet::where('user_id', $id)->where('slug', WalletType::PAYOUT)->first()->available_balance ?? 0.00;
        $affiliate_wallet_balance = Wallet::where('user_id', $id)->where('slug', WalletType::AFFILIATE)->first()->available_balance ?? 0.00;
        $payments = Transaction::where(function ($query) {
                        $query->where('type', TxnType::ManualDeposit)
                            ->orWhere('type', TxnType::Deposit);
                    })->latest()->paginate(10);
        $withdraws = Transaction::where(function ($query) {
                        $query->where('type', TxnType::Withdraw);
                    })->paginate(10);


        return view('backend.user.edit', compact('user', 'accounts', 'all_accounts', 'wallets_balance', 'total_approved_withdraws', 'payout_wallet_balance', 'affiliate_wallet_balance', 'all_account_types', 'payments', 'withdraws'));
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
            'email_verified' => 'required',
            'kyc' => 'required',
            'two_fa' => 'required',
            'deposit_status' => 'required',
            'withdraw_status' => 'required',
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
            'kyc' => $input['kyc'],
            'two_fa' => $input['two_fa'],
            'deposit_status' => $input['deposit_status'],
            'withdraw_status' => $input['withdraw_status'],
            'email_verified_at' => $input['email_verified'] == 1 ? now() : null,
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
            'group_id' => 'sometimes|exists:customer_groups,id'
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
        if (isset($input['group_id']) && $input['group_id'] !== '') {
            $user->customerGroups()->sync($input['group_id']);
        } else {
            $user->customerGroups()->detach();
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
     * @return RedirectResponse
     */
    public function userLogin($id)
    {
        Auth::guard('web')->loginUsingId($id);

        return redirect()->route('user.dashboard');
    }


    public function createCustomer()
    {
        $countries = Country::orderBy('name', 'ASC')->get();

        return view('backend.user.create', compact('countries'));
    }

    public function store(Request $request) {
        $data = $request->all(); 
        $data['password'] = bcrypt($request->password);  
        $data['ranking_id'] = '1';  
        $data['rankings'] = '[1]';  

        User::create($data); 

        notify()->success('User Created Successfully');

        return redirect()->back();
    }

}
