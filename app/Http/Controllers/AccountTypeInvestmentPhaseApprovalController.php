<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\InvestmentStatus;
use Illuminate\Validation\Rule;
use App\Models\AccountTypeInvestment;
use App\Enums\InvestmentPhaseApproval as InvestmentPhaseApprovalEnum;
use Illuminate\Support\Facades\Validator;
use App\Services\InvestmentPhaseApprovalService;
use App\Models\AccountTypeInvestmentPhaseApproval;
use App\Services\AccountTypeInvestmentPaymentService;

class AccountTypeInvestmentPhaseApprovalController extends Controller
{


    private $investment_payment;
    private $investment_phase_approve;

    public function __construct(AccountTypeInvestmentPaymentService $investment_payment, InvestmentPhaseApprovalService $investment_phase_approve) {
        $this->investment_payment = $investment_payment;
        $this->investment_phase_approve = $investment_phase_approve;
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
     * 
     * Investment Phase Approval Request
     * @param Request $request
     * @param AccountTY
     */
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

            // Approvals History
            // --- Create the Phase Approval Record ---
            // Phase Approval Passed Investment data
            $phase_approval_data[0] = [
                'account_type_investment_id' => $active_investment->id,
                'account_type_phase_id' => $active_investment->getPhaseSnapshotData()['id'],
                'phase_type' => $active_investment->getPhaseSnapshotData()['type'],
                'status' => InvestmentPhaseApprovalEnum::ACTIVE,
                'action' => 1
            ];
      
            // Save the entry in investment phase approvals tablep
            $this->investment_phase_approve->createRecord($phase_approval_data[0]);
            
            // Update the action of the current one to 1 
            AccountTypeInvestmentPhaseApproval::where([
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
     * @param  \App\Models\AccountTypeInvestmentPhaseApproval  $accountTypeInvestmentPhaseApproval
     * @return \Illuminate\Http\Response
     */
    public function show(AccountTypeInvestmentPhaseApproval $accountTypeInvestmentPhaseApproval)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountTypeInvestmentPhaseApproval  $accountTypeInvestmentPhaseApproval
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountTypeInvestmentPhaseApproval $accountTypeInvestmentPhaseApproval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountTypeInvestmentPhaseApproval  $accountTypeInvestmentPhaseApproval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccountTypeInvestmentPhaseApproval $accountTypeInvestmentPhaseApproval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountTypeInvestmentPhaseApproval  $accountTypeInvestmentPhaseApproval
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountTypeInvestmentPhaseApproval $accountTypeInvestmentPhaseApproval)
    {
        //
    }
}
