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
use App\Models\ReferralRelationship;
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
//                ->where('user_id',1197)
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
        $notedParentData = $this->getValidParent($ReferralRelationship->referralLink->user);
//        dd($notedParentData);

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

        $realForexAccounts = ForexAccount::realActiveAccount($childUserId)
            ->whereIn('forex_schema_id', $forexSchemas)
            ->orderBy('balance', 'desc')
            ->get();

        if ($realForexAccounts->isEmpty()) {
            return false;
        }
        $symbols = $this->getUserAssignedSymbols($notedParent);
        // dd($realForexAccounts);

        foreach ($realForexAccounts as $realForexAccount) {
            // dd($realForexAccount->login);
            $lastDealTime = $this->getLastDeal($childUserId, $realForexAccount->login);
            // dd($lastDealTime);
            $deals = $this->getMT5Deals($realForexAccount->login, $lastDealTime, $symbols);
            // dd($deals);
            $this->saveMT5Deals($deals, $childUserId, $ReferralRelationship, $notedParent, $notedLevel);
        }
    }

    protected function getValidParent($user)
    {
        $level = 1; // Start from level 1
        $maxLevel = Level::max('level_order'); // Get max level from levels table

        if (!$maxLevel) {
            return null; // Ignore further processing if no levels exist
        }

        if ($user && $user->ib_group_id && $user->ib_status === IBStatus::APPROVED) {
            return ['user' => $user, 'level' => $level];
        }

        while ($user && $user->ref_id && $level < $maxLevel) {
            $parent = User::find($user->ref_id);

            if ($parent && $parent->ib_group_id && $parent->ib_status === IBStatus::APPROVED) {
                return ['user' => $parent, 'level' => $level + 1];
            }

            $user = $parent;
            $level++;
        }

        return null;
    }

    protected function saveMT5Deals($deals, $childUserId, $ReferralRelationship, $notedParent, $notedLevel)
    {
        foreach ($deals as $deal) {
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
//            $metaDeal = MetaDeal::find(9900);
            // dd($metaDeal);
            $this->distributeRebate($metaDeal, $childUserId, $ReferralRelationship, $notedParent, $notedLevel);
        }
    }

    protected function distributeRebate($metaDeal, $childUserId, $ReferralRelationship, $notedParent, $notedLevel)
    {
        $shareDistribution = $this->calculateRebate($metaDeal->symbol, $notedParent, $notedLevel);
        // dd($shareDistribution);

        if (empty($shareDistribution)) {
            return;
        }

        $currentUser = $childUserId;
        $userHierarchy = [$childUserId];
        $currentLevel = $notedLevel; // Highest level for child, decrement for parents

        // Get parents in hierarchy order
        while ($currentUser && $currentLevel > 0) {
            $parentUser = User::find($currentUser)->ref_id;
            if ($parentUser) {
                $userHierarchy[] = $parentUser;
                $currentUser = $parentUser;
            }
            $currentLevel--;
        }
//        dd($userHierarchy);

        // Reverse the order to distribute correctly
        $userHierarchy = array_reverse($userHierarchy);
        $totalLevels = count($userHierarchy);

        foreach ($userHierarchy as $index => $userId) {
            $levelIndex = $totalLevels - $index; // Assign correct level share

            if (isset($shareDistribution[$levelIndex])) {
                $share = $shareDistribution[$levelIndex];

                if ($share > 0) {
                    $userAccount = get_user_account($userId, AccountBalanceType::IB_WALLET);
                    $targetId = $userAccount->wallet_id;
                    $amount = $share * $metaDeal->lot_share;

                    $transaction = Txn::new(
                        $amount, 0, $amount, 'system',
                        "IB Bonus via deal {$metaDeal->deal} on symbol {$metaDeal->symbol}  from account {$metaDeal->login}",
                        TxnType::IbBonus, TxnStatus::Success, base_currency(),
                        $amount, $userId, $childUserId, 'User', $metaDeal->toArray(),
                        "IB Bonus via deal {$metaDeal->deal} on symbol {$metaDeal->symbol}  from account {$metaDeal->login}", $targetId, TxnTargetType::Wallet->value
                );

                $this->addBalance($transaction);
            }
            }
        }

        $metaDeal->is_paid = Carbon::now();
        $metaDeal->save();
    }

    protected function calculateRebate($symbol, $notedParent, $notedLevel)
    {
        $rebateRule = $notedParent->ibGroup->rebateRules()
            ->whereHas('symbolGroups.symbols', fn($query) => $query->where('symbol', $symbol))
            ->first();

        if (!$rebateRule) {
            return [];
        }

        $totalRebateAmount = $rebateRule->rebate_amount;
        // dd($totalRebateAmount);
        $ibRule = UserIbRule::where('user_id', $notedParent->id)
            ->where('rebate_rule_id', $rebateRule->id)
            ->first();
        // dd($ibRule);

        if (!$ibRule) {
            return [];
        }

        $ibRuleLevel = UserIbRuleLevel::where('user_ib_rule_id', $ibRule->id)
            ->where('level_id', $notedLevel)
            ->first();

        $shares = [];

        if ($ibRuleLevel) {
            $shares = UserIbRuleLevelShare::where('user_ib_rule_level_id', $ibRuleLevel->id)->get();
        }


        $rebateDistribution = [];
        $totalShared = 0;

        // Initialize all levels up to notedLevel as 0
        for ($i = 1; $i <= $notedLevel; $i++) {
            $rebateDistribution[$i] = 0;
        }

        foreach ($shares as $share) {
            $rebateDistribution[$share->level_id] = $share->share;
            $totalShared += $share->share;
        }

        // If no shares found, assign full rebate to the noted parent at level 1
        if ($totalShared == 0) {
            $rebateDistribution[++$notedLevel] = max(0, $totalRebateAmount);
        } else {
            // Assign the remaining rebate to the highest recorded level
            $rebateDistribution[++$notedLevel] = max(0, $totalRebateAmount - $totalShared);
        }

        return $rebateDistribution;
    }

    protected function getUserAssignedSymbols($notedParentData): array
    {
        return $notedParentData->ibGroup->rebateRules()
            ->with('symbolGroups.symbols')
            ->get()
            ->flatMap(fn($rebateRule) => $rebateRule->symbolGroups->flatMap(fn($symbolGroup) => $symbolGroup->symbols))
            ->pluck('symbol') // Extracts the 'symbol' column
            ->unique() // Removes duplicates
            ->toArray(); // Converts to an array
    }
    protected function getLastDeal($childUserId, $login)
    {
        return MetaDeal::where('login', $login)
            ->where('user_id', $childUserId)
            ->latest('time')
            ->value('time') ?: Carbon::now()->startOfDay();
    }

    protected function getMT5Deals($login, $lastDealTime, $sysmbols)
    {
        $table = 'mt5_deals_' . Carbon::now()->year;
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
