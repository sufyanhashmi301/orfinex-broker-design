<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
use App\Models\AccountTypeInvestmentHourlyStatsRecord;

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
            $investments = AccountTypeInvestment::traderType()->where('user_id', $user->id)->where('status', $request->status)->orderBy('id', 'desc')->get();
        }

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
        $investment = AccountTypeInvestment::findorFail($investment_id);
        $investment_snapshot = $investment->accountTypeInvestmentSnapshot;

        // Same day 1st record after 12AM
        $first_record_after_midnight = AccountTypeInvestmentHourlyStatsRecord::where('account_type_investment_id', $investment->id)->where('created_at', '>=', Carbon::today())->orderBy('created_at', 'asc')->first();

        
        // If no record found, fallback to the most recent record
        if (!$first_record_after_midnight) {
            $first_record_after_midnight = AccountTypeInvestmentHourlyStatsRecord::where('account_type_investment_id', $investment->id)
                ->orderBy('created_at', 'desc')
                ->first();

        }

        // dd($first_record_after_midnight)

        if ($investment->status == InvestmentStatus::ACTIVE) {
            return view("frontend::fund_board.active_plan", compact("investment", "investment_snapshot", "first_record_after_midnight"));
        }

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
