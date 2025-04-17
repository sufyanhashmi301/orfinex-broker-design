<?php


namespace App\Services;

use App\Enums\AccountBalanceType;
use App\Enums\TxnTargetType;
use App\Enums\TxnType;

use App\Models\Account;

use App\Models\Ledger;

use App\Models\Transaction;
use App\Models\User;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class WalletService
{

//
    public function __construct()
    {

    }

    /**
     * @param $amount
     * @param $exchangeRate
     * @return BigDecimal|\Brick\Math\BigNumber
     * @version 1.0.0
     * @since 1.0
     */
    public function toBase($amount, $exchangeRate)
    {
        return BigDecimal::of($amount)
            ->dividedBy(BigDecimal::of($exchangeRate), '6', RoundingMode::CEILING);
    }

    /**
     * @param (array) $data
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    public function toTnxMeta($data)
    {
        $except = ['method_name', 'currency_name', 'equal_amount', 'equal_currency', 'pay_from', 'pay_to', 'fx_rate', 'fx_currency', 'source', 'base_fees', 'amount_fees', 'exchange_rate', 'bonus_type', 'type', 'base_currency', 'currency', 'user_id', 'desc', 'note', 'remarks', 'reference'];

        if (is_array($data)) {
            return Arr::except($data, $except);
        }
        return $data;
    }


    /**
     * @param $transaction
     * @param $ledgerBalance
     * @return Ledger
     * @throws \Exception
     * @version 1.0.0
     * @since 1.0
     */
    public function createCreditLedgerEntry($transaction, $ledgerBalance)
    {
        $ledger = new Ledger();
        $ledger->transaction_id = $transaction->id;

        $account = get_user_account_by_wallet_id($transaction->target_id);
        $accountId = $account->id;
//dd($account);
//        dd($transaction);

        $ledger->credit = $transaction->amount;
        $ledger->account_id = $accountId;
        $balance = BigDecimal::of($ledgerBalance)->plus(BigDecimal::of($transaction->amount));
//dd($balance,$ledgerBalance);
        if ($balance < BigDecimal::of(0.00)) {
//            dd($balance);
            throw new \Exception(__("Unprocessable transaction."));
        }

        $ledger->balance = $balance;
//        dd($ledger);
        $ledger->save();

        return $ledger;
    }

    public function createDebitLedgerEntry($transaction, $ledgerBalance)
    {
        $ledger = new Ledger();
        $ledger->transaction_id = $transaction->id;

        // Find the account associated with the wallet ID
        $account = get_user_account_by_wallet_id($transaction->target_id);

        // Set the debit amount for the ledger entry
        $ledger->debit = $transaction->final_amount;
        $ledger->account_id = $account->id;

        // Deduct the amount from the ledger balance
        $balance = BigDecimal::of($ledgerBalance)->minus(BigDecimal::of($transaction->final_amount));
//dd($balance);
        // Ensure that balance does not go below zero
        if ($balance->isLessThan(BigDecimal::of(0.00))) {
            throw new \Exception(__("Unprocessable transaction. Insufficient balance."));
        }

        // Update the balance in the ledger
        $ledger->balance = $balance;
        $ledger->save();


        return $ledger;
    }

    public function adjustLedgerEntry($transaction, $ledgerBalance)
    {
//        $ledger = new Ledger();
//        $ledger->transaction_id = $transaction->id;
////        dd($ledger);
//
//        if ($transaction->calc == TransactionCalcType::DEBIT) {
//            $ledger->debit = $transaction->amount;
        $ledger = Ledger::where('account_id', $transaction->account_to)->orderBy('id', 'DESC')->first();
        $ledger->account_id = $transaction->account_to;
//            dd($ledgerBalance,BigDecimal::of($transaction->total));
        $balance = BigDecimal::of($ledgerBalance)->minus(BigDecimal::of($transaction->total));
//            dd($ledgerBalance,$balance);
//        }
//
//        if ($transaction->calc == TransactionCalcType::CREDIT) {
//            $ledger->credit = $transaction->total;
//            $ledger->account_id = $transaction->account_to;
//            $balance = BigDecimal::of($ledgerBalance)->plus(BigDecimal::of($transaction->amount));
//        }
//        dd($balance,BigDecimal::of(0.00));

        if ($balance < BigDecimal::of(0.00)) {
//            dd($balance);
            throw new \Exception(__("Unprocessable transaction."));
        }

        $ledger->balance = $balance;
//        dd($ledger);
        $ledger->save();

        return $ledger;
    }

    public function getLedgerBalance($accountId)
    {
        $latestLedgerEntry = Ledger::where('account_id', $accountId)->orderBy('id', 'desc')->lockForUpdate()->first();
        return data_get($latestLedgerEntry, 'balance', 0.00);
    }


}
