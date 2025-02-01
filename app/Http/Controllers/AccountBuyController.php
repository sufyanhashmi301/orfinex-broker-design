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
        

        $account_types = AccountType::active()->traderType()  // Use the defined scope for active accounts
                    ->relevantForUser($user->country, [])  // Use the integrated scope for filtering by country and tags
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
