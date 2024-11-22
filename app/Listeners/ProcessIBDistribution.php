<?php
// Step 2: Create the listener for handling the IB distribution logic
// app/Listeners/ProcessIBDistribution.php

namespace App\Listeners;
use App\Enums\AccountBalanceType;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Enums\TxnTargetType;
use App\Events\IBDistributionEvent;
use App\Models\ReferralRelationship;
use App\Models\User;
use App\Models\UserIbRule;
use Illuminate\Support\Facades\DB;
use Txn;

class ProcessIBDistribution
{
    public function handle(IBDistributionEvent $event)
    {
        DB::transaction(function () use ($event) {
            $user_id = $event->user_id;
            $amount = $event->amount;
            $login = $event->login;
            $user = User::find($user_id);
            // Retrieve referral relationship with multi_level_id
            $relationship = ReferralRelationship::where('user_id', $user_id)
                ->whereNotNull('multi_level_id')
                ->first();

            if ($relationship) {
                // Get the parent user ID from ReferralLink
                $parent_user_id = $relationship->referralLink->user_id;

                // Retrieve the UserIbRule with matching multi_level_id for percentage
                $ibRule = UserIbRule::where('multi_level_id', $relationship->multi_level_id)->first();
//                dd($ibRule);

//                if ($ibRule) {
                    // Calculate distribution amounts
                    $percentage = $ibRule ? $ibRule->share : 0;
                    $user_amount = $amount * ($percentage / 100);
                    $parent_amount = $amount - $user_amount;
//                    dd($amount,$user_amount,$parent_amount);
                    $charge = 0;
                     $depositType = TxnType::IB;
                    $userAccount = get_user_account($user_id, AccountBalanceType::IB_WALLET);
                    $targetId = $userAccount->wallet_id;
                    $targetType = TxnTargetType::Wallet->value;

                    // Record the transactions
                    $txnInfo = Txn::new(
                        $user_amount, $charge, $user_amount, 'System',
                        __('IB Profit from ').$login, $depositType, TxnStatus::Pending,
                        base_currency(), $user_amount, $user_id, null, 'User',
                        $manualData ?? [], 'none', $targetId, $targetType
                    );
                    Txn::update($txnInfo->tnx, TxnStatus::Success, $txnInfo->user_id, 'system');

                     //**********Prent Record the transactions****************
                    $parentAccount = get_user_account($parent_user_id, AccountBalanceType::IB_WALLET);
                    $parentTargetId = $parentAccount->wallet_id;
                       //Prent Record the transactions
                    $txnInfo = Txn::new(
                        $parent_amount, $charge, $parent_amount, 'System',
                        __('IB Profit from ').$user->full_name . __(' - ').$login, $depositType, TxnStatus::Pending,
                        base_currency(), $parent_amount, $parent_user_id, null, 'User',
                        $manualData ?? [], 'none', $parentTargetId, $targetType
                    );
                    Txn::update($txnInfo->tnx, TxnStatus::Success, $txnInfo->user_id, 'system');

//                }
            }
        });
    }
}
