<?php

namespace App\Console\Commands;


use App\Enums\AccountBalanceType;
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
    protected $description = 'IB rebate distribution';

    public function handle()
    {
        DB::beginTransaction();
        try {
//            $ReferralRelationships = ReferralRelationship::whereNotNull('multi_level_id')->with('referralLink')->get();
            $ReferralRelationships = ReferralRelationship::with('referralLink')
                ->where('user_id', 7193)->get();
//            dd($ReferralRelationships);
            foreach ($ReferralRelationships as $ReferralRelationship) {
                $this->processReferralRelationship($ReferralRelationship);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to distribute rebates: ' . $e->getMessage());
            $this->error('Failed to distribute rebates: ' . $e->getMessage());
            return 1; // Indicates error
        }
        return 0; // Indicates success
    }

    protected function processReferralRelationship($ReferralRelationship)
    {
        $parentIbGroup = $ReferralRelationship->referralLink->user->ibGroup;
//        dd($parentIbGroup);
        if (!$parentIbGroup) {
            return false;
        }
//dd('s');
        $childUserId = $ReferralRelationship->user_id;
//        dd($childUserId);
        $realForexAccounts = ForexAccount::realActiveAccount($childUserId)
            ->orderBy('balance', 'desc')
            ->get();
//        dd($realForexAccounts);
        $sysmbols = $this->getUserAssignedSymbols($ReferralRelationship);

        foreach ($realForexAccounts as $realForexAccount) {
//            dd($sysmbols);
            $lastDealTime = $this->getLastDeal($childUserId, $realForexAccount->login);
            $deals = $this->getMT5Deals($realForexAccount->login, $lastDealTime, $sysmbols);
//            dd($ReferralRelationship);
            $this->saveMT5Deals($deals, $childUserId, $ReferralRelationship);
        }
    }

    protected function getUserAssignedSymbols(ReferralRelationship $referralRelationship): array
    {
        return $referralRelationship->referralLink->user->ibGroup->rebateRules()
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

        return DB::connection('mt5_db2')
            ->table($table)
            ->select(['Login', 'Deal', 'Dealer', 'Order', 'Symbol', 'Time', 'Volume', 'VolumeClosed'])
            ->where('Login', $login)
            ->whereIn('Symbol', $sysmbols)
            ->where('Time', '>', $lastDealTime)
            ->where('Volume', '>', 0)
            ->whereColumn('Volume', 'VolumeClosed')
            ->get();
    }

    protected function saveMT5Deals($deals, $childUserId, $ReferralRelationship)
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
//            dd($deal,$metaDeal);

            $this->distributeRebate($metaDeal, $childUserId, $ReferralRelationship);
        }
    }

    protected function distributeRebate($metaDeal, $childUserId, $ReferralRelationship)
    {
        $parentId = $ReferralRelationship->referralLink->user_id;
        $childUser = User::find($childUserId);
//dd($metaDeal);
        $share = $this->calculateRebate($metaDeal->symbol, $ReferralRelationship);
        $parentRebateBonus = $share['parentShare'];
        $childShareBonus = $share['childShare'];

        $targetType = TxnTargetType::Wallet->value;
        $sourceFrom = AccountBalanceType::IB_WALLET;

        if ($parentRebateBonus > 0) {
            $userAccount = get_user_account($parentId, $sourceFrom);
            $targetId = $userAccount->wallet_id;
            $transaction = Txn::new($parentRebateBonus, 0, $parentRebateBonus, 'system', 'IB Bonus via ' . $childUser->full_name . ' from account ' . $metaDeal->login, TxnType::IbBonus, TxnStatus::Success, base_currency(), $parentRebateBonus, $parentId, $childUserId, 'User', [], 'note', $targetId, $targetType);
            $this->addBalance($transaction);
        }
        if ($childShareBonus > 0) {
            $userAccount = get_user_account($childUserId, $sourceFrom);
            $targetId = $userAccount->wallet_id;
            $transaction = Txn::new($childShareBonus, 0, $childShareBonus, 'system', 'IB Bonus via ' . $childUser->full_name . ' from account ' . $metaDeal->login, TxnType::IbBonus, TxnStatus::Success, base_currency(), $childShareBonus, $childUserId, $childUserId, 'User', [], 'note', $targetId, $targetType);
            $this->addBalance($transaction);
        }

        $metaDeal->is_paid = Carbon::now();
        $metaDeal->save();
    }

    protected function calculateRebate($symbol, $ReferralRelationship)
    {
//        dd($ReferralRelationship);
        $rebateRule = $ReferralRelationship->referralLink
            ->user
            ->ibGroup
            ->rebateRules()
            ->whereHas('symbolGroups.symbols', function ($query) use ($symbol) {
                $query->where('symbol', $symbol);
            })
            ->first();  // Retrieve the first matching rebate rule
//        dd($symbol,$rebateRule);
        $parentShare = 0;
        $childShare = 0;
//        dd($rebateRule);
        if ($rebateRule) {
            $totalRebateAmount = $rebateRule->rebate_amount;
            $ibShare = UserIbRule::where('rebate_rule_id', $rebateRule->id)->first();
//            dd($ibShare,$rebateRule->id);
            if ($ibShare) {
                $childShare = $ibShare->sub_ib_share;
            }
            if ($totalRebateAmount >= $childShare) {
                $parentShare = $totalRebateAmount - $childShare;
            } else {
                $parentShare = $totalRebateAmount;
            }
        }
//        dd($parentShare);
        return [
            'parentShare' => $parentShare,
            'childShare' => $childShare,
        ];
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
