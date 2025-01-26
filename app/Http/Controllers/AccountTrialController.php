<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\AccountType;
use App\Models\AccountTrial;
use Illuminate\Http\Request;
use App\Enums\InvestmentStatus;
use Illuminate\Support\Facades\Auth;
use App\Services\AccountActivityService;
use App\Services\AccountTypeInvestmentService;
use App\Services\AccountTypeInvestmentPaymentService;

class AccountTrialController extends Controller
{

    protected $account;
    protected $account_payment;

    public function __construct(AccountTypeInvestmentService $account, AccountTypeInvestmentPaymentService $account_payment) {
        $this->account = $account;
        $this->account_payment = $account_payment;
    }

    /**
     * Granting free trial to user
     */
    public function freeTrial(Request $request, $id) {

        $account_type = AccountType::findOrFail($id);
        $trial_used = AccountTrial::where('user_id', Auth::id())->where('trial_used', 1)->exists();
        
        // Check that trial must not be used before and account type allows trial
        if($trial_used || $account_type->is_trial == 0) {
            abort(403);
        } 

        // Create Account
        $account = $this->account->createInvestment($request, 0, true);

        // Create Trial Entry
        AccountTrial::create([
            'user_id' => Auth::id(),
            'account_type_investment_id' => $account->id,
            'trial_expiry_at' => Carbon::now()->addDays(setting('auto_expire_expiry_days') + 1),
            'trial_used' => 1,
        ]);

        // Approve Account
        $account_approved = $this->account_payment->investmentActive($account->id);

        // Notify user
        if($account_approved->status == InvestmentStatus::ACTIVE) {
            AccountActivityService::log($account_approved, 'Trial Active');
            notify()->success('Your 14 days free trial has been started', 'Congratulations');
        } else {
            notify()->error('Unknown Error Occured. Trial account will be active soon.', 'Error');
        }

        return redirect()->route('user.investments.index');

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
