<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\AccountBalanceType;
use App\Enums\MultiLevelType;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\ForexSchema;
use App\Models\IbQuestion;
use App\Models\LevelReferral;
use App\Models\MetaDeal;
use App\Models\MultiLevel;
use App\Models\RebateRule;
use App\Models\UserIbRule;
use App\Models\Transaction;
use App\Services\IBTransactionPeriodService;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
//        if(auth()->user()->ib_status != \App\Enums\IBStatus::APPROVED && isset(auth()->user()->ref_id)){
//            return redirect()->route('user.referral');
//
//        }else

        if(auth()->user()->ib_status != \App\Enums\IBStatus::APPROVED && !isset(auth()->user()->ref_id)) {

            return redirect()->route('user.ib.request');
        }

        $user_id = $user->id;
        $totalMonthlyReferrals = $user->getReferral->monthlyRelationships()->count();
        $sourceFrom = AccountBalanceType::IB_WALLET;
        $account = get_user_account($user_id,$sourceFrom);
        $accountFromID = $account->id;
        $accountFromName = w2n($sourceFrom);
        $affiliateBalance = $account->amount;
        $tagNames = $user->riskProfileTags()->pluck('name')->toArray();

        // Total commission = ONLY ib_balance (not historical transactions)
        $totalIbBonusWithBalance = $user->ib_balance;

        // Calculate LAST 30 DAYS IB bonus from quarterly tables (may span 2 quarters)
        // Example: Oct 16 will get Sep 16 - Oct 16 (could be 10 days in current quarter + 20 days in previous quarter)
        // Note: This does NOT include ib_balance, only transactions from quarterly tables
        $currentMonthIbBonusWithBalance = $this->getCurrentMonthIbCommissionFromQuarters($user_id);

        $swapSchemas = ForexSchema::active()  // Use the defined scope for active schemas
        ->relevantForUser($user->country, $tagNames)  // Use the integrated scope for filtering by country and tags
        ->orderBy('priority', 'asc')
            ->get();

        $maxLevelOrder = MultiLevel::where('status', 1)  // Assuming '1' indicates active status
        ->select('forex_scheme_id', \DB::raw('COUNT(*) as count'))
            ->groupBy('forex_scheme_id','type')
            ->orderByDesc('count')
            ->first();

        $maxLevelOrderCount = $maxLevelOrder ? $maxLevelOrder->count : 0;
        $getReferral = $user->getReferrals()->first();
//        dd($getReferral,$getReferral->link);
        $levelOrder = 0;
        $dataCount = [
            'monthly_referrals' => $user->getReferral->monthlyRelationships()->count(),
//            'total_rebate' => $this->getReferralsNetRebate($user,30),
            'total_referrals_balance' =>  $this->getReferralsTotalBalance($user),
            'total_volume' => $this->getReferralsNetVolume($user,30),
            'total_referrals' => $user->referrals()->count(),
            'total_deposit' => $user->totalReferralsDeposit(),
            'total_withdraw' => $user->totalReferralsWithdraw(),
            'monthly_rebate' =>  $user->totalRebate(30),
            'net_rebate' =>  $user->totalRebate(),
            'net_referrals_volume' =>  $this->getReferralsNetVolume($user),
            'total_ib_bonus' => $totalIbBonusWithBalance,
            'current_month_ib_bonus' => $currentMonthIbBonusWithBalance,
            'total_lots' => $this->getReferralsLotShare($user)['total'],
            'current_month_lots' => $this->getReferralsLotShare($user)['current_month'],
        ];

        return view('frontend::partner.dashboard', get_defined_vars());

    }
    public function getReferralsTotalBalance($user)
    {
        // Get all referrals
        $referrals = $user->referrals()->get();

        // Initialize total balance
        $totalBalance = 0;

        // Iterate through each referral and calculate their balance
        foreach ($referrals as $referral) {
            $totalBalance += mt5_total_balance($referral->id);
        }

        return $totalBalance;
    }
    public function getReferralsNetRebate($user,$days=null)
    {
        // Get all referrals
        $referrals = $user->referrals()->pluck('id');
        $netRebate = MetaDeal::whereIn('user_id',$referrals);
             if (null != $days) {
                 $netRebate->where('created_at', '>=', Carbon::now()->subDays((int) $days));
             }
            $netRebate = $netRebate->sum('lot_share');
        return $netRebate;
    }
    public function getReferralsNetVolume($user,$days=null)
    {
        // Get all referrals
        $referrals = $user->referrals()->pluck('id');
        $netVolume = MetaDeal::whereIn('user_id',$referrals);
        if (null != $days) {
            $netVolume->where('created_at', '>=', Carbon::now()->subDays((int) $days));
        }
        $netVolume = $netVolume->sum('volume');

        return  round($netVolume/10000, 2);
    }

    public function getReferralsLotShare($user)
    {
        $referrals = $user->referrals()->pluck('id');

        $totalLotShare = MetaDeal::whereIn('user_id', $referrals)->sum('lot_share');

        $currentMonthLotShareQuery = MetaDeal::whereIn('user_id', $referrals)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);

        $currentMonthLotShare = $currentMonthLotShareQuery->sum('lot_share');

        return [
            'total' => $totalLotShare,
            'current_month' => $currentMonthLotShare,
        ];
    }

// Ensure this function is part of a class where `mt5_total_balance` is defined.

    public function rules()
    {
        if(auth()->user()->ib_status != \App\Enums\IBStatus::APPROVED){
            return redirect()->route('user.multi-level.ib.dashboard');
        }
        $user = auth()->user(); // Get the authenticated user

        // Fetch UserIbRules with related schemas and rebate rules
        $userIbRules = UserIbRule::with([
            'rebateRule',
            'rebateRule.ibGroups.forexSchemas',
            'rebateRule.symbolGroups.symbols'
        ])
            ->where('user_id', $user->id)
            ->get();

        // Return the data to the view
        return view('frontend::partner.rules',  [
            'userIbRules' => $userIbRules,
        ]);
    }

    public function getSchemeRules(Request $request)
    {
        $user = auth()->user();
        $tagNames = $user->riskProfileTags()->pluck('name')->toArray();
        $levelOrder = $request->input('level_order', 1); // Default to level 1 if not provided

        // Fetch data based on selected level
        $swapMultiLevels = MultiLevel::active()
            ->whereHas('forexSchema', function ($query) use ($user, $tagNames) {
                $query->relevantForUser($user->country, $tagNames)
                    ->where('status', true);
            })
            ->where('type', MultiLevelType::SWAP)
            ->where('level_order', $levelOrder)
            ->get();

        $swapFreeMultiLevels = MultiLevel::active()
            ->whereHas('forexSchema', function ($query) use ($user, $tagNames) {
                $query->relevantForUser($user->country, $tagNames)
                    ->where('status', true);
            })
            ->where('type', MultiLevelType::SWAP_FREE)
            ->where('level_order', $levelOrder)
            ->get();

        // Render the partial view with the fetched data
        $html = view('frontend.prime_x.partner.include.__scheme_rules', compact('swapMultiLevels', 'swapFreeMultiLevels', 'levelOrder'))->render();

        return response()->json(['html' => $html]);
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

    /**
     * Get last 30 days IB commission from quarterly tables
     * IMPORTANT: This calculates commission for the LAST 30 DAYS (not current month)
     * The last 30 days may span across 2 quarters
     * 
     * Example: If today is Oct 16, we get transactions from Sep 16 to Oct 16
     * - Current quarter (2025_q3) might have: Oct 1-16 (16 days)
     * - Previous quarter (2025_q2) might have: Sep 16-30 (14 days)
     *
     * @param int $userId
     * @return float
     */
    private function getCurrentMonthIbCommissionFromQuarters($userId)
    {
        try {
            $totalCommission = 0;
            
            // Calculate date range for last 30 days
            $endDate = now();
            $startDate = now()->subDays(30);
            
            \Log::info("Calculating last 30 days commission for user {$userId} from {$startDate->toDateString()} to {$endDate->toDateString()}");
            
            // Get current quarter period
            $currentPeriod = IBTransactionPeriodService::getCurrentPeriod();
            $currentTableName = IBTransactionPeriodService::getTableName($currentPeriod);
            
            // Check current quarter table for last 30 days
            if (Schema::hasTable($currentTableName)) {
                $commission = DB::table($currentTableName)
                    ->where('user_id', $userId)
                    ->where('type', TxnType::IbBonus->value)
                    ->where('created_at', '>=', $startDate)
                    // ->where('created_at', '<=', $endDate)
                    ->sum('amount');
                
                $totalCommission += $commission;
                
                \Log::info("Current quarter ({$currentPeriod}) - Last 30 days commission for user {$userId}: {$commission}");
            }
            
            // ALWAYS check previous quarter for last 30 days
            // Because the 30-day period will almost always span into the previous quarter
            try {
                $previousPeriod = IBTransactionPeriodService::getPreviousPeriod($currentPeriod);
                $previousTableName = IBTransactionPeriodService::getTableName($previousPeriod);
                
                if (Schema::hasTable($previousTableName)) {
                    $previousCommission = DB::table($previousTableName)
                        ->where('user_id', $userId)
                        ->where('type', TxnType::IbBonus->value)
                        ->where('created_at', '>=', $startDate)
                        // ->where('created_at', '<=', $endDate)
                        ->sum('amount');
                    
                    $totalCommission += $previousCommission;
                    
                    \Log::info("Previous quarter ({$previousPeriod}) - Last 30 days commission for user {$userId}: {$previousCommission}");
                }
            } catch (\Exception $e) {
                \Log::warning('Error checking previous quarter for last 30 days commission: ' . $e->getMessage());
            }
            
            \Log::info("Total last 30 days commission for user {$userId}: {$totalCommission}");
            return $totalCommission;
        } catch (\Exception $e) {
            \Log::error('Error calculating last 30 days IB commission: ' . $e->getMessage());
            return 0;
        }
    }
}
