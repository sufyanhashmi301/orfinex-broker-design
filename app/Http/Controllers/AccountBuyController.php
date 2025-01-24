<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Addon;
use App\Models\Setting;
use App\Enums\TxnStatus;
use App\Models\AccountType;
use App\Models\Transaction;
use App\Models\AccountTrial;
use Illuminate\Http\Request;
use App\Enums\InvestmentStatus;
use App\Services\AccountBuyService;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;
use App\Services\AccountActivityService;
use App\Services\AccountTypeInvestmentService;
use App\Services\AccountTypeInvestmentPaymentService;

class AccountBuyController extends Controller
{

    protected $account_buy;
    protected $account;
    protected $account_payment;

    public function __construct(AccountBuyService $account_buy, AccountTypeInvestmentService $account, AccountTypeInvestmentPaymentService $account_payment) {
        $this->account_buy = $account_buy;
        $this->account = $account;
        $this->account_payment = $account_payment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        $tagNames = $user->riskProfileTags()->pluck('name')->toArray();

        $account_types = AccountType::active()->traderType()  // Use the defined scope for active accounts
                    ->relevantForUser($user->country, $tagNames)  // Use the integrated scope for filtering by country and tags
                    ->orderBy('priority', 'asc')
                        ->get();
        
        $failed_transactions = Transaction::where('user_id', Auth::id())->where('status', TxnStatus::Failed)->get();

        $trial_used = AccountTrial::where('user_id', Auth::id())->where('trial_used', 1)->exists();

        return view('frontend::account_buy.index', compact('account_types', 'failed_transactions', 'trial_used'));
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
    public function show(Request $request, $id)
    {
        $account_type = AccountType::find($id);

        // Creation limit error
        $account_creation_limit = $this->account_buy->checkAccountCreationLimit($id);
        if(!$account_creation_limit) {
            abort(403);
        }

        // Check Status
        if($account_type->status == 0) {
            abort(403);
        }

        // Check Free trial
        $trial_used = AccountTrial::where('user_id', Auth::id())->where('trial_used', 1)->exists();
        if($request->action == 'free_trial' && $trial_used) {
            abort(403);
        } 

        $addons = Addon::where('status', 1)->get();
        $legal_links = Setting::where('name', 'LIKE', '%legal_%')->where('name', 'LIKE', '%_purchase%')->get();

        return view('frontend::account_buy.show', compact('account_type', 'addons', 'legal_links'));
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
            'trial_expiry_at' => Carbon::now()->addDays(15),
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
