<?php

namespace App\Facades\Txn;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\LevelReferral;
use App\Models\MetaTransaction;
use App\Models\OldTransaction;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ForexApiService;
use App\Traits\ForexApiTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $uId = $userId == null ? auth()->user()->id : $userId;

        $user = User::find($uId);
//        dd($status,$transaction->type,$transaction);

        if ($status == TxnStatus::Success && ($transaction->type == TxnType::Deposit || $transaction->type == TxnType::ManualDeposit)) {
//            dd($transaction);
            if (isset($transaction->target_id) && $transaction->target_type == 'forex_deposit') {
                $comment =  $transaction->method.'/'.substr($transaction->tnx, -7);
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
            $this->WithdrawDeposit($transaction);
        }

        $data = [
            'status' => $status,
            'approval_cause' => $approvalCause,
        ];

        return $transaction->update($data);

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
                $comment =  $transaction->method.'/'.substr($transaction->tnx, -7);
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
            $this->WithdrawDeposit($transaction);
        }

        $data = [
            'status' => $status,
            'approval_cause' => $approvalCause,
        ];

        return $transaction->update($data);

    }

//    public function ForexDeposit($tnx)
//    {
//        $depositUrl = config('forextrading.depositUrl');
//        $auth = config('forextrading.auth');
//
////            dd($amount);
//        $dataArray = array(
//            'Login' => $tnx->target_id,
//            'Deposit' => $tnx->final_amount,
//            'Comment' => "Deposit/".$tnx->tnx,
//            'auth' => $auth,
//        );
////            dd($dataArray);
//        $depositResponse = $this->sendApiRequest($depositUrl, $dataArray);
////        dd($depositResponse->object());
//
//        if (($depositResponse ? $depositResponse->status() == 200 && $depositResponse->object()->data == 0 : false)) {
//           return true;
//
//            // all good
//        }
////        catch(\Exception $e) {
////
////            throw ValidationException::withMessages(['invest' => 'Something wrong! please try again']);
////            // something went wrong
////        }
//
//    }
    public function WithdrawDeposit($tnx)
    {
        $withdrawUrl = config('forextrading.withdrawUrl');
        $auth = config('forextrading.auth');

        $dataArray = [
            'Login' => $tnx->target_id,
            'Withdraw' => $tnx->final_amount,
            'Comment' => "Withdraw/" . $tnx->final_amount
        ];
//        dd($userAccount,$dataArray);
        $withdrawResponse = $this->sendApiRequest($withdrawUrl, $dataArray);
//        dd($userAccount,$withdrawResponse);
        if ($withdrawResponse ? $withdrawResponse->status() == 200 && $withdrawResponse->object()->data == 0 : false) {
            return true;
        }

    }
}
