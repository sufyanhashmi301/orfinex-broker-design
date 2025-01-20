<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\ContractStatusEnums;
use App\Models\AccountOpenPosition;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;
use App\Services\AccountTypeInvestmentService;

class TradingStatsController extends Controller
{
    public $account;

    public function __construct(AccountTypeInvestmentService $account) {
        $this->account = $account;
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
        if($account->user->id != Auth::id()) {
            abort(403);
        }

        $account_array = $this->tradingStats($account, $account_id);

        return view("frontend::fund_board.active_plan")->with($account_array);
    }

    /**
     * Admin Trading Stats Index
     */
    public function adminTradingStats($account_id) {
        $account = AccountTypeInvestment::find($account_id);

        $account_array = $this->tradingStats($account, $account_id);

        return view("frontend::fund_board.active_plan")->with($account_array);
    }
}
