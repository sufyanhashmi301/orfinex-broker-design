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
use App\Models\AccountTypeInvestmentPhaseApproval;
use App\Models\AccountTypeInvestmentHourlyStatsRecord;

class AccountTypeInvestmentController extends Controller
{

    public $investment;

    public function __construct(AccountTypeInvestmentService $investment) {
        $this->investment = $investment;
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

    public function adminIndex() {
        $investment_phase_records = AccountTypeInvestmentPhaseApproval::get();

        return view('backend.investments.index', compact('investment_phase_records'));
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
        
        if(AccountTypeInvestment::find($investment_id)->login == null) {
            abort(403);
        }

        $investment_array = $this->investment->tradingStats($investment_id);

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
