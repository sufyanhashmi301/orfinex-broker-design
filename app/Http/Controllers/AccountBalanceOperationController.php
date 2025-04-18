<?php

namespace App\Http\Controllers;

use App\Enums\TraderType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\AccountBalanceOperation;
use App\Models\AccountTypeInvestment;
use App\Models\AccountTypeInvestmentStat;
use App\Models\AccountTypeInvestmentHourlyStatsRecord;
use App\Services\ForexApiService;
use App\Services\MatchTraderApiService;

class AccountBalanceOperationController extends Controller
{

    protected $forexApiService;
    protected $matchTraderApiService;

    public function __construct(ForexApiService $forexApiService, MatchTraderApiService $matchTraderApiService) {
        $this->forexApiService = $forexApiService;
        $this->matchTraderApiService = $matchTraderApiService;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Min amount validation (temp)
        if($request->amount <= 0) {
            notify()->error('Invalid Amount');
            return redirect()->back();
        }

        if($request->operation == 'remove' || $request->operation == 'add') {

            $account = AccountTypeInvestment::where('id', $request->account_type_investment_id)->first();

            // Do balance operation via API
            if($account->trader_type == TraderType::MT5) {
                // MT5 Platform
                $data = [
                    'login' => $account->login,
                    'Amount' => $request->amount,
                    'type' => $request->operation == 'add' ? 1 : 2, // deposit
                    'TransactionComments' => $request->comments
                ];
            
                $response = $this->forexApiService->balanceOperation($data);
            
                if ($response['success'] && $response['result']['responseCode'] == 10009) {
                    
                } else {
                    notify()->error('Unknown error occured. Please try again later!');
                    return false;
                }
            } else {
                // MatchTrader Platform
                $data = [
                    "systemUuid" => $account->getAccountTypeSnapshotData()['system_uuid'],
                    "login" => $account->login,
                    "amount" => $request->amount,
                    "comment" => $request->comments
                ];

                if($request->operation == 'add') {
                    $response = $this->matchTraderApiService->depositBalance($data);
                } else {
                    $response = $this->matchTraderApiService->deductBalance($data);
                }

                if(!$response) {
                    notify()->error('Unknown error occured. Please try again later!');
                    return false;
                }
            }
            

            AccountBalanceOperation::create($request->except('_token'));
            $latest_record = AccountTypeInvestmentStat::where('account_type_investment_id', $request->account_type_investment_id)->first();
            $midnight_record = $this->getMidnightRecord($request->account_type_investment_id);
            
            if($request->operation == 'remove') {
                $latest_record->balance = $latest_record->balance - $request->amount; 
                $latest_record->current_equity = $latest_record->current_equity - $request->amount; 
                
                if($request->affect_stats == 0) {
                    $midnight_record->balance = $midnight_record->balance - $request->amount; 
                    $midnight_record->current_equity = $midnight_record->current_equity - $request->amount; 
                }
            } else {
                $latest_record->balance = $latest_record->balance + $request->amount; 
                $latest_record->current_equity = $latest_record->current_equity + $request->amount; 

                if($request->affect_stats == 0) {
                    $midnight_record->balance = $midnight_record->balance + $request->amount; 
                    $midnight_record->current_equity = $midnight_record->current_equity + $request->amount; 
                }
            }

            $latest_record->save();
            $midnight_record->save();
        }

        notify('Account Balance Changed Successfully.');
        return redirect()->back();

    }

    private function getMidnightRecord($account_id) {
        // Same day 1st record after 12AM
        $first_record_after_midnight = AccountTypeInvestmentHourlyStatsRecord::where('account_type_investment_id', $account_id)->where('created_at', '>=', Carbon::today())->orderBy('created_at', 'asc')->first();
        
        // If no record found, fallback to the most recent record
        if (!$first_record_after_midnight) {
            $first_record_after_midnight = AccountTypeInvestmentHourlyStatsRecord::where('account_type_investment_id', $account_id)->orderBy('created_at', 'desc')->first();
        }

        return $first_record_after_midnight;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
