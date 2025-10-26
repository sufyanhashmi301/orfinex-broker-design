<?php

namespace App\Console\Commands;

use App\Enums\AccountBalanceType;
use App\Enums\TxnStatus;
use App\Enums\TxnTargetType;
use App\Enums\TxnType;
use App\Models\MetaDeal;
use App\Models\ReferralRelationship;
use App\Models\User;
use App\Services\WalletService;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Facades\Txn\Txn;

class UnpaidRebateDistribution extends Command
{
    protected $signature = 'rebate:check-unpaid';
    protected $description = 'Check and process unpaid MetaDeals for rebate distribution';

    public function handle()
    {
        DB::beginTransaction();
        try {
            // Fetch all unpaid deals
            $unpaidDeals = MetaDeal::whereNull('is_paid')
//                ->where('id',9760)
                ->get();


            if ($unpaidDeals->isEmpty()) {
                $this->info('No unpaid deals found.');
                return 0;
            }

            foreach ($unpaidDeals as $deal) {
                $this->processUnpaidDeal($deal);
            }

            DB::commit();
            $this->info('Unpaid rebates processed successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error processing unpaid rebates: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    protected function processUnpaidDeal($metaDeal)
    {
        $referralRelationship = ReferralRelationship::where('user_id', $metaDeal->user_id)->first();

        if (!$referralRelationship) {
            return;
        }

        $notedParentData = $this->getValidParent($referralRelationship->referralLink->user);
        if (!$notedParentData) {
            return;
        }

        $notedParent = $notedParentData['user'];
        $notedLevel = $notedParentData['level'];

        $this->distributeRebate($metaDeal, $metaDeal->user_id, $referralRelationship, $notedParent, $notedLevel);
    }

    protected function getValidParent($user)
    {
        $level = 1;
        $maxLevel = \App\Models\Level::max('level_order');

        if (!$maxLevel) {
            return null;
        }

        if ($user && $user->ib_group_id && $user->ib_status === \App\Enums\IBStatus::APPROVED) {
            return ['user' => $user, 'level' => $level];
        }

        while ($user && $user->ref_id && $level < $maxLevel) {
            $parent = User::find($user->ref_id);

            if ($parent && $parent->ib_group_id && $parent->ib_status === \App\Enums\IBStatus::APPROVED) {
                return ['user' => $parent, 'level' => $level + 1];
            }

            $user = $parent;
            $level++;
        }

        return null;
    }

    protected function distributeRebate($metaDeal, $childUserId, $ReferralRelationship, $notedParent, $notedLevel)
    {
        $shareDistribution = $this->calculateRebate($metaDeal->symbol, $notedParent, $notedLevel);

        if (empty($shareDistribution)) {
            return;
        }

        $currentUser = $childUserId;
        $userHierarchy = [$childUserId];
        $currentLevel = $notedLevel;

        while ($currentUser && $currentLevel > 0) {
            $parentUser = User::find($currentUser)->ref_id;
            if ($parentUser) {
                $userHierarchy[] = $parentUser;
                $currentUser = $parentUser;
            }
            $currentLevel--;
        }

        $userHierarchy = array_reverse($userHierarchy);
        $totalLevels = count($userHierarchy);

        foreach ($userHierarchy as $index => $userId) {
            $levelIndex = $totalLevels - $index;

            if (isset($shareDistribution[$levelIndex])) {
                $share = $shareDistribution[$levelIndex];

                if ($share > 0) {
                    $userAccount = get_user_account($userId, AccountBalanceType::IB_WALLET);
                    $targetId = $userAccount->wallet_id;
                    $amount = BigDecimal::of($share)->multipliedBy(BigDecimal::of($metaDeal->lot_share))->toFloat();

                    $txn = new Txn();
                    $transaction = $txn->new(
                        $amount, 0, $amount, 'system',
                        "IB Bonus via deal {$metaDeal->deal} on symbol {$metaDeal->symbol} from account {$metaDeal->login}",
                        TxnType::IbBonus, TxnStatus::Success, base_currency(),
                        $amount, $userId, $childUserId, 'User', $metaDeal->toArray(),
                        "IB Bonus via deal {$metaDeal->deal} on symbol {$metaDeal->symbol} from account {$metaDeal->login}", $targetId, TxnTargetType::Wallet->value
                    );

                    $this->addBalance($transaction);
                }
            }
        }

        // Mark deal as paid
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
        $ibRule = \App\Models\UserIbRule::where('user_id', $notedParent->id)
            ->where('rebate_rule_id', $rebateRule->id)
            ->first();

        if (!$ibRule) {
            return [];
        }

        $ibRuleLevel = \App\Models\UserIbRuleLevel::where('user_ib_rule_id', $ibRule->id)
            ->where('level_id', $notedLevel)
            ->first();

        $shares = [];
        if ($ibRuleLevel) {
            $shares = \App\Models\UserIbRuleLevelShare::where('user_ib_rule_level_id', $ibRuleLevel->id)->get();
        }

        $rebateDistribution = [];
        $totalShared = 0;

        for ($i = 1; $i <= $notedLevel; $i++) {
            $rebateDistribution[$i] = 0;
        }

        foreach ($shares as $share) {
            $rebateDistribution[$share->level_id] = $share->share;
            $totalShared += $share->share;
        }

        if ($totalShared == 0) {
            $rebateDistribution[++$notedLevel] = max(0, $totalRebateAmount);
        } else {
            $rebateDistribution[++$notedLevel] = max(0, $totalRebateAmount - $totalShared);
        }

        return $rebateDistribution;
    }

    protected function addBalance($transaction)
    {
        $userAccount = get_user_account_by_wallet_id($transaction->target_id);

        $wallet = new WalletService();
        $ledgerBalance = $wallet->getLedgerBalance($userAccount->id);
        $wallet->createCreditLedgerEntry($transaction, $ledgerBalance);

        if ($transaction->target_type == TxnTargetType::Wallet->value) {
        $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->amount))->toFloat();
        $userAccount->save();
    }
    }
}
