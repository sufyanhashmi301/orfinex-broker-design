<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Setting;
use App\Enums\TraderType;
use Illuminate\Http\Request;
use App\Models\FundedBalance;
use App\Models\PayoutRequest;
use App\Services\ForexApiService;
use App\Enums\PayoutRequestStatus;
use App\Models\AccountTypeInvestment;
use App\Services\MatchTraderApiService;
use App\Models\AccountTypeInvestmentStat;

class PayoutRequestController extends Controller
{
    protected $forexApiService;
    protected $matchTraderApiService;

    public function __construct(ForexApiService $forexApiService, MatchTraderApiService $matchTraderApiService)
    {
        $this->forexApiService = $forexApiService;
        $this->matchTraderApiService = $matchTraderApiService;
    }

    /**
     * Configure 
     */
    public function config(Request $request) {
        Setting::updateOrCreate(
            ['name' => 'payout_eligibility_period'],             
            ['val' => $request->payout_eligibility_period]  
        );

        notify()->success('Payout Request Configured Successfully!');
        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->status == PayoutRequestStatus::APPROVED){
            $payout_requests = PayoutRequest::where('status', PayoutRequestStatus::APPROVED)->orderBy('id', 'DESC')->get();
        } elseif ($request->status == PayoutRequestStatus::DECLINE) {
            $payout_requests = PayoutRequest::where('status', PayoutRequestStatus::DECLINE)->orderBy('id', 'DESC')->get();
        } elseif ($request->status == PayoutRequestStatus::PENDING) {
            $payout_requests = PayoutRequest::where('status', PayoutRequestStatus::PENDING)->orderBy('id', 'DESC')->get();
        } else {
            
            $payout_requests = PayoutRequest::orderBy('id', 'DESC')->get();
        }

        

        return view('backend.payout_request.index', compact('payout_requests'));
    }

    public function action(Request $request, $payout_request_id) {
        
        $payout_request = PayoutRequest::find($payout_request_id);
        

        // do security measures...
        if($payout_request->status != PayoutRequestStatus::PENDING) {
            notify()->error('False action on Payout Request', 'Error');
            return redirect()->back();
        }

        if($request->operation == 'approve') {
            $payout_request->status = PayoutRequestStatus::APPROVED;

            // Deduct from API, Funded balances, and Stats
            foreach($payout_request->detail as $single) {
                $account = AccountTypeInvestment::where('login', $single['login'])->firstOrFail();
                
                $total_profit = $single['total_profit'];

                // Balance Removal via API wrt Trader Type
                if($account->trader_type == TraderType::MT5) {
                    // Removal from API
                    $reset_balance_data = [
                        'login' => $single['login'],
                        'Amount' => $total_profit,
                        'type' => 0,
                        'TransactionComments' => 'Payout Successful'
                    ];
                
                    $response = $this->forexApiService->balanceOperation($reset_balance_data);
                } elseif($account->trader_type == TraderType::MT) {
                    $deduct_balance_data = [
                        "systemUuid" => $account->getAccountTypeSnapshotData()['system_uuid'],
                        "login" => $account->login,
                        "amount" => $total_profit,
                        "comment" => "Payout Successful"
                    ];
                
                    $deduct_balance_response = $this->matchTraderApiService->deductBalance($deduct_balance_data);

                    if(!$deduct_balance_response) {
                        return redirect()->back();
                    }
                }

                // removal from Stats
                $acocunt_stats = AccountTypeInvestmentStat::where('account_type_investment_id', $single['account_id'])->first();
                $acocunt_stats->balance = $acocunt_stats->balance - $total_profit;
                $acocunt_stats->current_equity = $acocunt_stats->current_equity - $total_profit;
                $acocunt_stats->save();

                // removal from funded balances
                $funded_balance = FundedBalance::where('account_type_investment_id', $single['account_id'])->first();
                $funded_balance->profit = $funded_balance->profit - $total_profit;
                $funded_balance->last_retrieved_profit = $funded_balance->last_retrieved_profit - $total_profit;
                $funded_balance->payout_pending = $funded_balance->payout_pending - $total_profit;
                $funded_balance->save();

            }

            // Add to Wallet
            $wallet = Wallet::where('unique_id', $payout_request->wallet_unique_id)->first();
            $wallet->available_balance = $wallet->available_balance + $payout_request->user_profit_share_amount;
            $wallet->save();

            notify('Payout request approved!', 'Success');

        } else {
            $payout_request->status = PayoutRequestStatus::DECLINE;

            foreach($payout_request->detail as $single) {
                $total_profit = $single['total_profit'];

                $funded_balance = FundedBalance::where('account_type_investment_id', $single['account_id'])->first();
                $funded_balance->payout_pending = $funded_balance->payout_pending - $total_profit;
                $funded_balance->save();
            }

            notify('Payout request declined!', 'Success');
        }
        $payout_request->save();

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
        //
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
