<?php

namespace App\Http\Controllers\Frontend;

use Validator;
use Carbon\Carbon;
use App\Enums\TxnType;
use App\Models\Wallet;
use App\Traits\Payment;
use App\Enums\WalletType;
use App\Models\Transaction;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Illuminate\Http\Request;
use App\Models\FundedBalance;
use App\Models\PayoutRequest;
use App\Models\UserAffiliate;
use App\Traits\ForexApiTrait;
use App\Models\WithdrawMethod;
use App\Models\WithdrawAccount;
use App\Services\PayoutService;
use App\Services\ForexApiService;
use App\Enums\PayoutRequestStatus;
use App\Enums\KycNoticeInvokeEnums;
use App\Enums\KycStatusEnums;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\UserWithdrawService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Http\Requests\UserWithdrawRequest;
use Illuminate\Contracts\Foundation\Application;

class WithdrawController extends Controller
{
    use ImageUpload, NotifyTrait, Payment, ForexApiTrait;
    protected $forexApiService;
    protected $payout;
    protected $withdraw;

    public function __construct(ForexApiService $forexApiService, PayoutService $payout, UserWithdrawService $withdraw)
    {
        $this->forexApiService = $forexApiService;
        $this->payout = $payout;
        $this->withdraw = $withdraw;
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
                $credentials[$key]['value'] = self::imageUploadTrait($value['value'], null, 'user/withdraws/' . Auth::id());
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
                $credentials[$key]['value'] = self::imageUploadTrait($value['value'], $oldCredentials[$key]['value'], 'user/withdraws/' . Auth::id());
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
        $processingTime = (int)$method->required_time > 0 ? $withdrawAccount->method->required_time . ' ' . $withdrawAccount->method->required_time_format : 'Automatic';

        $info = [
            'name' => $name,
            'charge' => $charge,
            'charge_type' => $withdrawAccount->method->charge_type,
            'min_withdraw' => number_format($method->min_withdraw, 2) . ' ' . $currency,
            'max_withdraw' => number_format($method->max_withdraw, 2) . ' ' . $currency,
            'processing_time' => $processingTime,
            'rate' => $method->rate,
            'pay_currency' => $method->currency
        ];

        if ($withdrawAccount->method->charge_type != 'fixed') {
            $charge = ($charge / 100) * $amount;
        }
        $conversionRate = $method->currency != $currency ? $method->rate . ' ' . $method->currency : null;
        $html = view('frontend::withdraw.include.__details', compact('credentials', 'name', 'charge', 'conversionRate', 'processingTime'))->render();

        return [
            'html' => $html,
            'info' => $info,
        ];
    }

    /**
     * @return string
     */
    public function withdrawNow(UserWithdrawRequest $request)
    {
        $input = $request->validated();
        
        // Checking Amount range
        $withdraw_service = $this->withdraw->main($input);
        if($withdraw_service['redirect_back']) {
            return redirect()->back();
        }

        return redirect()->route('user.withdraw.success', ['transaction_id' => $withdraw_service['transaction']->id ]);
    }

    public function success(Request $request) {
        $transaction = Transaction::findOrFail($request->transaction_id);
        return view('frontend::withdraw.success', ['transaction' => $transaction]);
    }

    /**
     * Payout request
     */
    public function payoutRequest(Request $request) {
        $user = Auth::user();
        if($user->kyc->status == KycStatusEnums::UNVERIFIED && kyc_invoke_at() != 'none') {
            return redirect()->route('user.verification.index');
        }

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
        $user = Auth::user();
        $kyc_check_exists = $user->kyc->status == KycStatusEnums::UNVERIFIED && kyc_invoke_at() != 'none' ? true : false;

        // Payout Wallet Create if not exists
        $payout_wallet = Wallet::where('user_id', Auth::id())->where('slug', WalletType::PAYOUT);
        if (!$payout_wallet->exists()) {
            $payout_wallet = $this->payout->createNewWallet(WalletType::PAYOUT);
        } else {
            $payout_wallet = $payout_wallet->first();
        }

        // Affiliate Wallet Create if not exists
        $affiliate_wallet = Wallet::where('user_id', Auth::id())->where('slug', WalletType::AFFILIATE);
        if (!$affiliate_wallet->exists()) {
            $affiliate_wallet = $this->payout->createNewWallet(WalletType::AFFILIATE);
        } else { 
            $affiliate_wallet = $affiliate_wallet->first();
        }

        // All the eligible funded balances entries created/updated
        $this->payout->updateAllFundedBalance(Auth::id());

        // Update affiliate wallet
        $user_affiliate_row = UserAffiliate::where('user_id', Auth::id());
        if($user_affiliate_row->exists()) {
            $affiliate_wallet->available_balance = $user_affiliate_row->first()->withdrawable_balance;
            $affiliate_wallet->save();
        } 
        

        // All eligible funded balances record.
        $funded_balances = FundedBalance::where('user_id', Auth::id())->whereRaw('profit - payout_pending != 0')->get();
        
        return view('frontend::withdraw.step1', get_defined_vars());
    }

    /**
     * @return Application|Factory|View
     */
    public function withdraw(Request $request)
    {

        // if wallet is not set
        if(!isset($request->wallet)) {
            notify()->error('Please select the wallet to withdraw.');
            return redirect()->route('user.withdraw.step1');
        }

        // if wallet is not from available types
        if($request->wallet != WalletType::PAYOUT && $request->wallet != WalletType::AFFILIATE) {
            notify()->error('Error proceeding.');
            return redirect()->route('user.withdraw.step1');
        }

        // Get wallet
        $wallet = Wallet::where('user_id', Auth::id())->where('slug', $request->wallet)->firstOrFail();
        if($wallet->available_balance == 0) {
            notify()->error('Insuficcient balance.');
            return redirect()->route('user.withdraw.step1');
        }

        // Withdraw to account:
        $withdraw_to_accounts = WithdrawAccount::where('user_id', Auth::id())->get();
        $withdraw_to_accounts = $withdraw_to_accounts->reject(function ($value, $key) {
            return !$value->method->status;
        });

        $wallets = Wallet::where('user_id', Auth::id())->get();

        // $forexAccounts = ForexAccount::with('schema')
        //     ->where('user_id', auth()->id())
        //     ->where('account_type', 'real')
        //     ->where('status', ForexAccountStatus::Ongoing)
        //     ->orderBy('id', 'desc')
        //     ->get();

        return view('frontend::withdraw.step2', compact('withdraw_to_accounts', 'wallet'));
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
