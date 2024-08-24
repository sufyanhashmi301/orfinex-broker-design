<?php
namespace App\Console\Commands;


use App\Models\ForexAccount;
use App\Models\MetaDeal;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\ReferralRelationship;
use App\Models\User;
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
    protected $description = 'Multi level rebate distribution';

    public function handle()
    {
        DB::beginTransaction();
        try {
            $ReferralRelationships = ReferralRelationship::whereNotNull('multi_level_id')->with('referralLink')->get();

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
        $childUserId = $ReferralRelationship->user_id;
        $realForexAccounts = ForexAccount::realActiveAccount($childUserId)
            ->orderBy('balance', 'desc')
            ->get();

        foreach ($realForexAccounts as $realForexAccount) {
            $lastDealTime = $this->getLastDeal($childUserId, $realForexAccount->login);
            $deals = $this->getMT5Deals($realForexAccount->login, $lastDealTime);
            $this->saveMT5Deals($deals, $childUserId, $ReferralRelationship);
        }
    }

    protected function getLastDeal($childUserId, $login)
    {
        return MetaDeal::where('login', $login)
            ->where('user_id', $childUserId)
            ->latest('time')
            ->value('time') ?: Carbon::now()->startOfDay();
    }

    protected function getMT5Deals($login, $lastDealTime)
    {
        $table = 'mt5_deals_' . Carbon::now()->year;

        return DB::connection('mt5_db')
            ->table($table)
            ->select(['Login', 'Deal', 'Dealer', 'Order', 'Symbol', 'Time', 'Volume', 'VolumeClosed'])
            ->where('Login', $login)
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
            $this->distributeRebate($metaDeal, $childUserId, $ReferralRelationship);
        }
    }

    protected function distributeRebate($metaDeal, $childUserId, $ReferralRelationship)
    {
        $parentId = $ReferralRelationship->referralLink->user_id;
        $parentUser = User::find($parentId);
        $childUser = User::find($childUserId);

        $parentRebateBonus = $this->calculateRebate($metaDeal->lot_share, $ReferralRelationship);
        // Retrieve and convert the current balance to BigDecimal
        $currentBalance = BigDecimal::of($parentUser->multi_ib_balance);

        // Calculate the new balance
        $newBalance = $currentBalance->plus($parentRebateBonus);

        // Update the user's balance with precision maintained
        $parentUser->multi_ib_balance = $newBalance->toScale(4)->__toString(); // Ensure the balance is stored as a string with 4 decimal places
        $parentUser->save();

        $metaDeal->is_paid = Carbon::now();
        $metaDeal->save();

        Txn::newMeta($parentRebateBonus, 0, $parentRebateBonus, 'system', 'Multi Level Bonus via ' . $childUser->full_name, TxnType::MultiLevelBonus, TxnStatus::Success, null, null,$parentId, $childUserId,'User', [], 'note', $metaDeal->deal);
    }

    protected function calculateRebate($lotShare, $ReferralRelationship)
    {
        $rebateAmount = $ReferralRelationship->multiLevel->rebateRule->first()->rebate_amount;
        return BigDecimal::of($lotShare)->multipliedBy(BigDecimal::of($rebateAmount));
    }
}
