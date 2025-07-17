<?php

namespace App\Facades\Txn;

use App\Enums\KYCStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Enums\TxnTargetType;
use App\Models\BonusDeduction;
use App\Models\BonusTransaction;
use App\Models\ForexAccount;
use App\Models\ForexSchema;
use App\Models\LevelReferral;
use App\Models\MetaTransaction;
use App\Models\OldTransaction;
use App\Models\Transaction;
use App\Models\User;
use App\Services\BonusService;
use App\Services\ForexApiService;
use App\Services\WalletService;
use App\Services\x9ApiService;
use App\Traits\ForexApiTrait;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class Txn
{
    use ForexApiTrait;

    /**
     * @param null $payCurrency
     * @param null $payAmount
     * @param null $userID
     * @param null $fromUserID
     * @param string $fromModel
     * @param array $manualDepositData
     */
    public function new($amount, $charge, $final_amount, $method, $description, string|TxnType $type, string|TxnStatus $status = TxnStatus::Pending, $payCurrency = null, $payAmount = null, $userID = null, $relatedUserID = null, $relatedModel = 'User', array $manualFieldData = [], string $approvalCause = 'none', $targetId = null, $targetType = null, $isLevel = false): Transaction
    {
        if ($type == 'withdraw') {
            self::withdrawBalance($amount);
        }

        $transaction = new Transaction();
        $transaction->user_id = $userID ?? Auth::user()->id;
        $transaction->from_user_id = $relatedUserID;
        $transaction->from_model = $relatedModel;
        $transaction->tnx = 'TRX' . strtoupper(Str::random(10));
        $transaction->description = $description;
        $transaction->amount = $amount;
        $transaction->type = $type;
        $transaction->charge = $charge;
        $transaction->final_amount = $final_amount;
        $transaction->method = $method;
        $transaction->pay_currency = $payCurrency;
        $transaction->pay_amount = $payAmount;
        $transaction->manual_field_data = json_encode($manualFieldData);
        $transaction->approval_cause = $approvalCause;
        $transaction->target_id = $targetId;
        $transaction->target_type = $targetType;
        $transaction->is_level = $isLevel;
        $transaction->status = $status;
        $transaction->save();

        return $transaction;
    }

    public function newMeta($amount, $charge, $final_amount, $method, $description, string|TxnType $type, string|TxnStatus $status = TxnStatus::Pending, $payCurrency = null, $payAmount = null, $userID = null, $relatedUserID = null, $relatedModel = 'User', array $manualFieldData = [], string $approvalCause = 'none', $targetId = null, $targetType = null, $isLevel = false): MetaTransaction
    {
        if ($type == 'withdraw') {
            self::withdrawBalance($amount);
        }

        $transaction = new MetaTransaction();
        $transaction->user_id = $userID ?? Auth::user()->id;
        $transaction->from_user_id = $relatedUserID;
        $transaction->from_model = $relatedModel;
        $transaction->tnx = 'TRX' . strtoupper(Str::random(10));
        $transaction->description = $description;
        $transaction->amount = $amount;
        $transaction->type = $type;
        $transaction->charge = $charge;
        $transaction->final_amount = $final_amount;
        $transaction->method = $method;
        $transaction->pay_currency = $payCurrency;
        $transaction->pay_amount = $payAmount;
        $transaction->manual_field_data = json_encode($manualFieldData);
        $transaction->approval_cause = $approvalCause;
        $transaction->target_id = $targetId;
        $transaction->target_type = $targetType;
        $transaction->is_level = $isLevel;
        $transaction->status = $status;
        $transaction->save();

        return $transaction;
    }

    public function newOld($amount, $charge, $final_amount, $method, $description, string|TxnType $type, string|TxnStatus $status = TxnStatus::Pending, $payCurrency = null, $payAmount = null, $userID = null, $relatedUserID = null, $relatedModel = 'User', array $manualFieldData = [], string $approvalCause = 'none', $targetId = null, $targetType = null, $isLevel = false, $createdAt): OldTransaction
    {

        // Convert formatted strings to float
        $formattedAmount = floatval(str_replace(',', '', $amount));
        $formattedFinalAmount = floatval(str_replace(',', '', $final_amount));
        $formattedPayAmount = floatval(str_replace(',', '', $payAmount));

        $transaction = new OldTransaction();
        $transaction->user_id = $userID ?? Auth::user()->id;
        $transaction->from_user_id = $relatedUserID;
        $transaction->from_model = $relatedModel;
        $transaction->tnx = 'TRX' . strtoupper(Str::random(10));
        $transaction->description = $description;
        $transaction->amount = $formattedAmount;  // Use the formatted value for amount
        $transaction->type = $type;
        $transaction->charge = $charge;
        $transaction->final_amount = $formattedFinalAmount;  // Use the formatted value for final amount
        $transaction->method = $method;
        $transaction->pay_currency = $payCurrency;
        $transaction->pay_amount = $formattedPayAmount;  // Use the formatted value for pay amount
        $transaction->manual_field_data = json_encode($manualFieldData);
        $transaction->approval_cause = $approvalCause;
        $transaction->target_id = $targetId;
        $transaction->target_type = $targetType;
        $transaction->is_level = $isLevel;
        $transaction->status = $status;
        $transaction->created_at = $createdAt;
        $transaction->save();

        return $transaction;
    }

    public function updateMeta($tnx, $status, $userId = null, $approvalCause = 'none')
    {
        $transaction = MetaTransaction::tnx($tnx);

        $uId = $userId == null ? auth()->user()->id : $userId;

        $user = User::find($uId);
        //        dd($status,$transaction->type,$transaction);

        if ($status == TxnStatus::Success && ($transaction->type == TxnType::Deposit || $transaction->type == TxnType::ManualDeposit)) {
            //            dd($transaction);
            if (isset($transaction->target_id) && $transaction->target_type == 'forex_deposit') {
                $comment = $transaction->method . '/' . substr($transaction->tnx, -7);
                $data = [
                    'login' => $transaction->target_id,
                    'Amount' => $transaction->final_amount,
                    'type' => 1, //deposit
                    'TransactionComments' => $comment
                ];
                $forexApiService = new ForexApiService();
                $forexApiService->balanceOperation($data);
                //                $this->ForexDeposit($transaction->target_id,$transaction->final_amount,$comment);
                first_min_deposit($transaction->target_id);
            } else {
                $amount = $transaction->amount;
                $user->increment('balance', $amount);
            }

            //level referral
            //            if (setting('site_referral', 'global') == 'level' && setting('deposit_level')) {
            //                if(!isset($transaction->user->multi_ib_login)) {
            //                    createMultiIBAccount($transaction->user);
            //                }
            //                $level = LevelReferral::where('type', 'deposit')->max('the_order') + 1;
            //                creditReferralBonus($transaction->user, 'deposit', $transaction->amount, $level);
            //            }
        }
        //        dd($status,$status == TxnStatus::Success,$transaction->type,TxnType::ManualDeposit, $transaction->type == TxnType::ManualDeposit);
        if (isset($transaction->target_id) && $transaction->target_type == 'withdraw_deposit') {
            $this->forexWithdraw($transaction);
        }

        $data = [
            'status' => $status,
            'approval_cause' => $approvalCause,
        ];

        return $transaction->update($data);
    }

    /**
     * @param $walletName
     */
    private function withdrawBalance($amount): void
    {

        User::find(auth()->user()->id)->removeMoney($amount);
    }

    /**
     * Bonus Update method
     *
     */
    private function applyBonusToForexAccount($transaction, $user, $uId)
    {

        $forex_schema = ForexSchema::where('id', function ($query) use ($user, $transaction) {
            $query->select('forex_schema_id')
                ->from('forex_accounts')
                ->where('login', $transaction->target_id) // Filter by login
                ->where('user_id', $user->id); // Ensure it belongs to the user
        })->first();

        $bonus = $forex_schema
            ->bonuses()
            ->where('process', 'deposit') // Filter bonuses where process is 'deposit'
            ->where('status', 1)
            ->whereDate('last_date', '>=', Carbon::today())
            ->orderBy('id', 'DESC')
            ->first();

        if (empty($bonus)) {
            return false;
        }

        // check KYC
        $kyc_status = '';
        if ($bonus->kyc_slug == 'level1') {
            $kyc_status = KYCStatus::Level1;
        } elseif ($bonus->kyc_slug == 'level2') {
            $kyc_status = KYCStatus::Level2;
        } elseif ($bonus->kyc_slug == 'level3') {
            $kyc_status = KYCStatus::Level3;
        }

        if ($user->kyc >= $kyc_status->value) {
        } else {
            return false;
        }

        // check if bonus is allowed for first deposit or for every deposit
        $for_first_deposit_only = $bonus->first_or_every_deposit == 'first' ? true : false;
        $user_no_of_transactions = Transaction::where('user_id', $user->id)->where('type', 'LIKE', '%deposit%')->where('status', 'success')->count();
        if ($for_first_deposit_only && $user_no_of_transactions > 0) {
            return false;
        }

        // dd($bonus);
        if ($bonus->type == 'percentage') {
            $bonusPercentage = $bonus->amount;
            $bonusValue = ($bonusPercentage / 100) * $transaction->amount;
        } else {
            $bonusValue = $bonus->amount;
        }

        // Creating new transaction for bonus
        $forex_account = ForexAccount::where('login', $transaction->target_id)->first();
        $transaction_description = "Bonus rewarded to " . $forex_account->account_name . ' forex account';
        $transaction_type = TxnType::Bonus;
        $transaction_status = TxnStatus::Success;
        $user_id = $user->id;
        $admin_id = Auth::id();
        $comment = 'Bonus Applied!';
        $account_target_id = $transaction->target_id;
        $account_target_type = 'forex';

        $new_transaction = Txn::new($bonusValue, 0, $bonusValue, 'system', $transaction_description, $transaction_type, $transaction_status, null, null, $user_id, null, 'User', [], $comment, $account_target_id, $account_target_type);

        // Add record to Bonus Transactions
        $bonus_txn = new BonusTransaction();
        $bonus_txn->account_id = $forex_account->id;
        $bonus_txn->transaction_id = $new_transaction->id;
        $bonus_txn->bonus_id = $bonus->id;
        $bonus_txn->account_target_id = $transaction->target_id;
        $bonus_txn->account_type = 'forex';
        $bonus_txn->given_by = 'System';
        $bonus_txn->bonus_amount = $bonusValue;
        $bonus_txn->bonus_amount_left = $bonusValue;
        $bonus_txn->bonus_removal_type = $bonus->bonus_removal_type;
        $bonus_txn->bonus_removal_amount = $bonus->bonus_removal_amount;
        $bonus_txn->save();

        $forexApiService = new ForexApiService();
        $bonusService = new BonusService($forexApiService);

        $bonusService->addOrSubtractBonusToAccount($account_target_type, $account_target_id, $bonusValue, 'Bonus Applied!', 'add');
    }

    /**
     * Remove leftover amounts until total bonus to be removed is 0
     * Helper fn() for deductBonusFromForexAccount
     */
    private function removeBonusUntilFinish($transaction, $remaining_bonus_to_remove)
    {
        $deducted_amount = 0;
        while ($remaining_bonus_to_remove > 0) {
            // Find the next eligible BonusTransaction
            $bonus_txn = BonusTransaction::where('account_target_id', $transaction->target_id)
                ->whereHas('transaction', function ($query) {
                    $query->where('status', 'success');
                })
                ->where('given_by', 'System')
                ->where('bonus_amount_left', '>', 0)
                ->orderBy('bonus_amount', 'desc') // Get the largest bonus available
                ->first();

            // Exit loop if no more eligible transactions
            if (!$bonus_txn) {
                break;
            }

            // Deduct from the current bonus transaction
            if ($bonus_txn->bonus_amount_left >= $remaining_bonus_to_remove) {
                // If the current bonus can cover the remaining removal amount
                $deducted_amount += $remaining_bonus_to_remove;
                $bonus_txn->bonus_amount_left -= $remaining_bonus_to_remove;
                $bonus_txn->save();
                $remaining_bonus_to_remove = 0; // All removed
            } else {
                // If the current bonus is smaller, deduct all and continue
                $deducted_amount += $bonus_txn->bonus_amount_left;
                $remaining_bonus_to_remove -= $bonus_txn->bonus_amount_left;
                $bonus_txn->bonus_amount_left = 0; // All used up
                $bonus_txn->save();
            }

            // create new deduction record
            $bonus_deduction = new BonusDeduction();
            $bonus_deduction->withdraw_transaction_id = $transaction->id;
            $bonus_deduction->bonus_transaction_id = $bonus_txn->id;
            $bonus_deduction->deducted_amount = $deducted_amount;
            $bonus_deduction->save();
        }

        if ($remaining_bonus_to_remove > 0) {
            // Log or handle the fact that there weren't enough bonuses to fully cover the removal
        }

        return $deducted_amount;
    }

    /**
     * Deduct bonus method
     *
     */
    private function deductBonusFromForexAccount($transaction, $user, $uId, $status)
    {
        $largest_given_bonus_active_transaction = BonusTransaction::where('account_target_id', $transaction->target_id)
            ->whereHas('transaction', function ($query) {
                $query->where('status', 'success');
            })
            ->where('given_by', 'System')
            ->where('bonus_amount_left', '>', 0)
            ->orderBy('bonus_amount', 'desc')
            ->first();

        if (!$largest_given_bonus_active_transaction) {
            return false;
        }
        $total_removed_bonus = 0;
        $withdrawAmount = $transaction->amount;
        $bonus_removal_type['type'] = $largest_given_bonus_active_transaction->bonus_removal_type;
        $bonus_removal_type['amount'] = $largest_given_bonus_active_transaction->bonus_removal_amount;

        // if there is full_bonus then remove the remaning bonus from it
        if ($bonus_removal_type['type'] == 'full_bonus') {
            $total_removed_bonus = $largest_given_bonus_active_transaction->bonus_amount_left;
            $deducted_amount = $largest_given_bonus_active_transaction->bonus_amount_left;
            $largest_given_bonus_active_transaction->decrement('bonus_amount_left', $total_removed_bonus);

            // create new deduction record
            $bonus_deduction = new BonusDeduction();
            $bonus_deduction->withdraw_transaction_id = $transaction->id;
            $bonus_deduction->bonus_transaction_id = $largest_given_bonus_active_transaction->id;
            $bonus_deduction->deducted_amount = $deducted_amount;
            $bonus_deduction->save();
        }

        // if type is percentage
        if ($bonus_removal_type['type'] == 'percentage') {
            $total_removed_bonus = ($largest_given_bonus_active_transaction->bonus_removal_amount / 100) * $withdrawAmount;
        }

        // if type is amount
        if ($bonus_removal_type['type'] == 'amount') {
            $total_removed_bonus = $largest_given_bonus_active_transaction->bonus_removal_amount;
        }

        // only run the following in case of percentage or amount
        if ($bonus_removal_type['type'] == 'percentage' || $bonus_removal_type['type'] == 'amount') {
            $remaining_bonus_to_remove = $total_removed_bonus;
            $deducted_amount = $this->removeBonusUntilFinish($transaction, $remaining_bonus_to_remove);
        }

        // Creating new transaction for bonus
        $forex_account = ForexAccount::where('login', $transaction->target_id)->first();
        $transaction_description = "Bonus deducted from " . $forex_account->account_name . ' forex account';
        $transaction_type = TxnType::BonusSubtract;
        $transaction_status = $status;
        $user_id = $user->id;
        $admin_id = Auth::id();
        $comment = 'Bonus Deducted!';
        $account_target_id = $transaction->target_id;
        $account_target_type = 'forex';

        $new_transaction = Txn::new($deducted_amount, 0, $deducted_amount, 'system', $transaction_description, $transaction_type, $transaction_status, null, null, $user_id, null, 'User', [], $comment, $account_target_id, $account_target_type);

        // Add bonus via API
        $forexApiService = new ForexApiService();
        $bonusService = new BonusService($forexApiService);
        $bonusService->addOrSubtractBonusToAccount('forex', $transaction->target_id, $deducted_amount, 'Bonus Removed!', 'subtract');
    }

    /**
     * Refund the bonus
     *
     */
    private function refundBonusToForexAccount($transaction, $user, $uId)
    {
        $get_bonus_deduction_rejected_fields = BonusDeduction::where('withdraw_transaction_id', $transaction->id)->get();

        if (!$get_bonus_deduction_rejected_fields) {
            return false;
        }

        $amount_to_add_again = 0;
        $update_transaction = false;
        foreach ($get_bonus_deduction_rejected_fields as $field) {
            $bonus_transaction = BonusTransaction::where('id', $field->bonus_transaction_id)->first();
            $bonus_transaction->bonus_amount_left += $field->deducted_amount;
            $bonus_transaction->save();
            $amount_to_add_again += $field->deducted_amount;

            // Update the status in transactions to Failed
            if (!$update_transaction) {
                $change_transaction_status = Transaction::where('id', $field->withdraw_transaction_id + 1)->first();
                $change_transaction_status->status = TxnStatus::Failed;
                $change_transaction_status->save();
                $update_transaction = true;
            }
        }

        // Delete all bonus_deductions
        foreach ($get_bonus_deduction_rejected_fields as $field) {
            $field->delete();
        }

        // Create a new Bonus Refund Transaction
        $forex_account = ForexAccount::where('login', $transaction->target_id)->first();
        $transaction_description = "Bonus refunded to " . $forex_account->account_name . ' forex account';
        $transaction_type = TxnType::BonusRefund;
        $transaction_status = TxnStatus::Success;
        $user_id = $user->id;
        $admin_id = Auth::id();
        $comment = 'Bonus Refunded!';
        $account_target_id = $transaction->target_id;
        $account_target_type = 'forex';

        if ($amount_to_add_again > 0) {
            $new_transaction = Txn::new($amount_to_add_again, 0, $amount_to_add_again, 'system', $transaction_description, $transaction_type, $transaction_status, null, null, $user_id, null, 'User', [], $comment, $account_target_id, $account_target_type);

            // Add bonus via API
            $forexApiService = new ForexApiService();
            $bonusService = new BonusService($forexApiService);
            $bonusService->addOrSubtractBonusToAccount('forex', $transaction->target_id, $amount_to_add_again, 'Bonus Refunded!', 'add');
        }
    }

    /**
     * Set transaction status to Review and update approval cause with a message.
     */
    private function setReviewStatus($transaction, $approvalCause = '')
    {
        $reason = 'MT5 API balance has not updated. Balance has been deducted from user but not sent to MT5 account. Please review this request.';
        $transaction->status = TxnStatus::Review;
        if (!empty($approvalCause)) {
            $transaction->approval_cause = $approvalCause . ' | ' . $reason;
        } else {
            $transaction->approval_cause = $reason;
        }
        $transaction->save();
        return $transaction;
    }


    public function update($tnx, $status, $userId = null, $approvalCause = 'none')
    {
        $transaction = Transaction::tnx($tnx);

        $uId = $userId == null ? auth()->user()->id : $userId;
        $user = User::find($uId);

        try {
            if ($status == TxnStatus::Success && ($transaction->type == TxnType::Deposit || $transaction->type == TxnType::ManualDeposit || $transaction->type == TxnType::IB || $transaction->type == TxnType::VoucherDeposit)) {
                if (isset($transaction->target_id) && $transaction->target_type == TxnTargetType::ForexDeposit->value) {

                    $amount = apply_cent_account_adjustment($transaction->target_id, $transaction->amount);
                    $comment = $transaction->method . '/' . substr($transaction->tnx, -7);
                    $data = [
                        'login' => $transaction->target_id,
                        'Amount' => $amount,
                        'type' => 1, //deposit
                        'TransactionComments' => $comment
                    ];
                    $traderType = $transaction->forexTarget->trader_type;

                    if ($traderType == \App\Enums\TraderType::MT5) {
                        $forexApiService = new ForexApiService();
                        $response = $forexApiService->balanceOperation($data);
                        
                        if ($response['success'] && 
                            ($response['result']['responseCode'] == 10009 || $response['result']['responseCode'] === 'MT_RET_REQUEST_DONE')
                        ) {
                            $manualData = json_decode($transaction->manual_field_data, true);
                            if (!is_array($manualData) || array_values($manualData) === $manualData) {
                                $manualData = [];
                            }
                            $manualData['mt5_deposit_status'] = 'Deposited';
                            $transaction->manual_field_data = json_encode($manualData);
                            $transaction->save();
                        } else {
                            Log::error("MT5 deposit failed at API level for tnx {$tnx}");
                            if ($transaction->type == TxnType::Deposit && $status == TxnStatus::Success) {
                                return $this->setReviewStatus($transaction, $approvalCause);
                            }
                            throw new \Exception('MT5 deposit failed at API level');
                        }
                    } elseif ($traderType == \App\Enums\TraderType::X9) {
                        $forexApiService = new x9ApiService();
                        $forexApiService->balanceOperation($transaction->target_id, 'balance', 'deposit', $transaction->final_amount, $comment);
                    }

                    $this->applyBonusToForexAccount($transaction, $user, $uId);
                    first_min_deposit($transaction->target_id);

                } elseif (isset($transaction->target_id) && $transaction->target_type == TxnTargetType::Wallet->value && ($transaction->type == TxnType::Deposit || $transaction->type == TxnType::ManualDeposit || $transaction->type == TxnType::IB || $transaction->type == TxnType::VoucherDeposit)) {
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

            if ($status == TxnStatus::Pending && ($transaction->type == TxnType::Withdraw || $transaction->type == TxnType::WithdrawAuto)) {
                if (isset($transaction->target_id) && $transaction->target_type == TxnTargetType::ForexWithdraw->value) {
                    $this->deductBonusFromForexAccount($transaction, $user, $uId, 'pending');
                }
            }

            if ($status == TxnStatus::Failed && ($transaction->type == TxnType::Withdraw || $transaction->type == TxnType::WithdrawAuto)) {
                if (isset($transaction->target_id) && $transaction->target_type == TxnTargetType::ForexWithdraw->value) {
                    $this->refundBonusToForexAccount($transaction, $user, $uId);
                }
            }

            if ($status == TxnStatus::Success && ($transaction->type == TxnType::Withdraw || $transaction->type == TxnType::WithdrawAuto)) {
                $deductionApplied = $this->applyWithdrawalDeduction($transaction);
                if (!$deductionApplied) {
                    throw new \Exception('Withdrawal deduction failed');
                }
            }

            $existingManualData = json_decode($transaction->manual_field_data, true);
            if (!is_array($existingManualData) || array_values($existingManualData) === $existingManualData) {
                $existingManualData = [];
            }

            $data = [
                'status' => $status,
                'approval_cause' => $approvalCause,
                'manual_field_data' => json_encode($existingManualData)
            ];

            return $transaction->update($data);

        } catch (\Exception $e) {
            $manualData = json_decode($transaction->manual_field_data, true);
            if (isset($manualData['mt5_deposit_status']) && $manualData['mt5_deposit_status'] === 'Deposited') {
                $amount = apply_cent_account_adjustment($transaction->target_id, $transaction->amount);

                $reverseData = [
                    'login' => $transaction->target_id,
                    'Amount' => $amount,
                    'type' => 2, //withdraw to reverse
                    'TransactionComments' => 'Auto-Reverse: ' . $transaction->method . '/' . substr($transaction->tnx, -7),
                ];

                $forexApiService = new ForexApiService();
                $forexApiService->balanceOperation($reverseData);
            }

            Log::error("Transaction update failed for tnx {$tnx}: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Apply deduction for Wallet or Forex withdrawals
     *
     * @param Transaction $transaction
     * @return bool
     */

    private function applyWithdrawalDeduction($transaction)
    {

        // If the deduction has already been applied, skip further deduction
        $manualFieldData = json_decode($transaction->manual_field_data, true);
        $deductionStatus = isset($manualFieldData['Deduction Status']['value']) ? $manualFieldData['Deduction Status']['value'] : 'Not Deducted';

        if ($deductionStatus === 'Deducted') {
            return true; // Deduction already applied
        }
        $targetId = $transaction->target_id;

        // Apply deduction based on the target type (Forex or Wallet)
        if ($transaction->target_type == TxnTargetType::ForexWithdraw->value) {
        $totalAmount = BigDecimal::of($transaction->final_amount);
        $forexApiService = new ForexApiService();
        $balance = $forexApiService->getValidatedBalance(['login' => $targetId]);
//        dd($totalAmount,$balance);
        if ($totalAmount->compareTo($balance) > 0) {
            notify()->error(__('Insufficient Balance in Your Forex Account'), 'Error');
            return false;
        }
        return $this->deductForexAccount($transaction);
    } elseif ($transaction->target_type == TxnTargetType::Wallet->value) {
        $wallet = get_user_account_by_wallet_id($targetId, $transaction->user_id);
        $balance = BigDecimal::of($wallet->amount);
        $totalAmount = BigDecimal::of($transaction->final_amount);
        if ($totalAmount->compareTo($balance) > 0) {
            notify()->error(__('Insufficient Balance in Your Wallet'), 'Error');
            return false;
        }
        return $this->deductWalletAccount($transaction);
    }
        return false; // Unhandled target type
    }

    /**
     * Deduct balance from Forex account for withdrawal with error notification.
     *
     * @param Transaction $transaction
     * @return bool
     */
    private function deductForexAccount($transaction)
    {

        $comment = $transaction->method . '/' . substr($transaction->tnx, -7);
        $data = [
            'login' => $transaction->target_id,
            'Amount' => apply_cent_account_adjustment($transaction->target_id, $transaction->final_amount),
            'type' => 2, // withdraw
            'TransactionComments' => $comment,
        ];

        $forexApiService = new ForexApiService();
        $withdrawResponse = $forexApiService->balanceOperation($data);
//        dd($withdrawResponse);
        if ($withdrawResponse['success'] && 
            ($withdrawResponse['result']['responseCode'] == 10009 || $withdrawResponse['result']['responseCode'] === 'MT_RET_REQUEST_DONE')
        ) {
            // Mark deduction as applied
            $manualFieldData = json_decode($transaction->manual_field_data, true);
            $manualFieldData['Deduction Status'] = [
                'type' => 'text',
                'validation' => 'optional',
                'value' => 'Deducted'
            ];

            // Save updated manual_field_data
            $transaction->manual_field_data = json_encode($manualFieldData);
            $transaction->save();

            return true;
        } else {
            // Notify admin and log the error
            notify()->error(__('Insufficient Balance in the Forex Account'), 'Error');
//            Log::error("Forex deduction failed for transaction ID {$transaction->id}: Insufficient balance");
            return false;
        }
    }

    /**
     * Deduct balance from Wallet account for withdrawal with error notification.
     *
     * @param Transaction $transaction
     * @return bool
     */
    private function deductWalletAccount($transaction)
    {
        $userAccount = get_user_account_by_wallet_id($transaction->target_id);
        $walletService = new WalletService();
        $ledgerBalance = $walletService->getLedgerBalance($userAccount->id);

        try {
            // Create ledger entry for the wallet deduction (Debit)
            $walletService->createDebitLedgerEntry($transaction, $ledgerBalance);

            // Deduct the amount from wallet
            $userAccount->amount = BigDecimal::of($userAccount->amount)->minus(BigDecimal::of($transaction->final_amount));
            $userAccount->save();

            // Mark deduction as applied
            $manualFieldData = json_decode($transaction->manual_field_data, true);
            $manualFieldData['Deduction Status'] = [
                'type' => 'text',
                'validation' => 'optional',
                'value' => 'Deducted'
            ];

            // Save updated manual_field_data
            $transaction->manual_field_data = json_encode($manualFieldData);
            $transaction->save();

            return true;
        } catch (\Exception $e) {
            // Notify admin and log the error
            notify()->error(__('Insufficient Balance in the Wallet Account'), 'Error');
            Log::error("Wallet deduction failed for transaction ID {$transaction->id}: " . $e->getMessage());

            return false;
        }
    }
}
