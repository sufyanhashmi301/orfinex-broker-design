<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\ContractStatusEnums;
use App\Enums\InvestmentStatus;
use App\Models\AccountOpenPosition;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;
use App\Models\AccountTypeInvestmentHourlyStatsRecord;
use App\Services\AccountTypeInvestmentService;

class TradingStatsController extends Controller
{
    public $account;

    public function __construct(AccountTypeInvestmentService $account) {
        $this->middleware('permission:account-list', ['only' => ['adminTradingStats']]);
        $this->middleware('permission:account-trading-history', ['only' => ['accountTradingStatsHistory']]);
        $this->account = $account;
    }

    /**
     * Admin Account Trading Stats History
     */
    public function accountTradingStatsHistory(Request $request) {

        // If search
        if(isset($request->search)) {
            $accounts_stats = AccountTypeInvestmentHourlyStatsRecord::whereHas('accountTypeInvestment', function ($query) use ($request) {
                                                $query->where('login', 'LIKE', '%' . $request->search . '%');
                                            })
                                            ->orderBy('id', 'desc')
                                            ->paginate(48);
            $title = 'Trading Stats History for #' . $request->search;
        } else {
            $accounts_stats = AccountTypeInvestmentHourlyStatsRecord::orderBy('id', 'DESC')->paginate(48);
            $title = "All Trading Stats History";
        }



        return view('backend.accounts_history.index', compact('accounts_stats', 'title'));
    }

    /**
     * Account Tradings Statistics
     */
    private function tradingStats($account, $account_id){
        

        // If account dont exisr
        if(!$account) {
            return redirect()->route('user.investments.index');
        }

        // If account does not have the login 
        if($account->login == null) {
            abort(403);
        }

        

        // if the contract exists and is in pending state
        if(isset($account->contract) && $account->contract->status == ContractStatusEnums::PENDING) {
            notify()->error('Submit Contract to view Trading Stats.', 'Contract Pending');
            return redirect()->back();
        }
        if(isset($account->contract) && $account->contract->status == ContractStatusEnums::EXPIRED) {
            notify()->error('Your contract has been expired.', 'Contract Expired');
            return redirect()->back();
        }

        // if account exists but not the stats or hourly stats
        $hourly_stats = $account->accountTypeInvestmentHourlyStatsRecord;
        if( $account->exists() && (!isset( $account->accountTypeInvestmentStat) || count($hourly_stats) == 0 ) ){
            notify()->error('Account Stats are Loading. Please check back later.', 'Error');
            return redirect()->route('user.investments.index');
        }

        $account_array = $this->account->tradingStats($account_id);
        $account_open_positions = AccountOpenPosition::orderBy('id', 'DESC')->first();

        // All open positions
        $account_array["account_open_positions"] = $account_open_positions['data'] ?? [];

        return $account_array;


    }

    /**
     * User Trading Stats Index
     */
    public function userTradingStats($account_id) {
        $account = AccountTypeInvestment::find($account_id);

        // if the account does not belong to the auth user
        if($account->user->id != Auth::id() || $account->status == InvestmentStatus::EXPIRED) {
            abort(403);
        }

        $account_array = $this->tradingStats($account, $account_id);

        return view("frontend::account.trading_stats")->with($account_array);
    }

    /**
     * Admin Trading Stats Index
     */
    public function adminTradingStats($account_id) {
        $account = AccountTypeInvestment::find($account_id);

        $account_array = $this->tradingStats($account, $account_id);

        return view("frontend::account.trading_stats")->with($account_array);
    }
}
