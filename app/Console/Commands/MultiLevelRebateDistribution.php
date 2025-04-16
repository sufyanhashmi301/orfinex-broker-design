<?php

namespace App\Console\Commands;

use App\Enums\AccountBalanceType;
use App\Enums\IBStatus;
use App\Enums\TransactionCalcType;
use App\Models\ForexAccount;
use App\Models\Ledger;
use App\Models\MetaDeal;
use App\Enums\TxnStatus;
use App\Enums\TxnTargetType;
use App\Enums\TxnType;
use App\Models\RebateRule;
use App\Models\ReferralRelationship;
use App\Models\Symbol;
use App\Models\User;
use App\Models\UserIbRule;
use App\Models\UserIbRuleLevel;
use App\Models\UserIbRuleLevelShare;
use App\Models\Level;
use App\Services\WalletService;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Txn;

class MultiLevelRebateDistribution extends Command
{
    protected $signature = 'rebate:distribution';
    protected $description = 'Multi-Level IB Rebate Distribution';

    public function handle()
    {
        
        DB::beginTransaction();
        try {
            $ReferralRelationships = ReferralRelationship::with('referralLink')
//                ->where('user_id',3216)
                ->get();

            foreach ($ReferralRelationships as $ReferralRelationship) {
                $this->processReferralRelationship($ReferralRelationship);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to distribute rebates: ' . $e->getMessage());
            return 1;
        }
        return 0;
    }

    protected function processReferralRelationship($ReferralRelationship)
    {
        $notedParentData = $this->getValidParent($ReferralRelationship->user);
        // dd($notedParentData);

        if (!$notedParentData) {
            return false;
        }

        $notedParent = $notedParentData['user'];
        $notedLevel = $notedParentData['level']; // Get the valid IB parent's level

        $childUserId = $ReferralRelationship->user_id;
        $forexSchemas = $notedParent->ibGroup->rebateRules()
            ->with('forexSchemas')
            ->get()
            ->flatMap(fn($rebateRule) => $rebateRule->forexSchemas)
            ->pluck('id')
            ->unique();
//dd($forexSchemas);
        $realForexAccounts = ForexAccount::realActiveAccount($childUserId)
            ->whereIn('forex_schema_id', $forexSchemas) // Ensure it belongs to the IB's allowed schemas
            ->get();

        if ($realForexAccounts->isEmpty()) {
            return false;
        }


        foreach ($realForexAccounts as $realForexAccount) {
            // dd($realForexAccount);
            $symbols = $this->getUserAssignedSymbols($notedParent, $realForexAccount);
            // dd($symbols);
//
            $lastDealTime = $this->getLastDeal($childUserId, $realForexAccount->login);
            // dd($lastDealTime);
            $deals = $this->getMT5Deals($realForexAccount->login, $lastDealTime, $symbols);
            //   dd($deals);

            if (!$deals->isEmpty()) {
                $this->saveMT5Deals($deals, $childUserId, $ReferralRelationship, $notedParent, $notedLevel, $realForexAccount);
            }
        }

    }

    protected function getValidParent($user)
    {
        // dd($user);
        $level = 0; // Start from level 0
        $maxLevel = Level::max('level_order'); // Get max level from levels table
        if (!$maxLevel) {
            return null; // Ignore further processing if no levels exist
        }
        while ($user && $user->ref_id && $level <= $maxLevel) {
            $parent = User::find($user->ref_id);

            if ($parent && $parent->ib_group_id && $parent->ib_status === IBStatus::APPROVED) {
                return ['user' => $parent, 'level' => $level];
            }

            $user = $parent;
            $level++;
        }

        return null;
    }
    protected function saveMT5Deals($deals, $childUserId, $ReferralRelationship, $notedParent, $notedLevel, $realForexAccount)
    {
        foreach ($deals as $deal) {
            if(!MetaDeal::where('login',$deal->Login)->where('deal',$deal->Deal)->where('order',$deal->Order)->exists()) {
                $data = [
                    'user_id' => $childUserId,
                    'login' => $deal->Login,
                    'deal' => $deal->Deal,
                    'dealer' => $deal->Dealer,
                    'order' => $deal->Order,
                    'symbol' => $deal->Symbol,
                    'volume' => $deal->Volume,
                    'volume_closed' => $deal->VolumeClosed,
                    'lot_share' => BigDecimal::of($deal->Volume)->dividedBy(BigDecimal::of(10000), 2),
                    'time' => $deal->Time
                ];
                $metaDeal = MetaDeal::create($data);
                // $metaDeal = MetaDeal::find(13125);
                // dd($metaDeal);
                $this->distributeRebate($metaDeal, $childUserId, $ReferralRelationship, $notedParent, $notedLevel, $realForexAccount);
            }
        }
    }

    protected function distributeRebate($metaDeal, $childUserId, $ReferralRelationship, $notedParent, $notedLevel, $realForexAccount)
    {
        $shareDistribution = $this->calculateRebate($metaDeal->symbol, $notedParent, $notedLevel, $realForexAccount);
        // dd($shareDistribution);

        if (empty($shareDistribution)) {
            return;
        }

        $currentUser = $ReferralRelationship->referralLink->user->id;
        $userHierarchy = [$currentUser];
        $currentLevel = $notedLevel; // Highest level for child, decrement for parents
// dd($userHierarchy);
        // Get parents in hierarchy order
        while ($currentUser && $currentLevel > 0) {
            $parentUser = User::find($currentUser)->ref_id;
            if ($parentUser) {
                $userHierarchy[] = $parentUser;
                $currentUser = $parentUser;
            }
            $currentLevel--;
        }
        // dd($userHierarchy);

        // Reverse the order to distribute correctly
        $userHierarchy = array_reverse($userHierarchy);
        $totalLevels = count($userHierarchy);
        //user have the trade
        $childUser = User::find($childUserId);
        // dd($userHierarchy,$totalLevels);

        foreach ($userHierarchy as $index => $userId) {
            $levelIndex = ++$index; // Assign correct level share
// dd($userHierarchy,$shareDistribution,$levelIndex,$shareDistribution[$levelIndex]);
            if (isset($shareDistribution[$levelIndex])) {
                $share = $shareDistribution[$levelIndex];

                if ($share > 0) {
                    $userAccount = get_user_account($userId, AccountBalanceType::IB_WALLET);
                    $targetId = $userAccount->wallet_id;
                    $amount = $share * $metaDeal->lot_share;

                    $transaction = Txn::new(
                        $amount, 0, $amount, 'system',
                        "IB Bonus via deal {$metaDeal->deal} on symbol {$metaDeal->symbol} from the account {$metaDeal->login} of {$childUser->full_name}",
                        TxnType::IbBonus, TxnStatus::Success, base_currency(),
                        $amount, $userId, $childUserId, 'User', $metaDeal->toArray(),
                        "IB Bonus via deal {$metaDeal->deal} on symbol {$metaDeal->symbol} from the account {$metaDeal->login} of {$childUser->full_name}", $targetId, TxnTargetType::Wallet->value
                );

                $this->addBalance($transaction);
                }
            }
        }

        $metaDeal->is_paid = Carbon::now();
        $metaDeal->save();
    }

    protected function calculateRebate($symbol, $notedParent, $notedLevel, $forexAccount)
    {
        // Fetch the rebate rule that matches the IB Group, Forex Schema, and Symbol
        $rebateRule = RebateRule::whereHas('ibGroups', fn($query) =>
        $query->where('ib_group_id', $notedParent->ibGroup->id)) // Ensure it belongs to the IB Group
        ->whereHas('forexSchemas', fn($query) =>
        $query->where('forex_schema_id', $forexAccount->forex_schema_id)) // Ensure schema matches
        ->whereHas('symbolGroups.symbols', fn($query) =>
        $query->where('symbol', $symbol)) // Ensure the rule applies to the symbol
        ->first();

        // dd($rebateRule);
        if (!$rebateRule) {
            return [];
        }

        $totalRebateAmount = $rebateRule->rebate_amount;

        // Fetch the IB Rule for the parent user
        $ibRule = UserIbRule::where('user_id', $notedParent->id)
            ->where('rebate_rule_id', $rebateRule->id)
            ->first();
        // dd($ibRule);

        if (!$ibRule) {
            return [];
        }

        // Find User IB Rule Level
        $ibRuleLevel = UserIbRuleLevel::where('user_ib_rule_id', $ibRule->id)
            ->where('level_id', $notedLevel)
            ->first();

        // Initialize default rebate distribution
        $rebateDistribution = array_fill(1, $notedLevel, 0);
        $totalShared = 0;
        // dd($rebateDistribution);

        if ($ibRuleLevel) {
            $shares = UserIbRuleLevelShare::where('user_ib_rule_level_id', $ibRuleLevel->id)->get();
            // dd($shares);
            foreach ($shares as $share) {
                $rebateDistribution[$share->level_id] = $share->share;
                $totalShared += $share->share;
            }
        }
        // dd($rebateDistribution,$totalShared,$totalRebateAmount);

        // Assign remaining rebate to the highest level
            $remaining = $totalRebateAmount - $totalShared;

            // Shift all existing levels down by 1
            $shifted = [];
            foreach ($rebateDistribution as $level => $share) {
                $shifted[$level + 1] = $share;
            }

            // Put the remaining rebate at level 1
            $rebateDistribution = [1 => $remaining] + $shifted;

        return $rebateDistribution;
    }

    protected function getUserAssignedSymbols($notedParentData, $forexAccount): array
    {
        $filteredRebateRules = $this->getFilteredRebateRules($notedParentData, $forexAccount);

        return $filteredRebateRules
            ->flatMap(fn($rebateRule) => $rebateRule->symbolGroups->flatMap(fn($symbolGroup) => $symbolGroup->symbols))
            ->pluck('symbol') // Extract only the 'symbol' column
            ->unique() // Remove duplicates
            ->toArray(); // Convert to an array
    }

    protected function getFilteredRebateRules($notedParentData, $forexAccount)
    {
//        dd($notedParentData->ibGroup->id);
        return RebateRule::whereHas('ibGroups', fn($query) =>
        $query->where('ib_group_id', $notedParentData->ibGroup->id)) // Ensure the rebate rule belongs to the IB Group
        ->whereHas('forexSchemas', fn($query) =>
        $query->where('forex_schema_id', $forexAccount->forex_schema_id)) // Ensure the rebate rule is attached to the Forex Schema
        ->with(['symbolGroups.symbols']) // Load related symbols to avoid N+1 problem
        ->get();
    }


    protected function getLastDeal($childUserId, $login)
    {
        return Carbon::now()->subDay(8)->startOfDay();
        return MetaDeal::where('login', $login)
            ->where('user_id', $childUserId)
            ->latest('time')
            ->value('time') ?: Carbon::now()->startOfDay();
    }

    protected function getMT5Deals($login, $lastDealTime, $sysmbols)
    {
//        $table = 'mt5_deals_' . Carbon::now()->year;
        $table = 'mt5_deals';
//        dd($table,$login,$lastDealTime,$sysmbols);

        return DB::connection('mt5_db')
            ->table($table)
            ->select(['Login', 'Deal', 'Dealer', 'Order', 'Symbol', 'Time', 'Volume', 'VolumeClosed'])
            ->where('Login', $login)
            ->whereIn('Symbol', $sysmbols)
            ->where('Time', '>', $lastDealTime)
            ->where('Volume', '>', 0)
            ->whereColumn('Volume', 'VolumeClosed')
            ->get();
    }
    protected function addBalance($transaction)
    {
        $userAccount = get_user_account_by_wallet_id($transaction->target_id);

        $wallet = new WalletService();
        $ledgerBalance = $wallet->getLedgerBalance($userAccount->id);
        $wallet->createCreditLedgerEntry($transaction, $ledgerBalance);
        if ($transaction->target_type == TxnTargetType::Wallet->value) {
        $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->amount));
        $userAccount->save();
    }
    }

}
