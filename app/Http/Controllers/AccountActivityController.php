<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\InvestmentStatus;
use App\Models\AccountActivity;
use Illuminate\Validation\Rule;
use App\Models\AccountTypeInvestment;
use App\Services\AccountActivityService;
use Illuminate\Support\Facades\Validator;
use App\Services\InvestmentPhaseApprovalService;
use App\Services\AccountTypeInvestmentPaymentService;
use App\Enums\InvestmentPhaseApproval as InvestmentPhaseApprovalEnum;

class AccountActivityController extends Controller
{

    private $investment_payment;

    public function __construct(AccountTypeInvestmentPaymentService $investment_payment, ) {
        $this->investment_payment = $investment_payment;
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

    public function phaseApprovalRequest(Request $request, $investment_id) {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'operation' => [
                'required',
                'string',
                Rule::in(['approve', 'reject']),
            ],
        ]);

        // If Request Invalidates
        if ($validator->fails()) {
            notify()->error('Something went wrong! Please try again.', 'Error');
            return redirect()->back();
        }
    

        // Validate the investment ID
        $investment = AccountTypeInvestment::find($investment_id);
        if (!$investment) {
            notify()->error('Invalid Investment Id', 'Error');
            return redirect()->back();
        }

        // Approve/Reject Investment
        if($request->operation == 'approve') {
            // Approve Logic
            $active_investment = $this->investment_payment->investmentActive($investment_id);

            // Account activity log
            AccountActivityService::log($active_investment, InvestmentPhaseApprovalEnum::ACTIVE);
            
            // Update the action of the current one to 1 
            AccountActivity::where([
                'account_type_investment_id' => $investment->id,
                'account_type_phase_id' => $investment->getPhaseSnapshotData()['id'],
                'status' => InvestmentPhaseApprovalEnum::ADMIN_APPROVE,
            ])->first()->update(['action' => 1]);

            if($active_investment->status == InvestmentStatus::ACTIVE) {
                notify()->success('Investment Phase is successfully approved');
                return redirect()->back();
            }else{
                notify()->error('Something went wrong! Investment phase is not approved.', 'Error');
                return redirect()->back();
            }

        }else{
            // Reject Logic
        }

        

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
