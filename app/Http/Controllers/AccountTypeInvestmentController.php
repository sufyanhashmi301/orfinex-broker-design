<?php

namespace App\Http\Controllers;

use App\Enums\AccountType;
use Carbon\Carbon;
use App\Enums\TraderType;
use Brick\Math\BigDecimal;
use Illuminate\Http\Request;
use App\Enums\InvestmentStatus;
use App\Models\AccountActivity;
use App\Services\ForexApiService;
use App\Enums\ContractStatusEnums;
use App\Enums\KycNoticeInvokeEnums;
use App\Models\AccountOpenPosition;
use App\Models\AccountTypePhaseRule;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;
use App\Services\UserAffiliateService;
use App\Models\AccountTypeInvestmentSnapshot;
use App\Services\AccountTypeInvestmentService;
use App\Services\AccountTypeInvestmentPaymentService;
use App\Models\AccountTypeInvestmentHourlyStatsRecord;
use App\Models\Transaction;
use App\Services\MatchTraderApiService;
use Illuminate\Support\Facades\Artisan;

class AccountTypeInvestmentController extends Controller
{

    public $investment;
    public $affiliate;
    public $investment_payment;
    protected $forexApiService;
    protected $matchTraderApiService;

    public function __construct(ForexApiService $forexApiService, MatchTraderApiService $matchTraderApiService, AccountTypeInvestmentService $investment, UserAffiliateService $userAffiliate, AccountTypeInvestmentPaymentService $investment_payment) {
        $this->middleware('permission:account-list', ['only' => ['adminIndex']]);

        $this->investment = $investment;
        $this->affiliate = $userAffiliate;
        $this->investment_payment = $investment_payment;
        $this->forexApiService = $forexApiService;
        $this->matchTraderApiService = $matchTraderApiService;

        $this->middleware('check.user.profile')->only(['store']);
    }

    /**
     * Admin Index (Show Accounts)
     */
    public function adminIndex(Request $request) {

        $accounts_filter = false;
        if(isset($request->status)){
            // Filter accounts wrt status when status exists

            if (in_array($request->status, (new \ReflectionClass(InvestmentStatus::class))->getConstants())) {
                // Handle the logic here if the status is valid
                $accounts = AccountTypeInvestment::where('status', '!=', InvestmentStatus::PENDING_NOT_PAID)->where('status', '!=', InvestmentStatus::PENDING)->where('status', $request->status)->orderBy('id', 'desc')->paginate(15);
                $title = ucfirst($request->status) . ' Accounts';
                $accounts_filter = true;
            }

        }

        // If search
        if(isset($request->search)) {
            $accounts = AccountTypeInvestment::where('status', '!=', InvestmentStatus::PENDING_NOT_PAID)->where('status', '!=', InvestmentStatus::PENDING)->where('login', 'LIKE', '%' . $request->search . '%')
                                            ->orWhereHas('user', function ($query) use ($request) {
                                                $query->where('first_name', 'LIKE', '%' . $request->search . '%')
                                                    ->orWhere('last_name', 'LIKE', '%' . $request->search . '%')
                                                    ->orWhere('email', 'LIKE', '%' . $request->search . '%');
                                            })
                                            ->orderBy('id', 'desc')
                                            ->paginate(15);
            $accounts_filter = true;
            $title = 'Search results for: ' . $request->search;
        }

        // if status is unknown then show all accounts
        if(!$accounts_filter) {
            $accounts = AccountTypeInvestment::where('status', '!=', InvestmentStatus::PENDING_NOT_PAID)->where('status', '!=', InvestmentStatus::PENDING)->orderBy('id', 'desc')->paginate(15);
            $title = 'All Accounts';
            if($request->status != 'all') {
                return redirect()->route('admin.accounts.index', ['status' => 'all']);
            }
        }

        return view('backend.accounts.index', compact('accounts', 'title'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // Delete the accounts that are not paid
        AccountTypeInvestment::where('user_id', Auth::id())->where('status', InvestmentStatus::PENDING_NOT_PAID)->delete();

        $user = auth()->user();

        if(!isset($request->status)){
            // show all investments if the status is not defined
            $investments = AccountTypeInvestment::traderType()->where('user_id', $user->id)->orderBy('id', 'desc')->get();
        }else{
            $investments = AccountTypeInvestment::traderType()->where('user_id', $user->id)->where('status', $request->status)->orderBy('id', 'desc')->get();
        }

        $transactions = Transaction::whereIn('target_id', $investments->pluck('id'))->get();

        return view('frontend::account.index', get_defined_vars());
    }

    /**
     * AJAX: return account stats wrt login
     */
    public function ajaxAccountStats(Request $request) {

        $account = AccountTypeInvestment::where('user_id', Auth::id())->whereHas('accountTypeInvestmentStat')->where('login', $request->login)->first();

        if($account) {
            return [
                'balance' => number_format($account->accountTypeInvestmentStat->balance, 2),
                'current_equity' => number_format($account->accountTypeInvestmentStat->current_equity, 2),
                'total_pnl' => number_format($account->accountTypeInvestmentStat->total_pnl, 2),
                'floating' => number_format($account->accountTypeInvestmentStat->current_equity - $account->accountTypeInvestmentStat->balance, 2),
                'login' => $account->login
            ];
        } else {
            return false;
        }
    }

    /**
     * Assign Account Manually
     */
    public function addManually(Request $request) {
        $rule_unique_id = AccountTypePhaseRule::where('id', $request->rule_id)->firstOrFail()->unique_id;
        $rule = AccountTypePhaseRule::where('account_type_phase_id', $request->phase_id)->where('unique_id', $rule_unique_id)->first();
        $request->merge(['rule_id' => $rule->id]);

        $account = $this->investment->createInvestment($request, 0, false, true, $request->user_id);
        $active_account = $this->investment_payment->investmentActive($account->id);

        if(!$active_account) {
            return redirect()->back();
        }

        if($active_account->status == InvestmentStatus::ACTIVE) {
            notify()->success('Account created successfully!');
        } else {
            notify()->error('Account not created!', 'Unknown Error Occured');
            AccountTypeInvestmentSnapshot::where('account_type_investment_id', $account->id)->delete();
            $account->delete();
        }

        return redirect()->back();
    }

    /**
     * Restore violated account
     */
    public function restoreViolatedAccount(Request $request, $id) {
        $account = AccountTypeInvestment::findOrFail($id);
        $balance = 0;

        if($request->restore_method == 'original') {
            $balance = $account->getRuleSnapshotData()['allotted_funds'];
        }
        
        if($request->restore_method == 'custom') {
            $balance = $request->balance;
            $allotted_funds_with_profit_target = $account->getRuleSnapshotData()['allotted_funds'] + $account->getRuleSnapshotData()['profit_target'];
            $allotted_funds_with_dd = $account->getRuleSnapshotData()['allotted_funds'] - $account->getRuleSnapshotData()['daily_drawdown_limit'];

            if($balance <= $allotted_funds_with_dd) {
                notify()->error('The amount to be added must be greater than the allotted funds minus the daily drawdown.');
                return redirect()->back();
            }

            if($balance >= $allotted_funds_with_profit_target) {
                notify()->error('The amount to be added must be lesser than the allotted funds plus the profit target.');
                return redirect()->back();
            }
        }

        if($account->trader_type == TraderType::MT5) {
            // Balance Operation
            $balance_data = [
                'login' => $account->login,
                'Amount' => $balance,
                'type' => 1, //deposit
                'TransactionComments' => 'Account Manually Restored'
            ];
            
            $response = $this->forexApiService->balanceOperation($balance_data);

            if (!$response['success'] || $response['result']['responseCode'] != 10009) {
                notify()->error('Unknown error occured. Please try again.');
            }

        } elseif($account->trader_type == TraderType::MT) {
            $deposit_balance_data = [
                "systemUuid" => $account->getAccountTypeSnapshotData()['system_uuid'],
                "login" => $account->login,
                "amount" => $balance,
                "comment" => "Account Manually Restored"
            ];
          
            $deposit_balance_response = $this->matchTraderApiService->depositBalance($deposit_balance_data);

            if(!$deposit_balance_response) {
                notify()->error('Unknown error occured. Please try again.');
            }
        }

        // Update Account
        $account->update([
            'status' => InvestmentStatus::ACTIVE,
            'violation_reason' => null,
            'violation_data' => null,
            'mail_sent' => 0,

        ]);
  
        // Update the Stats and Hourly Stats
        Artisan::call('update:investment-stats --both');

        notify()->success('Balance is added to account and the Account is active.');
       
        return redirect()->back();

        
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // for affiliates testing ---
        // $buyer_user_id  = Auth::id();
        // $this->affiliate->applyCommission($request->rule_id, $buyer_user_id);
        // return redirect()->back();
        // for affiliates testing ---

        // KYC Check
        if(kyc_check_exists(KycNoticeInvokeEnums::ACCOUNT_PURCHASE)) {
            return redirect()->route('user.verification.index');
        }

        $investment = $this->investment->createInvestment($request);

        // to Deposit Page
        return redirect()->route('user.deposit.amount', ['investment' => $investment->id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
