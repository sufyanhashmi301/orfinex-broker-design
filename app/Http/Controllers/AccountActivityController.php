<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\InvestmentStatus;
use App\Models\AccountActivity;
use Illuminate\Validation\Rule;
use App\Models\AccountTypeInvestment;
use App\Services\AccountActivityService;
use App\Enums\AccountActivityStatusEnums;
use Illuminate\Support\Facades\Validator;
use App\Services\AccountTypeInvestmentPaymentService;

class AccountActivityController extends Controller
{

    private $investment_payment;

    public function __construct(AccountTypeInvestmentPaymentService $investment_payment, ) {

        $this->middleware('permission:account-activity-list', ['only' => ['index', 'phaseApprovalRequest']]);
        $this->middleware('permission:account-activity-approval', ['only' => ['phaseApprovalRequest']]);

        $this->investment_payment = $investment_payment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $account_activities_filter = false;

        // Filter wrt Unique ID
        if(isset($request->unique_id)){
            $uniqueId = $request->unique_id;
            $account_activities = AccountActivity::whereHas('accountTypeInvestment', function ($query) use ($uniqueId) {
                                            $query->where('unique_id', $uniqueId);
                                        })->orderBy('id', 'DESC')->paginate(15);
            $account_activities_filter = true;

            $title = 'Account Activity Logs: ' . $request->unique_id;
        } 

        // Wrt status
        if(isset($request->status)){
            // Filter users wrt status when status exists
            if (in_array($request->status, (new \ReflectionClass(AccountActivityStatusEnums::class))->getConstants())) {
                
                $condition = ['status' => $request->status];
                if(AccountActivityStatusEnums::ADMIN_APPROVE) {
                    $condition['action'] = 0;
                }

                $account_activities = AccountActivity::where($condition)->with(['accountTypeInvestment.user'])->whereHas('accountTypeInvestment', function($query) {
                                            $query->whereHas('user'); 
                                        })->orderBy('id', 'DESC')->paginate(15);
                
                $account_activities_filter = true;
            }

            $title = 'Account Acitivites | ' . ucfirst(str_replace('_', ' ', $request->status));
        }

        // Search
        if(isset($request->search)) {
            
            $account_activities = AccountActivity::whereHas('accountTypeInvestment.user', function ($query) use ($request) {
                        $query->where('first_name', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('email', 'LIKE', '%' . $request->search . '%');
                        })
                        ->paginate(15);
            
            $account_activities_filter = true;
            $title = 'Search results for: ' . $request->search;
        }

        // All
        if(!$account_activities_filter) {
            $account_activities = AccountActivity::with(['accountTypeInvestment.user'])->whereHas('accountTypeInvestment', function($query) {
                                                    $query->whereHas('user'); 
                                                })->orderBy('id', 'DESC')->paginate(15);
            
            if($request->status != 'all') {
                return redirect()->route('admin.accounts_activity.log', ['status' => 'all']);
            }
            $title = 'All Account Activity Logs';
        }

        return view('backend.accounts_activity.index', get_defined_vars());
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

            if(!$active_investment) {
                return redirect()->back();
            }

            // Account activity log
            AccountActivityService::log($active_investment, AccountActivityStatusEnums::ACTIVE);
            
            // Update the action of the current one to 1 
            AccountActivity::where([
                'account_type_investment_id' => $investment->id,
                'account_type_phase_id' => $investment->getPhaseSnapshotData()['id'],
                'status' => AccountActivityStatusEnums::ADMIN_APPROVE,
            ])->first()->update(['action' => 1]);

            if($active_investment->status == InvestmentStatus::ACTIVE) {
                notify()->success('Investment Phase is successfully approved');
                return redirect()->back();
            }else{
                notify()->error('Something went wrong! Investment phase is not approved.', 'Error');
                return redirect()->back();
            }

        } else {
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
