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
            $referralRelationships = ReferralRelationship::with('referralLink')->get();

            foreach ($referralRelationships as $referralRelationship) {
                $this->processReferralRelationship($referralRelationship);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to distribute rebates: ' . $e->getMessage());
            return 1;
        }
        return 0;
    }

    protected function processReferralRelationship($referralRelationship)
    {
        $notedParentData = $this->getValidParent($referralRelationship->user);

        if (!$notedParentData) {
            return false;
        }

        $notedParent = $notedParentData['user'];
        // Here, we assume that the parent’s level is now retrieved via a stable 'level_order' value.
        $stableLevel = $notedParentData['level'];

        $childUserId = $referralRelationship->user_id;
        $forexSchemas = $notedParent->ibGroup->rebateRules()
            ->with('forexSchemas')
            ->get()
            ->flatMap(fn($rebateRule) => $rebateRule->forexSchemas)
            ->pluck('id')
            ->unique();

        $realForexAccounts = ForexAccount::realActiveAccount($childUserId)
            ->whereIn('forex_schema_id', $forexSchemas)
            ->get();

        if ($realForexAccounts->isEmpty()) {
            return false;
        }

        foreach ($realForexAccounts as $realForexAccount) {
            $symbols = $this->getUserAssignedSymbols($notedParent, $realForexAccount);
            $lastDealTime = $this->getLastDeal($childUserId, $realForexAccount->login);
            $deals = $this->getMT5Deals($realForexAccount->login, $lastDealTime, $symbols);

            if (!$deals->isEmpty()) {
                $this->saveMT5Deals($deals, $childUserId, $referralRelationship, $notedParent, $stableLevel, $realForexAccount);
            }
        }
    }

    /**
     * Revised getValidParent() to use a stable value for the level.
     */
    protected function getValidParent($user)
    {
        // If the User model has a relationship to its Level, use it.
        while ($user && $user->ref_id) {
            $parent = User::find($user->ref_id);
            if ($parent && $parent->ib_group_id && $parent->ib_status === IBStatus::APPROVED) {
                // Preferably, retrieve the stable level order from a relationship (e.g., $parent->level->level_order).
                // If not available, fallback to a default (like 1).
                $stableLevelOrder = isset($parent->level) ? $parent->level->level_order : 1;
                return ['user' => $parent, 'level' => $stableLevelOrder];
            }
            $user = $parent;
        }
        return null;
    }

    protected function saveMT5Deals($deals, $childUserId, $referralRelationship, $notedParent, $stableLevel, $realForexAccount)
    {
        foreach ($deals as $deal) {
            if (!MetaDeal::where('login', $deal->Login)->where('deal', $deal->Deal)->where('order', $deal->Order)->exists()) {
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
                $this->distributeRebate($metaDeal, $childUserId, $referralRelationship, $notedParent, $stableLevel, $realForexAccount);
            }
        }
    }

    protected function distributeRebate($metaDeal, $childUserId, $referralRelationship, $notedParent, $stableLevel, $realForexAccount)
    {
        $shareDistribution = $this->calculateRebate($metaDeal->symbol, $notedParent, $stableLevel, $realForexAccount);
        if (empty($shareDistribution)) {
            return;
        }

        $currentUser = $referralRelationship->referralLink->user->id;
        $userHierarchy = [$currentUser];
        $currentLevel = $stableLevel;
        while ($currentUser && $currentLevel > 0) {
            $parentUser = User::find($currentUser)->ref_id;
            if ($parentUser) {
                $userHierarchy[] = $parentUser;
                $currentUser = $parentUser;
            }
            $currentLevel--;
        }
        $userHierarchy = array_reverse($userHierarchy);

        $childUser = User::find($childUserId);
        foreach ($userHierarchy as $index => $userId) {
            $levelIndex = ++$index;
            if (isset($shareDistribution[$levelIndex]) && $shareDistribution[$levelIndex] > 0) {
                $userAccount = get_user_account($userId, AccountBalanceType::IB_WALLET);
                $targetId = $userAccount->wallet_id;
                $amount = $shareDistribution[$levelIndex] * $metaDeal->lot_share;

                $transaction = Txn::new(
                    $amount, 0, $amount, 'system',
                    "IB Bonus via deal {$metaDeal->deal} on symbol {$metaDeal->symbol} from the account {$metaDeal->login} of {$childUser->full_name}",
                    TxnType::IbBonus, TxnStatus::Success, base_currency(),
                    $amount, $userId, $childUserId, 'User', $metaDeal->toArray(),
                    "IB Bonus via deal {$metaDeal->deal} on symbol {$metaDeal->symbol} from the account {$metaDeal->login} of {$childUser->full_name}",
                    $targetId, TxnTargetType::Wallet->value
                );
                $this->addBalance($transaction);
            }
        }

        $metaDeal->is_paid = Carbon::now();
        $metaDeal->save();
    }

    /**
     * Revised calculateRebate() to use stable level order instead of relying on level_id.
     */
    protected function calculateRebate($symbol, $notedParent, $stableLevel, $forexAccount)
    {
        $rebateRule = RebateRule::whereHas('ibGroups', fn($query) =>
        $query->where('ib_group_id', $notedParent->ibGroup->id))
            ->whereHas('forexSchemas', fn($query) =>
            $query->where('forex_schema_id', $forexAccount->forex_schema_id))
            ->whereHas('symbolGroups.symbols', fn($query) =>
            $query->where('symbol', $symbol))
            ->first();

        if (!$rebateRule) {
            return [];
        }

        $totalRebateAmount = $rebateRule->rebate_amount;

        $ibRule = UserIbRule::where('user_id', $notedParent->id)
            ->where('rebate_rule_id', $rebateRule->id)
            ->first();

        if (!$ibRule) {
            return [];
        }

        // Fetch the User IB Rule Level using the stable level_order (e.g., level_order instead of level_id)
        $ibRuleLevel = UserIbRuleLevel::where('user_ib_rule_id', $ibRule->id)
            ->whereHas('level', function ($query) use ($stableLevel) {
                $query->where('level_order', $stableLevel);
            })->first();

        // Initialize distribution array using the stable level value as the count
        $rebateDistribution = array_fill(1, $stableLevel, 0);
        $totalShared = 0;

        if ($ibRuleLevel) {
            $shares = UserIbRuleLevelShare::where('user_ib_rule_level_id', $ibRuleLevel->id)->get();
            foreach ($shares as $share) {
                // Assume that each share record now has a stable property like "level_order"
                $rebateDistribution[$share->level_order] = $share->share;
                $totalShared += $share->share;
            }
        }

            $remaining = $totalRebateAmount - $totalShared;
            $shifted = [];
            foreach ($rebateDistribution as $level => $share) {
                $shifted[$level + 1] = $share;
            }
            $rebateDistribution = [1 => $remaining] + $shifted;


        return $rebateDistribution;
    }

    protected function getUserAssignedSymbols($notedParent, $forexAccount): array
    {
        $filteredRebateRules = $this->getFilteredRebateRules($notedParent, $forexAccount);

        return $filteredRebateRules
            ->flatMap(fn($rebateRule) => $rebateRule->symbolGroups->flatMap(fn($symbolGroup) => $symbolGroup->symbols))
            ->pluck('symbol')
            ->unique()
            ->toArray();
    }

    protected function getFilteredRebateRules($notedParent, $forexAccount)
    {
        return RebateRule::whereHas('ibGroups', fn($query) =>
        $query->where('ib_group_id', $notedParent->ibGroup->id))
            ->whereHas('forexSchemas', fn($query) =>
            $query->where('forex_schema_id', $forexAccount->forex_schema_id))
            ->with(['symbolGroups.symbols'])
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

    protected function getMT5Deals($login, $lastDealTime, $symbols)
    {

//        $table = 'mt5_deals_' . Carbon::now()->year;
//        dd($table,$login,$lastDealTime,$sysmbols);
//        For Qorva table structure
        $table = 'mt5_deals';


        return DB::connection('mt5_db')
            ->table($table)
            ->select(['Login', 'Deal', 'Dealer', 'Order', 'Symbol', 'Time', 'Volume', 'VolumeClosed'])
            ->where('Login', $login)
            ->whereIn('Symbol', $symbols)
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
