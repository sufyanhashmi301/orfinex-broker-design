<?php

namespace App\Facades\Txn;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Enums\TxnTargetType;
use App\Models\LevelReferral;
use App\Models\MetaTransaction;
use App\Models\OldTransaction;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ForexApiService;
use App\Services\WalletService;
use App\Traits\ForexApiTrait;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Str;

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
                    'type' => 1,//deposit
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

    public function update($tnx, $status, $userId = null, $approvalCause = 'none')
    {
        $transaction = Transaction::tnx($tnx);
//        dd($transaction);

        $uId = $userId == null ? auth()->user()->id : $userId;

        $user = User::find($uId);
//        dd($status,$transaction->type,$transaction);

        if ($status == TxnStatus::Success && ($transaction->type == TxnType::Deposit || $transaction->type == TxnType::ManualDeposit)) {
            if (isset($transaction->target_id) && $transaction->target_type == TxnTargetType::ForexDeposit->value) {
                $comment = $transaction->method . '/' . substr($transaction->tnx, -7);
                $data = [
                    'login' => $transaction->target_id,
                    'Amount' => $transaction->final_amount,
                    'type' => 1,//deposit
                    'TransactionComments' => $comment
                ];
                $forexApiService = new ForexApiService();
                $forexApiService->balanceOperation($data);
//                $this->ForexDeposit($transaction->target_id,$transaction->final_amount,$comment);
                first_min_deposit($transaction->target_id);
            }elseif (isset($transaction->target_id) && $transaction->target_type == TxnTargetType::Wallet->value && ($transaction->type == TxnType::Deposit || $transaction->type == TxnType::ManualDeposit)) {
                $userAccount = get_user_account($transaction->user_id);
                $wallet = new WalletService();
                $ledgerBalance = $wallet->getLedgerBalance($userAccount->id);
                $wallet->createCreditLedgerEntry($transaction, $ledgerBalance);

                if ($transaction->target_type == TxnTargetType::Wallet->value) {
                    $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->amount));
                    $userAccount->save();
                }
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
//            dd('ss');
//        dd($status,$status == TxnStatus::Success,$transaction->type,TxnType::ManualDeposit, $transaction->type == TxnType::ManualDeposit);
        // Apply deduction for withdrawals only
        if ($status == TxnStatus::Success && ($transaction->type == TxnType::Withdraw || $transaction->type == TxnType::WithdrawAuto)) {
            $deductionApplied = $this->applyWithdrawalDeduction($transaction);

            if (!$deductionApplied) {
                // If deduction fails, return false or handle error
                return false;
            }
        }

        // Update the transaction status
        $data = [
            'status' => $status,
            'approval_cause' => $approvalCause,
            'manual_field_data' => json_encode(json_decode($transaction->manual_field_data, true)) // Re-encode manual_field_data
        ];

        return $transaction->update($data);

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

        // Apply deduction based on the target type (Forex or Wallet)
        if ($transaction->target_type == TxnTargetType::ForexWithdraw->value) {
        return $this->deductForexAccount($transaction);
    } elseif ($transaction->target_type == TxnTargetType::Wallet->value) {
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
            'Amount' => $transaction->final_amount,
            'type' => 2, // withdraw
            'TransactionComments' => $comment,
        ];

        $forexApiService = new ForexApiService();
        $withdrawResponse = $forexApiService->balanceOperation($data);

        if ($withdrawResponse['success']) {
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
            Log::error("Forex deduction failed for transaction ID {$transaction->id}: Insufficient balance");

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
