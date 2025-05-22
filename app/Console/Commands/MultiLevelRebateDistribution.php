<?php

namespace App\Console\Commands;

use App\Enums\AccountBalanceType;
use App\Enums\IBStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnTargetType;
use App\Enums\TxnType;
use App\Jobs\ProcessReferralRebate;
use App\Models\ForexAccount;
use App\Models\Ledger;
use App\Models\MetaDeal;
use App\Models\RebateRule;
use App\Models\ReferralRelationship;
use App\Models\Symbol;
use App\Models\User;
use App\Models\UserIbRule;
use App\Models\UserIbRuleLevel;
use App\Models\UserIbRuleLevelShare;
use App\Models\Level;
use App\Models\Transaction;
use App\Services\WalletService;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Txn;

class MultiLevelRebateDistribution extends Command
{
    protected $signature = 'rebate:distribution';
    protected $description = 'Multi-Level IB Rebate Distribution';


    public function handle()
    {
        try {
            ReferralRelationship::with('referralLink')
                ->chunkById(500, function ($referrals) {
                    foreach ($referrals as $referral) {
                        try {
                            ProcessReferralRebate::dispatch($referral->id);
//                            Log::info("Successfully dispatched referral ID: {$referral->id}");
                        } catch (Throwable $e) {
//                            Log::error("Failed to dispatch rebate job for referral ID {$referral->id}: {$e->getMessage()}");
                        }
                    }
                });
        } catch (Throwable $e) {
            Log::error("Rebate distribution job dispatch failed: {$e->getMessage()}");
            return 1;
        }

        return 0;
    }

    public function processReferralRelationship($referral)
    {
//        dd($referral->user);
        $parentData = $this->getValidParent($referral->user);

        if (!$parentData) return;

        $parent = $parentData['user'];
        $level = $parentData['level'];
        $childUserId = $referral->user_id;

        $forexSchemas = $parent->ibGroup->rebateRules()
            ->with('forexSchemas')
            ->get()
            ->flatMap(fn($rebateRule) => $rebateRule->forexSchemas)
            ->pluck('id')
            ->unique();

        $accounts = ForexAccount::realActiveAccount($childUserId)
            ->whereIn('forex_schema_id', $forexSchemas)
            ->get();

        foreach ($accounts as $account) {
            $symbols = $this->getUserAssignedSymbols($parent, $account);
            $lastDealTime = $this->getLastDeal($childUserId, $account->login);
            $deals = $this->getMT5Deals($account->login, $lastDealTime, $symbols);
//            dd($deals);

            if (!$deals->isEmpty()) {
                $this->saveAndDistributeDeals($deals, $childUserId, $referral, $parent, $level, $account);
            }
        }
    }

    protected function saveAndDistributeDeals($deals, $childUserId, $referral, $parent, $level, $account)
    {
        foreach ($deals as $deal) {
            $exists = MetaDeal::where('login', $deal->Login)
                ->where('deal', $deal->Deal)
                ->where('order', $deal->Order)
                ->exists();

            if ($exists) continue;

            $metaDeal = MetaDeal::create([
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
            ]);

            $this->distributeRebate($metaDeal, $childUserId, $referral, $parent, $level, $account);
        }
    }

    protected function distributeRebate($metaDeal, $childUserId, $referral, $parent, $level, $account)
    {
        $distribution = $this->calculateRebate($metaDeal->symbol, $parent, $level, $account);
        if (empty($distribution)) return;

        $currentUser = $referral->referralLink->user->id;
        $hierarchy = [$currentUser];
        $currentLevel = $level;

        while ($currentUser && $currentLevel > 0) {
            $parentUser = User::find($currentUser)->ref_id;
            if ($parentUser) {
                $hierarchy[] = $parentUser;
                $currentUser = $parentUser;
            }
            $currentLevel--;
        }

        $hierarchy = array_reverse($hierarchy);
        $childUser = User::find($childUserId);

        foreach ($hierarchy as $index => $userId) {
            $levelIndex = ++$index;
            if (!isset($distribution[$levelIndex]) || $distribution[$levelIndex] <= 0) continue;

            $share = $distribution[$levelIndex];
            $amount = $share * $metaDeal->lot_share;

            $account = get_user_account($userId, AccountBalanceType::IB_WALLET);
            $walletId = $account->wallet_id;
            $description = "IB Bonus via deal {$metaDeal->deal} on symbol {$metaDeal->symbol} from account {$metaDeal->login} of {$childUser->full_name}";

            if (Transaction::isDuplicateIbBonus($userId, $childUserId, $description, $amount)) continue;

            $transaction = Txn::new(
                $amount, 0, $amount, 'system',
                $description,
                TxnType::IbBonus, TxnStatus::Success, base_currency(),
                $amount, $userId, $childUserId, 'User', $metaDeal->toArray(),
                $description, $walletId, TxnTargetType::Wallet->value
            );

            $this->safeAddBalance($transaction);
        }

        $metaDeal->is_paid = Carbon::now();
        $metaDeal->save();
    }

    protected function safeAddBalance($transaction, $retries = 3)
    {
        for ($i = 0; $i < $retries; $i++) {
            try {
                DB::transaction(function () use ($transaction) {
                    $account = \App\Models\Account::where('wallet_id', $transaction->target_id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $wallet = new WalletService();
                    $ledgerBalance = $wallet->getLedgerBalance($account->id);
                    $wallet->createCreditLedgerEntry($transaction, $ledgerBalance);

                    if ($transaction->target_type == TxnTargetType::Wallet->value) {
                        $account->amount = BigDecimal::of($account->amount)->plus(BigDecimal::of($transaction->amount));
                        $account->save();
                    }
                });
                return;
            } catch (Throwable $e) {
                if ($i === $retries - 1) throw $e;
                usleep(100000); // wait 100ms before retry
            }
        }
    }

    protected function calculateRebate($symbol, $parent, $level, $account)
    {
        $rule = RebateRule::whereHas('ibGroups', fn($q) => $q->where('ib_group_id', $parent->ibGroup->id))
            ->whereHas('forexSchemas', fn($q) => $q->where('forex_schema_id', $account->forex_schema_id))
            ->whereHas('symbolGroups.symbols', fn($q) => $q->where('symbol', $symbol))
            ->first();

        if (!$rule) return [];

        $total = $rule->rebate_amount;
        $userRule = UserIbRule::where('user_id', $parent->id)
            ->where('rebate_rule_id', $rule->id)
            ->first();

        if (!$userRule) return [];

        $ruleLevel = UserIbRuleLevel::where('user_ib_rule_id', $userRule->id)
            ->where('level_id', $level)
            ->first();

        $distribution = array_fill(1, $level, 0);
        $shared = 0;

        if ($ruleLevel) {
            $shares = UserIbRuleLevelShare::where('user_ib_rule_level_id', $ruleLevel->id)->get();
            foreach ($shares as $share) {
                $distribution[$share->level_id] = $share->share;
                $shared += $share->share;
            }
        }

        $remaining = $total - $shared;
        $shifted = [];

        foreach ($distribution as $lvl => $share) {
            $shifted[$lvl + 1] = $share;
        }

        return [1 => $remaining] + $shifted;
    }

    protected function getUserAssignedSymbols($parent, $account): array
    {
        $rules = RebateRule::whereHas('ibGroups', fn($q) => $q->where('ib_group_id', $parent->ibGroup->id))
            ->whereHas('forexSchemas', fn($q) => $q->where('forex_schema_id', $account->forex_schema_id))
            ->with('symbolGroups.symbols')
            ->get();

        return $rules->flatMap(fn($r) => $r->symbolGroups->flatMap(fn($g) => $g->symbols))
            ->pluck('symbol')
            ->unique()
            ->toArray();
    }

    protected function getLastDeal($userId, $login)
    {
        return MetaDeal::where('login', $login)
            ->where('user_id', $userId)
            ->latest('time')
            ->value('time') ?: Carbon::now()->startOfDay();
    }

    protected function getMT5Deals($login, $lastTime, $symbols)
    {
        $table = 'mt5_deals_' . Carbon::now()->year;
        //For Qorva
//        $table = 'mt5_deals';

        return DB::connection('mt5_db')
            ->table($table)
            ->select(['Login', 'Deal', 'Dealer', 'Order', 'Symbol', 'Time', 'Volume', 'VolumeClosed'])
            ->where('Login', $login)
            ->whereIn('Symbol', $symbols)
            ->where('Time', '>', $lastTime)
            ->where('Volume', '>', 0)
            ->whereColumn('Volume', 'VolumeClosed')
            ->get();
    }

    protected function getValidParent($user)
    {
        $level = 0;
        $maxLevel = Level::max('level_order');
        if (!$maxLevel) return null;

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
}
