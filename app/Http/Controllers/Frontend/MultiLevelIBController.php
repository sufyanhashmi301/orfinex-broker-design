<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\AccountBalanceType;
use App\Enums\MultiLevelType;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\ForexSchema;
use App\Models\MultiLevel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MultiLevelIBController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $user_id = $user->id;
        $totalMonthlyReferrals = $user->getReferral->monthlyRelationships()->count();
        $sourceFrom = AccountBalanceType::AFFILIATE_WALLET;
        $account = get_user_account($user_id,$sourceFrom);
        $accountFromID = $account->id;
        $accountFromName = w2n($sourceFrom);
        $affiliateBalance = $user->multi_ib_balance;
        $tagNames = $user->riskProfileTags()->pluck('name')->toArray();

        $swapSchemas = ForexSchema::active()  // Use the defined scope for active schemas
        ->relevantForUser($user->country, $tagNames)  // Use the integrated scope for filtering by country and tags
        ->orderBy('priority', 'asc')
            ->get();

        $maxLevelOrder = MultiLevel::where('status', 1)  // Assuming '1' indicates active status
        ->select('forex_scheme_id', \DB::raw('COUNT(*) as count'))
            ->groupBy('forex_scheme_id','type')
            ->orderByDesc('count')
            ->first();
        $maxLevelOrder = $maxLevelOrder->count;
        $getReferral = $user->getReferrals()->first();
        $levelOrder = 0;
        $dataCount = [
            'total_deposit' => $user->totalDeposit(30),
            'net_deposit' => $user->totalDeposit(),
            'total_rebate' => $user->totalRebateMeta(),
            'total_volume' => $user->totalVolumeMeta(),
//            'total_investment' => $user->totalInvestment(),
//            'total_profit' => $user->totalProfit(),
//            'profit_last_7_days' => $user->totalProfit(7),
            'total_withdraw' => $user->totalWithdraw(30),
//            'total_transfer' => $user->totalTransfer(),
//            'total_referral_profit' => $user->totalReferralProfit(),
            'total_referral' => $user->getReferral->monthlyRelationships()->count(),

//            'total_forex_balance' => mt5_total_balance($user->id),
//            'total_forex_equity' => mt5_total_equity($user->id),
        ];
        return view('frontend::partner.dashboard', get_defined_vars());

    }
    public function getAccountBalance($name = null, $echo = false)
    {
        $name = (empty($name)) ? AccType('main') : $name;
        $userID = auth()->user()->id;
        return Account::getBalance($name, $userID, $echo);
    }
    public function getSchemes(Request $request)
    {
        $levelOrder = $request->input('level_order');

        // Fetch the schemes related to the selected level
        $user = auth()->user();
        $tagNames = $user->riskProfileTags()->pluck('name')->toArray();

        if($levelOrder==0 ){
            $swapSchemas = ForexSchema::active()  // Use the defined scope for active schemas
            ->relevantForUser($user->country, $tagNames)  // Use the integrated scope for filtering by country and tags
            ->orderBy('priority', 'asc')
                ->get();
        }else {
            $swapSchemas = ForexSchema::active()
                ->relevantForUser($user->country, $tagNames)
                ->whereHas('multiLevels', function ($query) use ($levelOrder) {
                    $query->where('type', MultiLevelType::SWAP)
                        ->where('level_order', $levelOrder)
                        ->where('status', true);
                })
                ->orderBy('priority', 'asc')
                ->get();
            $swapFreeSchemas = ForexSchema::active()
                ->relevantForUser($user->country, $tagNames)
                ->whereHas('multiLevels', function ($query) use ($levelOrder) {
                    $query->where('type', MultiLevelType::SWAP_FREE)
                        ->where('level_order', $levelOrder)
                        ->where('status', true);
                })
                ->orderBy('priority', 'asc')
                ->get();
        }

        $getReferral = $user->getReferrals()->first();

        // Render the view with the updated schemas
        $html = view('frontend::partner.include.__schemes', get_defined_vars())->render();

        // Return the rendered view as JSON
        return response()->json(['html' => $html]);
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
