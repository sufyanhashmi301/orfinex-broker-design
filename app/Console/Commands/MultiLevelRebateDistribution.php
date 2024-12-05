<?php

namespace App\Console\Commands;

use App\Enums\AccountBalanceType;
use App\Enums\TxnStatus;
use App\Enums\TxnTargetType;
use App\Enums\TxnType;
use App\Models\ForexAccount;
use App\Models\MetaDeal;
use App\Models\ReferralRelationship;
use App\Models\User;
use App\Models\UserIbRule;
use App\Services\WalletService;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Txn;

class MultiLevelRebateDistribution extends Command
{
    protected $signature = 'rebate:distribution';
    protected $description = 'Distribute IB rebates and sub-IB shares';

    public function handle()
    {
        DB::beginTransaction();
        try {
            // Step 1: Get users with ib_group_id
            $usersWithIbGroup = User::whereNotNull('ib_group_id')
                ->where('id',524)
                ->with('ibGroup')->get();


            foreach ($usersWithIbGroup as $user) {
//                dd($user);
                // Process Forex accounts for this user
                $this->processUserForexAccounts($user);

                // Process referral relationships under this user
                $referralRelationships = $user->referralRelationship()->with('referralLink')->get();
                foreach ($referralRelationships as $referralRelationship) {
                    $this->processReferralRelationship($referralRelationship);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Rebate distribution failed: ' . $e->getMessage());
            $this->error('Failed to distribute rebates: ' . $e->getMessage());
            return 1;
        }
        return 0;
    }

    protected function processUserForexAccounts(User $user)
    {
        $realForexAccounts = $this->getRealForexAccounts($user->id);
        $symbols = $this->getUserAssignedSymbols($user);

        foreach ($realForexAccounts as $forexAccount) {
            $lastDealTime = $this->getLastDealTime($user->id, $forexAccount->login);
            $deals = $this->getMT5Deals($forexAccount->login, $lastDealTime, $symbols);

            foreach ($deals as $deal) {
                $metaDeal = MetaDeal::create([
                    'user_id' => $user->id,
                    'login' => $deal->Login,
                    'deal' => $deal->Deal,
                    'dealer' => $deal->Dealer,
                    'order' => $deal->Order,
                    'symbol' => $deal->Symbol,
                    'volume' => $deal->Volume,
                    'volume_closed' => $deal->VolumeClosed,
                    'lot_share' => BigDecimal::of($deal->Volume)->dividedBy(BigDecimal::of(10000), 2),
                    'time' => $deal->Time,
                ]);

                $this->distributeRebate($metaDeal, $user->id, $user->ibGroup);
            }
        }
    }

    protected function processReferralRelationship(ReferralRelationship $referralRelationship)
    {
        $childUserId = $referralRelationship->user_id;
        $realForexAccounts = $this->getRealForexAccounts($childUserId);
        $symbols = $this->getUserAssignedSymbols($referralRelationship->referralLink->user);

        foreach ($realForexAccounts as $forexAccount) {
            $lastDealTime = $this->getLastDealTime($childUserId, $forexAccount->login);
            $deals = $this->getMT5Deals($forexAccount->login, $lastDealTime, $symbols);

            foreach ($deals as $deal) {
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
                    'time' => $deal->Time,
                ]);

                $this->distributeRebate($metaDeal, $childUserId, $referralRelationship);
            }
        }
    }

    protected function getRealForexAccounts(int $userId)
    {
        return ForexAccount::realActiveAccount($userId)
            ->orderBy('balance', 'desc')
            ->get();
    }

    protected function getUserAssignedSymbols($groupOrUser): array
    {
        return $groupOrUser->ibGroup->rebateRules()
            ->with('symbolGroups.symbols')
            ->get()
            ->flatMap(fn($rule) => $rule->symbolGroups->flatMap(fn($group) => $group->symbols))
            ->pluck('symbol')
            ->unique()
            ->toArray();
    }

    protected function getLastDealTime(int $userId, string $login)
    {
        return MetaDeal::where('login', $login)
            ->where('user_id', $userId)
            ->latest('time')
            ->value('time') ?: Carbon::now()->startOfDay()->subDay(1211);
    }

    protected function getMT5Deals(string $login, $lastDealTime, array $symbols)
    {
        $table = 'mt5_deals_' . Carbon::now()->year;

//        dd($lastDealTime);
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

    protected function distributeRebate(MetaDeal $metaDeal, int $userId, $relationshipOrGroup)
    {
        $parentId = $relationshipOrGroup->referralLink->user_id ?? $userId;
        $user = User::find($userId);
//        dd($parentId,$userId);

        $shares = $this->calculateRebate($metaDeal->symbol, $relationshipOrGroup);
        $this->createRebateTransaction($shares['parentShare'] * $metaDeal->lot_share, $parentId,$userId, $user->full_name, $metaDeal->login);
        $this->createRebateTransaction($shares['childShare'] * $metaDeal->lot_share,$userId, $userId, $user->full_name, $metaDeal->login);

        $metaDeal->is_paid = Carbon::now();
        $metaDeal->save();
    }

    protected function calculateRebate(string $symbol, $relationshipOrGroup): array
    {
        $rebateRule = $relationshipOrGroup->rebateRules()
            ->whereHas('symbolGroups.symbols', fn($query) => $query->where('symbol', $symbol))
            ->first();

        if (!$rebateRule) {
            return ['parentShare' => 0, 'childShare' => 0];
        }

        $totalRebateAmount = $rebateRule->rebate_amount;
        $childShare = 0;
        if(isset($relationshipOrGroup->referralLink)) {
            $ibShare = UserIbRule::where('rebate_rule_id', $rebateRule->id)->where('user_id', $relationshipOrGroup->referralLink->user_id)->first();

            $childShare = $ibShare ? $ibShare->sub_ib_share : 0;
        }
        $parentShare = max(0, $totalRebateAmount - $childShare);

        return ['parentShare' => $parentShare, 'childShare' => $childShare];
    }

    protected function createRebateTransaction($amount,$toUser, $fromUser, $childUserName, $login)
    {
        if ($amount > 0) {
            $targetType = AccountBalanceType::IB_WALLET;
            $userAccount = get_user_account($toUser, $targetType);
            $targetId = $userAccount->wallet_id;

            $transaction = Txn::new(
                $amount,
                0,
                $amount,
                'system',
                "IB Bonus via $childUserName from account $login",
                TxnType::IbBonus,
                TxnStatus::Success,
                base_currency(),
                $amount,
                $toUser,
                $fromUser,
                'User',
                [],
                'note',
                $targetId,
                TxnTargetType::Wallet->value
            );

            $this->addBalance($transaction);
        }
    }

    protected function addBalance($transaction)
    {
        $userAccount = get_user_account_by_wallet_id($transaction->target_id);
        $walletService = new WalletService();
        $ledgerBalance = $walletService->getLedgerBalance($userAccount->id);

        $walletService->createCreditLedgerEntry($transaction, $ledgerBalance);

        if ($transaction->target_type == TxnTargetType::Wallet->value) {
        $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->amount));
        $userAccount->save();
    }
    }
}
