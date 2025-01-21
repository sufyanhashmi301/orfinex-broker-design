<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\TxnStatus;
use App\Models\User;
use App\Models\AccountType;
use App\Models\AccountTypeInvestment;
use App\Models\Addon;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class AccountBuyController extends Controller
{
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

        return view('frontend::forex_schema.index', compact('account_types', 'failed_transactions'));
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
        $account_type = AccountType::find($id);

        // Creation limit error
        $no_of_accounts = AccountTypeInvestment::where('user_id', Auth::id())
                                                ->whereHas('accountTypeInvestmentSnapshot', function ($query) use ($account_type) {
                                                    $query->whereJsonContains('account_types_data->id', $account_type->id);
                                                })->get();

        $failed_transactions = Transaction::where('user_id', Auth::id())->where('status', TxnStatus::Failed)->get();
        $exclude_limit = count(array_intersect($failed_transactions->pluck('target_id')->toArray(), $no_of_accounts->pluck('id')->toArray()));                                                            
        if((count($no_of_accounts) - $exclude_limit) >= $account_type->accounts_limit) {
            abort(403);
        }

        $addons = Addon::where('status', 1)->get();
        $legal_links = Setting::where('name', 'LIKE', '%legal_%')->where('name', 'LIKE', '%_purchase%')->get();

        return view('frontend::forex_schema.preview', compact('account_type', 'addons', 'legal_links'));
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
