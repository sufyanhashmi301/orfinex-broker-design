<?php

namespace App\Http\Controllers;

use App\Enums\ContractStatusEnums;
use App\Enums\InvestmentPhaseApproval;
use Carbon\Carbon;
use App\Enums\TraderType;
use Brick\Math\BigDecimal;
use Illuminate\Http\Request;
use App\Enums\InvestmentStatus;
use App\Models\AccountOpenPosition;
use App\Services\ForexApiService;
use App\Models\AccountTypePhaseRule;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;
use App\Models\AccountTypeInvestmentSnapshot;
use App\Services\AccountTypeInvestmentService;
use App\Models\AccountActivity;
use App\Models\AccountTypeInvestmentHourlyStatsRecord;
use App\Services\AccountTypeInvestmentPaymentService;
use App\Services\UserAffiliateService;

class AccountTypeInvestmentController extends Controller
{

    public $investment;
    public $affiliate;
    public $investment_payment;

    public function __construct(AccountTypeInvestmentService $investment, UserAffiliateService $userAffiliate, AccountTypeInvestmentPaymentService $investment_payment) {

        $this->middleware('permission:account-list', ['only' => ['adminIndex']]);

        $this->investment = $investment;
        $this->affiliate = $userAffiliate;
        $this->investment_payment = $investment_payment;
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
                $accounts = AccountTypeInvestment::where('status', $request->status)->orderBy('id', 'desc')->paginate(15);
                $title = ucfirst($request->status) . ' Accounts';
                $accounts_filter = true;
            }

        }

        // If search
        if(isset($request->search)) {
            $accounts = AccountTypeInvestment::where('login', 'LIKE', '%' . $request->search . '%')
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
            $accounts = AccountTypeInvestment::orderBy('id', 'desc')->paginate(15);
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

        $user = auth()->user();

        if(!isset($request->status)){
            // show all investments if the status is not defined
            $investments = AccountTypeInvestment::traderType()->where('user_id', $user->id)->orderBy('id', 'desc')->get();
        }else{
            $investments = AccountTypeInvestment::traderType()->where('user_id', $user->id)->where('status', $request->status)->orderBy('id', 'desc')->get();
        }

        return view('frontend::account.index', compact('investments'));
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
