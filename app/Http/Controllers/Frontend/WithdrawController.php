<?php

namespace App\Http\Controllers\Frontend;

use Txn;
use Session;
use Validator;
use Carbon\Carbon;
use App\Enums\TxnType;
use App\Models\Wallet;
use App\Traits\Payment;
use App\Enums\TxnStatus;
use App\Enums\WalletType;
use Brick\Math\BigDecimal;
use App\Models\Transaction;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use App\Models\ForexAccount;
use Illuminate\Http\Request;
use App\Models\FundedBalance;
use App\Traits\ForexApiTrait;
use App\Models\WithdrawMethod;
use App\Enums\InvestmentStatus;
use App\Models\WithdrawAccount;
use App\Services\PayoutService;
use Illuminate\Validation\Rule;
use App\Enums\ForexAccountStatus;
use App\Enums\PayoutRequestStatus;
use App\Services\ForexApiService;
use App\Models\WithdrawalSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;
use App\Models\PayoutRequest;
use App\Models\UserAffiliate;
use Illuminate\Http\RedirectResponse;
use App\Rules\ForexLoginBelongsToUser;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class WithdrawController extends Controller
{
    use ImageUpload, NotifyTrait, Payment, ForexApiTrait;
    protected $forexApiService;
    protected $payout;

    public function __construct(ForexApiService $forexApiService, PayoutService $payout)
    {
        $this->forexApiService = $forexApiService;
        $this->payout = $payout;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $accounts = WithdrawAccount::where('user_id', auth()->id())->get();

        return view('frontend::withdraw.account.index', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'withdraw_method_id' => 'required',
            'method_name' => 'required',
            'credentials' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        $credentials = $input['credentials'];
        foreach ($credentials as $key => $value) {

            if (is_file($value['value'])) {
                $credentials[$key]['value'] = self::imageUploadTrait($value['value']);
            }
        }

        $data = [
            'user_id' => auth()->id(),
            'withdraw_method_id' => $input['withdraw_method_id'],
            'method_name' => $input['method_name'],
            'credentials' => json_encode($credentials),
        ];

        WithdrawAccount::create($data);

        notify()->success('Successfully Withdraw Account Created', 'success');

        return redirect()->route('user.withdraw.account.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $withdrawMethods = WithdrawMethod::where('status', true)
            ->where(function ($query) {
                $query->whereJsonContains('country', auth()->user()->country)
                    ->orWhereJsonContains('country', 'All');
            })->get();


        return view('frontend::withdraw.account.create', compact('withdrawMethods'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $withdrawMethods = WithdrawMethod::where(function ($query) {
            $query->whereJsonContains('country', auth()->user()->country)
                ->orWhereJsonContains('country', 'All');
        })->get();
        $withdrawAccount = WithdrawAccount::where('id', get_hash($id))->where('user_id', auth()->user()->id)->first();
        if ($withdrawAccount) {
            return view('frontend::withdraw.account.edit', compact('withdrawMethods', 'withdrawAccount'));
        }
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'withdraw_method_id' => 'required',
            'method_name' => 'required',
            'credentials' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        $withdrawAccount = WithdrawAccount::find($id);

        $oldCredentials = json_decode($withdrawAccount->credentials, true);

        $credentials = $input['credentials'];
        foreach ($credentials as $key => $value) {

            if (!isset($value['value'])) {
                $credentials[$key]['value'] = $oldCredentials[$key]['value'];
            }

            if (isset($value['value']) && is_file($value['value'])) {
                $credentials[$key]['value'] = self::imageUploadTrait($value['value'], $oldCredentials[$key]['value']);
            }
        }

        $data = [
            'user_id' => auth()->id(),
            'withdraw_method_id' => $input['withdraw_method_id'],
            'method_name' => $input['method_name'],
            'credentials' => json_encode($credentials),
        ];

        $withdrawAccount->update($data);
        notify()->success('Successfully Withdraw Account Updated', 'success');

        return redirect()->route('user.withdraw.account.index');
    }

    /**
     * @return string
     */
    public function withdrawMethod($id)
    {
        $withdrawMethod = WithdrawMethod::find($id);

        if ($withdrawMethod) {
            return view('frontend::withdraw.include.__account', compact('withdrawMethod'))->render();
        }

        return '';
    }

    /**
     * @return array
     */
    public function details($accountId, int $amount = 0)
    {

        $withdrawAccount = WithdrawAccount::find($accountId);

        $credentials = json_decode($withdrawAccount->credentials, true);

        $currency = setting('site_currency', 'global');
        $method = $withdrawAccount->method;
        $charge = $method->charge;
        $name = $withdrawAccount->method_name;
        $processingTime = (int)$method->required_time > 0 ? 'Processing Time: ' . $withdrawAccount->method->required_time . ' ' . $withdrawAccount->method->required_time_format : 'This Is Automatic Method';

        $info = [
            'name' => $name,
            'charge' => $charge,
            'charge_type' => $withdrawAccount->method->charge_type,
            'range' => 'Minimum ' . $method->min_withdraw . ' ' . $currency . ' and ' . 'Maximum ' . $method->max_withdraw . ' ' . $currency,
            'processing_time' => $processingTime,
            'rate' => $method->rate,
            'pay_currency' => $method->currency
        ];

        if ($withdrawAccount->method->charge_type != 'fixed') {
            $charge = ($charge / 100) * $amount;
        }
        $conversionRate = $method->currency != $currency ? $method->rate . ' ' . $method->currency : null;
        $html = view('frontend::withdraw.include.__details', compact('credentials', 'name', 'charge', 'conversionRate'))->render();

        return [
            'html' => $html,
            'info' => $info,
        ];
    }

    /**
     * @return string
     */
    public function withdrawNow(Request $request)
    {

        //        notify()->error(__('Withdrawals are currently disabled for a short period. We apologize for any inconvenience and will be back soon'), 'Error');
        //        return redirect()->back();
        if (!setting('user_withdraw', 'permission') || !\Auth::user()->withdraw_status) {
            abort('403', __('Withdraw Disable Now'));
        }

        $withdrawOffDays = WithdrawalSchedule::where('status', 0)->pluck('name')->toArray();
        $date = Carbon::now();
        $today = $date->format('l');

        if (in_array($today, $withdrawOffDays)) {
            abort('403', __('Today is the off day of withdraw'));
        }
        $input = $request->all();
        //        dd($input);
        $validator = Validator::make($input, [
            'target_id' => ['required', 'integer', new ForexLoginBelongsToUser],
            'withdraw_account' => ['required'],
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9]{1,4})?$/'],

        ], [
            'target_id.required' => __('Kindly select the account to withdraw'),
            //            'target_id.exists' => 'The selected account from does not exist or is not of type real.',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }
        $user = Auth::user();
        //daily limit
        $todayTransaction = Transaction::where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('type', TxnType::Withdraw)
                    ->orWhere('type', TxnType::WithdrawAuto);
            })
            ->whereDate('created_at', Carbon::today())
            ->count();
        $dayLimit = (float)Setting('withdraw_day_limit', 'fee');
        if ($todayTransaction >= $dayLimit) {
            notify()->error(__('Today Withdraw limit has been reached'), 'Error');
            return redirect()->back();
        }

        $input = $request->all();
        $amount = (float)$input['amount'];

        $withdrawAccount = WithdrawAccount::find($input['withdraw_account']);
        $withdrawMethod = $withdrawAccount->method;

        if ($amount < $withdrawMethod->min_withdraw || $amount > $withdrawMethod->max_withdraw) {
            $currencySymbol = setting('currency_symbol', 'global');
            $message = 'Please Withdraw the Amount within the range ' . $currencySymbol . $withdrawMethod->min_withdraw . ' to ' . $currencySymbol . $withdrawMethod->max_withdraw;
            notify()->error($message, 'Error');
            return redirect()->back();
        }

        $charge = $withdrawMethod->charge_type == 'percentage' ? (($withdrawMethod->charge / 100) * $amount) : $withdrawMethod->charge;
        $totalAmount = BigDecimal::of($amount)->abs();


        $targetId = $input['target_id'];

        $balance = $this->forexApiService->getValidatedBalance([
            'login' => $targetId
        ]);
        if ($totalAmount->compareTo($balance) > 0) {
            notify()->error(__('Insufficient Balance Your Account'), 'Error');
            return redirect()->back();
        }
        $totalAmount = $totalAmount->toFloat();
        $payAmount = ($amount * $withdrawMethod->rate) - ($charge * $withdrawMethod->rate);

        $type = $withdrawMethod->type == 'auto' ? TxnType::WithdrawAuto : TxnType::Withdraw;

        $targetType = 'forex_withdraw';
        $txnInfo = Txn::new(
            $input['amount'],
            $charge,
            $totalAmount,
            $withdrawMethod->name,
            'Withdraw With ' . $withdrawAccount->method_name,
            $type,
            TxnStatus::None,
            $withdrawMethod->currency,
            $payAmount,
            $user->id,
            null,
            'User',
            json_decode($withdrawAccount->credentials, true),
            'none',
            $targetId,
            $targetType
        );

        //
        //        dd(setting('withdraw_deduction', 'features'));
        if (!setting('withdraw_deduction', 'features')) {
            Txn::update($txnInfo->tnx, TxnStatus::Pending, $txnInfo->user_id, 'Pending Request');
        } else {
            $comment = $withdrawMethod->name . '/' . substr($txnInfo->tnx, -7);
            $data = [
                'login' => $targetId,
                'Amount' => $totalAmount,
                'type' => 2, //withdraw
                'TransactionComments' => $comment
            ];
            $withdrawResponse = $this->forexApiService->balanceOperation($data);
            if ($withdrawResponse['success']) {
                Txn::update($txnInfo->tnx, TxnStatus::Pending, $txnInfo->user_id, 'Pending Request');
            } else {
                Txn::update($txnInfo->tnx, TxnStatus::Failed, $txnInfo->user_id, 'Insufficient Withdrawable Balance');
                return redirect()->back();
            }
        }

        //        dd($withdrawMethod->type);
        if ($withdrawMethod->type == 'auto') {
            $gatewayCode = $withdrawMethod->gateway->gateway_code;
            return self::withdrawAutoGateway($gatewayCode, $txnInfo);
        }

        $symbol = setting('currency_symbol', 'global');
        $notify = [
            'card-header' => 'Withdraw Money',
            'title' => $symbol . $txnInfo->amount . ' Withdraw Request Successful',
            'p' => 'The Withdraw Request has been successfully sent',
            'strong' => 'Transaction ID: ' . $txnInfo->tnx,
            'action' => route('user.withdraw.view'),
            'a' => 'WITHDRAW REQUEST AGAIN',
            'view_name' => 'withdraw',
        ];
        Session::put('user_notify', $notify);
        $shortcodes = [
            '[[full_name]]' => $txnInfo->user->full_name,
            '[[txn]]' => $txnInfo->tnx,
            '[[method_name]]' => $withdrawMethod->name,
            '[[withdraw_amount]]' => $txnInfo->amount . setting('site_currency', 'global'),
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];
        $this->mailNotify($user->email, 'withdraw_request_user', $shortcodes);
        $this->mailNotify(setting('site_email', 'global'), 'withdraw_request', $shortcodes);
        $this->pushNotify('withdraw_request', $shortcodes, route('admin.withdraw.pending'), $user->id);
        $this->smsNotify('withdraw_request', $shortcodes, $user->phone);

        return redirect()->route('user.notify');
    }

    public function WithdrawApiCall($login, $totalAmount)
    {
        $withdrawUrl = config('forextrading.withdrawUrl');
        $auth = config('forextrading.auth');

        $dataArray = [
            'Login' => $login,
            'Withdraw' => $totalAmount,
            'Comment' => "Withdraw/USD",

        ];
        //        dd($userAccount,$dataArray);
        $withdrawResponse = $this->sendApiRequest($withdrawUrl, $dataArray);
        //        dd($userAccount,$withdrawResponse);
        if ($withdrawResponse ? $withdrawResponse->status() == 200 && $withdrawResponse->object()->data == 0 : false) {
            return true;
        }
    }

    /**
     * Payout request
     */
    public function payoutRequest(Request $request) {

        // $reset_balance_data = [
        //     'login' =>'996466',
        //     'Amount' => '24000',
        //     'type' => 1, //deposit
        //     'TransactionComments' => 'Balance Reset'
        // ];
      
        // $response = $this->forexApiService->balanceOperation($reset_balance_data);
        // dd('hi');

        // if wallets dont exist then return false
        $payout_wallet = Wallet::where('user_id', Auth::id())->where('slug', WalletType::PAYOUT);
        $affiliate_wallet = Wallet::where('user_id', Auth::id())->where('slug', WalletType::AFFILIATE);

        if(!$payout_wallet->exists() || !$affiliate_wallet->exists()){
            notify()->error('Error submitting the request. Please try again later.', 'Error');
            return redirect()->back();
        }

        // All eligible funded balances record.
        $funded_balances = FundedBalance::where('user_id', Auth::id())->whereRaw('profit - payout_pending != 0')->get();

        $total_user_profit_share_amount = 0;
        $detail = [];
        $i = 0;
        foreach($funded_balances as $fb) {

            $net_profit = $fb->profit - $fb->payout_pending;

            $user_profit_share_amount = ( $net_profit * $fb->user_profit_share ) / 100;
            $total_user_profit_share_amount += $user_profit_share_amount;

            $detail[$i]['account_id'] = $fb->accountTypeInvestment->id; 
            $detail[$i]['login'] = $fb->accountTypeInvestment->login; 
            $detail[$i]['total_profit'] = $net_profit; 
            $detail[$i]['user_profit_percentage'] = $fb->user_profit_share; 
            $detail[$i]['user_profit_share_amount'] = $user_profit_share_amount; 
        }

        // Temper handling
        if($total_user_profit_share_amount == 0){
            notify()->error('No Available Profit', 'Error');
            return redirect()->back();
        }

        // Reset the funded balances to 0 but not at the api level and also not the stats
        // Add the amount to payout_pending of each funded balance 
        foreach($funded_balances as $fb) {
            $net_profit = $fb->profit - $fb->payout_pending;

            // Add to payout_pending
            $fb->payout_pending = $fb->payout_pending + $net_profit;
            $fb->save();

            // $reset_balance_data = [
            //     'login' => $fb->accountTypeInvestment->login,
            //     'Amount' => $fb->profit,
            //     'type' => 0, //deposit
            //     'TransactionComments' => 'Balance Reset'
            // ];
          
            // $response = $this->forexApiService->balanceOperation($reset_balance_data);
            
            // 0 the funded balances of all accounts of Auth::id() user and Stats
            // stats
            // $fb->accountTypeInvestment->accountTypeInvestmentStat->balance = $fb->accountTypeInvestment->accountTypeInvestmentStat->balance - $fb->profit;
            // $fb->accountTypeInvestment->accountTypeInvestmentStat->current_equity = $fb->accountTypeInvestment->accountTypeInvestmentStat->current_equity - $fb->profit;
            // $fb->accountTypeInvestment->accountTypeInvestmentStat->save();
            
            // funded balances
            // $funded_balance = FundedBalance::find($fb->id);
            // $fb->profit = 0.00;
            // $fb->last_retrieved_profit = 0.00;
            // $fb->save();
        }

        // create new payout request
        $payout_request = new PayoutRequest();
        $payout_request->user_id = Auth::id();
        $payout_request->wallet_unique_id = $request->wallet_unique_id;
        $payout_request->user_profit_share_amount = $total_user_profit_share_amount;
        $payout_request->detail = $detail;
        $payout_request->status = PayoutRequestStatus::PENDING;
        $payout_request->save();

        // Create transaction. maybe?

        notify('Payout request has been submitted!', 'Success');
        return redirect()->back();   

    }


    /**
     * Step 1 
     */
    public function step1Index()
    {

        // Payout Wallet Create if not exists
        $payout_wallet = Wallet::where('user_id', Auth::id())->where('slug', WalletType::PAYOUT);
        if (!$payout_wallet->exists()) {
            $payout_wallet = $this->payout->createNewWallet(WalletType::PAYOUT);
        }

        // Affiliate Wallet Create if not exists
        $affiliate_wallet = Wallet::where('user_id', Auth::id())->where('slug', WalletType::AFFILIATE);
        if (!$affiliate_wallet->exists()) {
            $affiliate_wallet = $this->payout->createNewWallet(WalletType::AFFILIATE);
        }

        // All the eligible funded balances entries created/updated
        $this->payout->updateAllFundedBalance(Auth::id());

        // Update affiliate wallet
        $user_affiliate_row = UserAffiliate::where('user_id', Auth::id());
        if($user_affiliate_row->exists()) {
            $affiliate_wallet->available_balance = $user_affiliate_row->first()->total_commission;
            $affiliate_wallet->save();
        }
        

        // All eligible funded balances record.
        $funded_balances = FundedBalance::where('user_id', Auth::id())->whereRaw('profit - payout_pending != 0')->get();
        
        return view('frontend::withdraw.step1', compact('payout_wallet', 'affiliate_wallet', 'funded_balances'));
    }

    /**
     * @return Application|Factory|View
     */
    public function withdraw()
    {
        $accounts = WithdrawAccount::where('user_id', \Auth::id())->get();
        $accounts = $accounts->reject(function ($value, $key) {
            return !$value->method->status;
        });
        $forexAccounts = ForexAccount::with('schema')
            ->where('user_id', auth()->id())
            ->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)
            ->orderBy('id', 'desc')
            ->get();

        return view('frontend::withdraw.step2', compact('accounts', 'forexAccounts'));
    }

    public function withdrawLog()
    {
        $withdraws = Transaction::search(request('query'), function ($query) {
            $query->where('user_id', auth()->user()->id)
                ->where('type', TxnType::Withdraw)
                ->when(request('date'), function ($query) {
                    $query->whereDay('created_at', '=', Carbon::parse(request('date'))->format('d'));
                });
        })->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('frontend::withdraw.log', compact('withdraws'));
    }
}
