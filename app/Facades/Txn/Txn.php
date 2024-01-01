<?php

namespace App\Facades\Txn;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\ForexApiTrait;
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

        if ($status == 'success' && ($transaction->type == TxnType::Deposit || $transaction->type == TxnType::ManualDeposit)) {
            if (isset($transaction->target_id) && $transaction->target_type == 'forex_deposit') {
                $comment =  "$transaction->method/".$transaction->tnx;
                $this->ForexDeposit($transaction->target_id,$transaction->final_amount,$comment);
            } else {
                $amount = $transaction->amount;
                $user->increment('balance', $amount);
            }

        }

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
            'Comment' => "Withdraw/" . $tnx->final_amount,
            'auth' => $auth,
        ];
//        dd($userAccount,$dataArray);
        $withdrawResponse = $this->sendApiRequest($withdrawUrl, $dataArray);
//        dd($userAccount,$withdrawResponse);
        if ($withdrawResponse ? $withdrawResponse->status() == 200 && $withdrawResponse->object()->data == 0 : false) {
            return true;
        }

    }
}
