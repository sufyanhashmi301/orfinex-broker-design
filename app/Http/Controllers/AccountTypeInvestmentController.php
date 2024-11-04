<?php

namespace App\Http\Controllers;

use App\Enums\TraderType;
use Brick\Math\BigDecimal;
use Illuminate\Http\Request;
use App\Enums\InvestmentStatus;
use App\Services\ForexApiService;
use App\Models\AccountTypePhaseRule;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;
use App\Models\AccountTypeInvestmentSnapshot;
use App\Services\AccountTypeInvestmentService;

class AccountTypeInvestmentController extends Controller
{

    protected $investment;

    public function __construct(AccountTypeInvestmentService $investment) {
        $this->investment = $investment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if(!isset($request->status)){
            // show all investments if the status is not defined
            $investments = AccountTypeInvestment::traderType()->where('user_id', $user->id)->orderBy('id', 'desc')->get();
        }else{
            $investments = AccountTypeInvestment::traderType()->where('status', $request->status)->where('user_id', $user->id)->orderBy('id', 'desc')->get();
        }

        $investments = $investments->groupBy('status');

        return view('frontend::user.forex.log', compact('investments'));    
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
        $investment = $this->investment->createInvestment($request);

        // to Deposit Page
        return redirect()->route('user.deposit.amount', ['investment' => $investment->id]);

    }

    /**
     * Investment Tradings Statistics
     */
    public function tradingStats($investment_id){
        $invest = AccountTypeInvestment::find($investment_id);
        $investment_snapshot = $invest->accountTypeInvestmentSnapshot;
       
        if (blank($invest)) {
            notify()->error(__('Investment not found! Try Again'), 'Error');
            return redirect()->back();
          
        }

        if ($invest->status == InvestmentStatus::ACTIVE) {
            $invest = $invest->fresh();

            $growthPercentage = percentage_of_total_calc(0, $invest->accountTypePhaseRule->allotted_funds);

            $traderType = $invest->trader_type;

            if ($traderType == TraderType::MT5) {
                $forexApi = new ForexApiService();
                $data = [
                    'login' => $invest->login
                ];
                $statsUser = $forexApi->statsUser($data);
                $todayScore = $forexApi->getTodayRiskScore($data);
                $weeklyScore = $forexApi->getWeekRiskScore($data);
                $totalScore = $forexApi->getTotalRiskScore($data);
                $totalBalance = $forexApi->getBalance($data);
            } 
            
            

            return view("frontend::fund_board.active_plan", compact("invest", "investment_snapshot", "growthPercentage", "todayScore", "weeklyScore", "totalScore", "totalBalance", "statsUser"));
        }

        return view("investment.user.pricing.plan", compact("invest", "plans"));
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
