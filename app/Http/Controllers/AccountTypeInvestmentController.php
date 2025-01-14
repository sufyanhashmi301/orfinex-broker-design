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
use App\Models\AccountTypeInvestmentPhaseApproval;
use App\Models\AccountTypeInvestmentHourlyStatsRecord;
use App\Services\UserAffiliateService;

class AccountTypeInvestmentController extends Controller
{

    public $investment;
    public $affiliate;

    public function __construct(AccountTypeInvestmentService $investment, UserAffiliateService $userAffiliate) {
        $this->investment = $investment;
        $this->affiliate = $userAffiliate;
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

        return view('frontend::user.forex.log', compact('investments'));    
    }

    public function adminAccountsPhasesLog(Request $request) {
        if(isset($request->unique_id)){
            $uniqueId = $request->unique_id;
            $investment_phase_records = AccountTypeInvestmentPhaseApproval::whereHas('accountTypeInvestment', function ($query) use ($uniqueId) {
                                            $query->where('unique_id', $uniqueId);
                                        })->orderBy('updated_at', 'DESC')->get();
        } elseif (isset($request->{'pending-approvals'})) {
            $investment_phase_records = AccountTypeInvestmentPhaseApproval::where(['status' => InvestmentPhaseApproval::ADMIN_APPROVE, 'action' => 0])->orderBy('updated_at', 'DESC')->get();
        } elseif (isset($request->{'violated-acounts'})) {
            $investment_phase_records = AccountTypeInvestmentPhaseApproval::where(['status' => InvestmentPhaseApproval::VIOLATED])->orderBy('updated_at', 'DESC')->get();
        } else{
            $investment_phase_records = AccountTypeInvestmentPhaseApproval::orderBy('updated_at', 'DESC')->get();
        }

        return view('backend.accounts_phases.index', compact('investment_phase_records'));
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
     * Investment Tradings Statistics
     */
    public function tradingStats($investment_id){
        $investment = AccountTypeInvestment::find($investment_id);
        if($investment->login == null) {
            abort(403);
        }

        // if the contract exists and is in pending state
        if(isset($investment->contract) && $investment->contract->status == ContractStatusEnums::PENDING) {
            notify()->error('Submit Contract to view Trading Stats.', 'Contract Pending');
            return redirect()->back();
        }
        if(isset($investment->contract) && $investment->contract->status == ContractStatusEnums::EXPIRED) {
            notify()->error('Your contract has been expired.', 'Contract Expired');
            return redirect()->back();
        }

        // if account exists but not the stats or hourly stats
        $hourly_stats = $investment->accountTypeInvestmentHourlyStatsRecord;
        if( $investment->exists() && (!isset( $investment->accountTypeInvestmentStat) || count($hourly_stats) == 0 ) ){
            notify()->error('Account Stats are Loading. Please check back later.', 'Error');
            return redirect()->route('user.investments.index');
        }

        $investment_array = $this->investment->tradingStats($investment_id);
        $account_open_positions = AccountOpenPosition::orderBy('id', 'DESC')->first();

        // All open positions 
        $investment_array["account_open_positions"] = $account_open_positions['data'] ?? [];

        // All Accounts
        // $investment_array['accounts'] = AccountTypeInvestment::all();
    
        // dd($investment_array);


        return view("frontend::fund_board.active_plan")->with($investment_array);


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
