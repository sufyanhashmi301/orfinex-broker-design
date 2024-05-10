<?php


namespace App\Services\Transaction;

use App\Enums\AccountBalanceType;
use App\Enums\AccountClassification;
use App\Enums\FundedApproval;
use App\Enums\PaymentMethod;
use App\Enums\PricingInvestmentStatus;
use App\Enums\TransactionCalcType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\UserState;
use App\Models\Account;
use App\Models\ForexTrading;
use App\Models\Ledger;
use App\Models\PricingScheme;
use App\Models\Transaction;
use App\Models\User;
use App\Services\PricingInvestormService;
use App\Services\Service;
use App\Traits\ForexApi;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class TransactionService extends Service
{
    use ForexApi;

    private $transactionProcessor;
    private $rounded;
//
    public function __construct()
    {
        $this->transactionProcessor = new TransactionProcessor();

        $this->rounded = (object)[
            // 'fiat' => sys_settings('decimal_fiat_calc', 3),
            // 'crypto' => sys_settings('decimal_crypto_calc', 6)
            'fiat' => 3,
            'crypto' => 6,
        ];
    }

    /**
     * @param $amount
     * @param $exchangeRate
     * @return BigDecimal|\Brick\Math\BigNumber
     * @version 1.0.0
     * @since 1.0
     */
    private function toBase($amount, $exchangeRate)
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
    private function toTnxMeta($data)
    {
        $except = ['method_name', 'currency_name', 'equal_amount', 'equal_currency', 'pay_from', 'pay_to', 'fx_rate', 'fx_currency', 'source', 'base_fees', 'amount_fees', 'exchange_rate', 'bonus_type', 'type', 'base_currency', 'currency', 'user_id', 'desc', 'note', 'remarks', 'reference'];

        if (is_array($data)) {
            return Arr::except($data, $except);
        }
        return $data;
    }

    /**
     * @param $tnxData
     * @return Transaction
     * @version 1.0.0
     * @since 1.0
     */
    public function createDepositTransaction($tnxData)
    {
//        dd(Arr::get($tnxData, 'account_id'));
//        dd(Arr::get($tnxData, 'account_id'),AccountBalanceType::MAIN);

        $userId = auth()->user()->id;

        if (Arr::get($tnxData, 'account_id') == AccountBalanceType::MAIN ) {
//            dd(Arr::get($tnxData, 'account_id'),AccountBalanceType::MAIN);
            $account = get_user_account($userId);
            $transactionType = TransactionType::DEPOSIT;
        } else {
            $account = ForexTrading::find(Arr::get($tnxData, 'account_id'));
            $transactionType = TransactionType::FOREX_TRADING_DEPOSIT_DIRECT;
        }
//        dd($account);
        $transaction = new Transaction();
        $transaction->tnx = generate_unique_tnx();
        $transaction->type = $transactionType;
        $transaction->user_id = $userId;
        $transaction->account_to = $account->id;
        $transaction->calc = TransactionCalcType::CREDIT;
        $transaction->amount = Arr::get($tnxData, 'base_amount');
        $transaction->fees = Arr::get($tnxData, 'base_fees', 0);
        $transaction->total = to_sum($transaction->amount, $transaction->fees);
        $transaction->currency = Arr::get($tnxData, 'base_currency');
        $transaction->tnx_amount = Arr::get($tnxData, 'amount');
        $transaction->tnx_fees = Arr::get($tnxData, 'amount_fees', 0);
        $transaction->tnx_total = to_sum($transaction->tnx_amount, $transaction->tnx_fees);
        $transaction->tnx_currency = Arr::get($tnxData, 'currency');
        $transaction->tnx_method = Arr::get($tnxData, 'method');
        $transaction->exchange = Arr::get($tnxData, 'exchange_rate');
        $transaction->status = TransactionStatus::NONE;
        $transaction->description = __('Deposit via :method', ['method' => Arr::get($tnxData, 'method_name')]);
        $transaction->meta = $this->toTnxMeta($tnxData);
        $transaction->pay_to = Arr::get($tnxData, 'pay_to');
        $transaction->img = Arr::get($tnxData, 'path');
        $transaction->note = Arr::get($tnxData, 'note');
        $transaction->created_by = $userId;
        $transaction->save();

        return $transaction;
    }
    public function createCryptoTransaction($tnxData)
    {
//        dd($tnxData);
        $userId = auth()->user()->id;
        if (Arr::get($tnxData, 'account_id') == AccountBalanceType::MAIN || Arr::get($tnxData, 'account_id') =='coinbase'  || Arr::get($tnxData, 'account_id') =='bank' ) {
            $account = get_user_account($userId);
            $transactionType = TransactionType::DEPOSIT;
        } else {
            $account = ForexTrading::find(Arr::get($tnxData, 'account_id'));
            $transactionType = TransactionType::FOREX_TRADING_DEPOSIT_DIRECT;
        }
//        dd($account);
        $transaction = new Transaction();
        $transaction->tnx = generate_unique_tnx();
        $transaction->type = $transactionType;
        $transaction->user_id = $userId;
        $transaction->account_to = $account->id;
        $transaction->calc = TransactionCalcType::CREDIT;
        $transaction->amount = Arr::get($tnxData, 'base_amount');
        $transaction->fees = Arr::get($tnxData, 'base_fees', 0);
        $transaction->total = to_sum($transaction->amount, $transaction->fees);
        $transaction->currency = Arr::get($tnxData, 'base_currency');
        $transaction->tnx_amount = Arr::get($tnxData, 'amount');
        $transaction->tnx_fees = Arr::get($tnxData, 'amount_fees', 0);
        $transaction->tnx_total = to_sum($transaction->tnx_amount, $transaction->tnx_fees);
        $transaction->tnx_currency = Arr::get($tnxData, 'currency');
        $transaction->tnx_method = Arr::get($tnxData, 'method');
        $transaction->exchange = Arr::get($tnxData, 'exchange_rate');
        $transaction->status = TransactionStatus::NONE;
        $transaction->description = __('Deposit via :method', ['method' => Arr::get($tnxData, 'method_name')]);
        $transaction->meta = $this->toTnxMeta($tnxData);
        $transaction->pay_to = Arr::get($tnxData, 'pay_to');
        $transaction->img = Arr::get($tnxData, 'path');
        $transaction->note = Arr::get($tnxData, 'note');
        $transaction->created_by = $userId;
        $transaction->save();

        return $transaction;
    }

    /**
     * @param $tnxData
     * @return Transaction
     * @version 1.0.0
     * @since 1.0
     */
    public function createManualTransaction($tnxData, $creator = null)
    {
        $userId = Arr::get($tnxData, 'user_id');
        $account = get_user_account($userId);

        $transaction = new Transaction();
        $transaction->tnx = generate_unique_tnx();
        $transaction->type = Arr::get($tnxData, 'type');
        $transaction->user_id = $userId;
        $transaction->calc = Arr::get($tnxData, 'calc');
        $transaction->amount = Arr::get($tnxData, 'base_amount');
        $transaction->fees = Arr::get($tnxData, 'base_fees', 0);
        $transaction->total = to_sum($transaction->amount, $transaction->fees);
        $transaction->currency = Arr::get($tnxData, 'base_currency');
        $transaction->tnx_amount = Arr::get($tnxData, 'amount');
        $transaction->tnx_fees = Arr::get($tnxData, 'amount_fees', 0);
        $transaction->tnx_total = to_sum($transaction->tnx_amount, $transaction->tnx_fees);
        $transaction->tnx_currency = Arr::get($tnxData, 'currency');
        $transaction->tnx_method = Arr::get($tnxData, 'method');
        $transaction->exchange = Arr::get($tnxData, 'exchange_rate', 1);
        $transaction->status = TransactionStatus::PENDING;
        $transaction->reference = Arr::get($tnxData, 'reference');
        $transaction->description = Arr::get($tnxData, 'desc');
        $transaction->remarks = Arr::get($tnxData, 'remarks');
        $transaction->note = Arr::get($tnxData, 'note');
        $transaction->meta = $this->toTnxMeta($tnxData);
        $transaction->pay_to = Arr::get($tnxData, 'pay_to');

        if (!empty($creator) && isset($creator->id)) {
            $transaction->created_by = $creator->id;
        } elseif (auth()->check() && auth()->user()->id) {
            $transaction->created_by = auth()->user()->id;
        } else {
            $transaction->created_by = (!empty(system_admin())) ? system_admin()->id : 0;
        }

        if (Arr::get($tnxData, 'pay_from')) {
            $transaction->pay_from = Arr::get($tnxData, 'pay_from');
        }

        if (Arr::get($tnxData, 'calc') == TransactionCalcType::DEBIT) {
            $transaction->account_from = $account->id;
        } else {
            $transaction->account_to = $account->id;
        }
        $transaction->save();

        return $transaction;
    }
    public function createForexTradingManualTransaction($tnxData,$accountID)
    {
        $userId = Arr::get($tnxData, 'user_id');
//        $account = get_user_account($userId);

        $transaction = new Transaction();
        $transaction->tnx = generate_unique_tnx();
        $transaction->type = Arr::get($tnxData, 'type');
        $transaction->user_id = $userId;
        $transaction->calc = Arr::get($tnxData, 'calc');
        $transaction->amount = Arr::get($tnxData, 'base_amount');
        $transaction->fees = Arr::get($tnxData, 'base_fees', 0);
        $transaction->total = to_sum($transaction->amount, $transaction->fees);
        $transaction->currency = Arr::get($tnxData, 'base_currency');
        $transaction->tnx_amount = Arr::get($tnxData, 'amount');
        $transaction->tnx_fees = Arr::get($tnxData, 'amount_fees', 0);
        $transaction->tnx_total = to_sum($transaction->tnx_amount, $transaction->tnx_fees);
        $transaction->tnx_currency = Arr::get($tnxData, 'currency');
        $transaction->tnx_method = Arr::get($tnxData, 'method');
        $transaction->exchange = Arr::get($tnxData, 'exchange_rate', 1);
        $transaction->status = TransactionStatus::PENDING;
        $transaction->reference = Arr::get($tnxData, 'reference');
        $transaction->description = Arr::get($tnxData, 'desc');
        $transaction->remarks = Arr::get($tnxData, 'remarks');
        $transaction->note = Arr::get($tnxData, 'note');
        $transaction->meta = $this->toTnxMeta($tnxData);
        $transaction->pay_to = Arr::get($tnxData, 'pay_to');

        if (!empty($creator) && isset($creator->id)) {
            $transaction->created_by = $creator->id;
        } elseif (auth()->check() && auth()->user()->id) {
            $transaction->created_by = auth()->user()->id;
        } else {
            $transaction->created_by = (!empty(system_admin())) ? system_admin()->id : 0;
        }

        if (Arr::get($tnxData, 'pay_from')) {
            $transaction->pay_from = Arr::get($tnxData, 'pay_from');
        }

        if (Arr::get($tnxData, 'calc') == TransactionCalcType::DEBIT) {
            $transaction->account_from = $accountID;
        } else {
            $transaction->account_to = $accountID;
        }
        $transaction->save();

        return $transaction;
    }




    public function createManualTransactionWithSource($tnxData, $source, $creator = null)
    {
        $userId = Arr::get($tnxData, 'user_id');
        $account = get_user_account($userId, $source);

        $transaction = new Transaction();
        $transaction->tnx = generate_unique_tnx();
        $transaction->type = Arr::get($tnxData, 'type');
        $transaction->user_id = $userId;
        $transaction->calc = Arr::get($tnxData, 'calc');
        $transaction->amount = Arr::get($tnxData, 'base_amount');
        $transaction->fees = Arr::get($tnxData, 'base_fees', 0);
        $transaction->total = to_sum($transaction->amount, $transaction->fees);
        $transaction->currency = Arr::get($tnxData, 'base_currency');
        $transaction->tnx_amount = Arr::get($tnxData, 'amount');
        $transaction->tnx_fees = Arr::get($tnxData, 'amount_fees', 0);
        $transaction->tnx_total = to_sum($transaction->tnx_amount, $transaction->tnx_fees);
        $transaction->tnx_currency = Arr::get($tnxData, 'currency');
        $transaction->tnx_method = Arr::get($tnxData, 'method');
        $transaction->exchange = Arr::get($tnxData, 'exchange_rate', 1);
        $transaction->status = TransactionStatus::PENDING;
        $transaction->reference = Arr::get($tnxData, 'reference');
        $transaction->description = Arr::get($tnxData, 'desc');
        $transaction->remarks = Arr::get($tnxData, 'remarks');
        $transaction->note = Arr::get($tnxData, 'note');
        $transaction->meta = $this->toTnxMeta($tnxData);
        $transaction->pay_to = Arr::get($tnxData, 'pay_to');

        if (!empty($creator) && isset($creator->id)) {
            $transaction->created_by = $creator->id;
        } elseif (auth()->check() && auth()->user()->id) {
            $transaction->created_by = auth()->user()->id;
        } else {
            $transaction->created_by = (!empty(system_admin())) ? system_admin()->id : 0;
        }

        if (Arr::get($tnxData, 'pay_from')) {
            $transaction->pay_from = Arr::get($tnxData, 'pay_from');
        }

        if (Arr::get($tnxData, 'calc') == TransactionCalcType::DEBIT) {
            $transaction->account_from = $account->id;
        } else {
            $transaction->account_to = $account->id;
        }
        $transaction->save();

        return $transaction;
    }
    public function createForexTransactionWithSource($tnxData, $source, $creator = null)
    {
        $userId = Arr::get($tnxData, 'user_id');
        $account = get_user_account($userId, $source);

        $transaction = new Transaction();
        $transaction->tnx = generate_unique_tnx();
        $transaction->type = Arr::get($tnxData, 'type');
        $transaction->user_id = $userId;
        $transaction->calc = Arr::get($tnxData, 'calc');
        $transaction->amount = Arr::get($tnxData, 'base_amount');
        $transaction->fees = Arr::get($tnxData, 'base_fees', 0);
        $transaction->total = to_sum($transaction->amount, $transaction->fees);
        $transaction->currency = Arr::get($tnxData, 'base_currency');
        $transaction->tnx_amount = Arr::get($tnxData, 'amount');
        $transaction->tnx_fees = Arr::get($tnxData, 'amount_fees', 0);
        $transaction->tnx_total = to_sum($transaction->tnx_amount, $transaction->tnx_fees);
        $transaction->tnx_currency = Arr::get($tnxData, 'currency');
        $transaction->tnx_method = Arr::get($tnxData, 'method');
        $transaction->exchange = Arr::get($tnxData, 'exchange_rate', 1);
        $transaction->status = TransactionStatus::PENDING;
        $transaction->reference = Arr::get($tnxData, 'reference');
        $transaction->description = Arr::get($tnxData, 'desc');
        $transaction->remarks = Arr::get($tnxData, 'remarks');
        $transaction->note = Arr::get($tnxData, 'note');
        $transaction->meta = $this->toTnxMeta($tnxData);
        $transaction->pay_to = Arr::get($tnxData, 'pay_to');
        $transaction->account_to = Arr::get($tnxData, 'account_to');

        if (!empty($creator) && isset($creator->id)) {
            $transaction->created_by = $creator->id;
        } elseif (auth()->check() && auth()->user()->id) {
            $transaction->created_by = auth()->user()->id;
        } else {
            $transaction->created_by = (!empty(system_admin())) ? system_admin()->id : 0;
        }

        if (Arr::get($tnxData, 'pay_from')) {
            $transaction->pay_from = Arr::get($tnxData, 'pay_from');
        }

        if (Arr::get($tnxData, 'calc') == TransactionCalcType::DEBIT) {
            $transaction->account_from = $account->id;
        } else {
            $transaction->account_to = $account->id;
        }
        $transaction->save();

        return $transaction;
    }

    /**
     * @param $tnxData
     * @return Transaction
     * @version 1.0.0
     * @since 1.0
     */

    public function createWithdrawTransaction($tnxData)
    {
//        dd($tnxData);
        $userId = auth()->user()->id;

        if (Arr::get($tnxData, 'account_from_type') == AccountClassification::WALLETS) {
            $account = get_user_account($userId, data_get($tnxData, 'source'));
            $transactionType = TransactionType::WITHDRAW;
        } else {
            $account = ForexTrading::find(Arr::get($tnxData, 'account_id'));
            $transactionType = TransactionType::FOREX_TRADING_WITHDRAW_DIRECT;
        }

        $transaction = new Transaction();
        $transaction->tnx = generate_unique_tnx();
        $transaction->type = $transactionType;
        $transaction->user_id = $userId;
        $transaction->account_from = $account->id;
        $transaction->calc = TransactionCalcType::DEBIT;
        $transaction->amount = Arr::get($tnxData, 'base_amount');
        $transaction->fees = Arr::get($tnxData, 'base_fees', 0);
        $transaction->total = to_sum($transaction->amount, $transaction->fees);
        $transaction->currency = Arr::get($tnxData, 'base_currency');
        $transaction->tnx_amount = Arr::get($tnxData, 'amount');
        $transaction->tnx_fees = Arr::get($tnxData, 'amount_fees', 0);
        $transaction->tnx_total = to_sum($transaction->tnx_amount, $transaction->tnx_fees);
        $transaction->tnx_currency = Arr::get($tnxData, 'currency');
        $transaction->tnx_method = Arr::get($tnxData, 'method');
        $transaction->exchange = Arr::get($tnxData, 'exchange_rate');
        $transaction->status = TransactionStatus::PENDING;
        $transaction->description = __('Withdraw via :method', ['method' => Arr::get($tnxData, 'method_name')]);
        $transaction->meta = $this->toTnxMeta($tnxData);
        $transaction->pay_to = Arr::get($tnxData, 'pay_to');
        $transaction->note = Arr::get($tnxData, 'note');
        $transaction->created_by = $userId;
        $transaction->save();

        return $transaction;
    }


    /**
     * @param $transaction
     * @param $ledgerBalance
     * @return Ledger
     * @throws \Exception
     * @version 1.0.0
     * @since 1.0
     */
    private function createLedgerEntry($transaction, $ledgerBalance)
    {
        $ledger = new Ledger();
        $ledger->transaction_id = $transaction->id;
//        dd($ledger);

        if ($transaction->calc == TransactionCalcType::DEBIT) {
            $ledger->debit = $transaction->amount;
            $ledger->account_id = $transaction->account_from;
//            dd($ledgerBalance,BigDecimal::of($transaction->total));
            $balance = BigDecimal::of($ledgerBalance)->minus(BigDecimal::of($transaction->total));
//            dd($ledgerBalance,$balance);
        }

        if ($transaction->calc == TransactionCalcType::CREDIT) {
            $ledger->credit = $transaction->total;
            $ledger->account_id = $transaction->account_to;
            $balance = BigDecimal::of($ledgerBalance)->plus(BigDecimal::of($transaction->amount));
        }
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

    private function adjustLedgerEntry($transaction, $ledgerBalance)
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

    private function getLedgerBalance($accountId)
    {
        $latestLedgerEntry = Ledger::where('account_id', $accountId)->orderBy('id', 'desc')->first();
        return data_get($latestLedgerEntry, 'balance', 0.00);
    }

    /**
     * @param $transaction
     * @param array $completedBy
     * @return mixed
     * @throws \Exception
     * @version 1.0.0
     * @since 1.0
     */
    public function confirmTransaction($transaction, $completedBy = [], $auto = false)
    {
        if (in_array($transaction->status, [TransactionStatus::COMPLETED])) {
            throw new \Exception(__("Transaction already completed."));
        }

        if (!in_array($transaction->status, [
            TransactionStatus::PENDING,
            TransactionStatus::ONHOLD,
            TransactionStatus::CONFIRMED
        ])) {
            throw new \Exception(__("Invalid transaction status."));
        }

        $userAccount = get_user_account($transaction->user_id);
//        dd($userAccount);
        $ledgerBalance = $this->getLedgerBalance($userAccount->id);
//        dd($ledgerBalance);
        $this->createLedgerEntry($transaction, $ledgerBalance);
//        dd('s');
        if ($transaction->calc == TransactionCalcType::CREDIT) {
            $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->amount));
            $userAccount->save();
        }
        $this->updateUserLeadState($transaction);
        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->completed_at = Carbon::now();
        $transaction->completed_by = $completedBy;
        $transaction->save();
        $transaction->fresh();

        if (has_deposit_bonus()) {
            $this->addDepositBonus($transaction);
        }

        if (referral_system()) {
            $this->addReferralCommissionDeposit($transaction);
        }

        return $userAccount->toArray();
    }
    public function confirmWithdrawTransactionWithSource($transaction, $completedBy = [], $source)
    {
        if (in_array($transaction->status, [TransactionStatus::COMPLETED])) {
            throw new \Exception(__("Transaction already completed."));
        }

        if (!in_array($transaction->status, [
            TransactionStatus::PENDING,
            TransactionStatus::ONHOLD,
            TransactionStatus::CONFIRMED
        ])) {
            throw new \Exception(__("Invalid transaction status."));
        }
        $userAccount = get_user_account($transaction->user_id, $source);

//        dd($userAccount);
        $ledgerBalance = $this->getLedgerBalance($userAccount->id);
//        dd($ledgerBalance);
        $this->createLedgerEntry($transaction, $ledgerBalance);
//        dd('s');
        if ($transaction->calc == TransactionCalcType::CREDIT) {
            $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->amount));
            $userAccount->save();
        }
        $this->updateUserLeadState($transaction);
        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->completed_at = Carbon::now();
        $transaction->completed_by = $completedBy;
        $transaction->save();
        $transaction->fresh();

        if (has_deposit_bonus()) {
            $this->addDepositBonus($transaction);
        }

        if (referral_system()) {
            $this->addReferralCommissionDeposit($transaction);
        }

        return $userAccount->toArray();
    }
    public function confirmCoinbaseTransaction($transaction, $completedBy = [], $auto = false)
    {
        if (in_array($transaction->status, [TransactionStatus::COMPLETED])) {
            throw new \Exception(__("Transaction already completed."));
        }

        if (!in_array($transaction->status, [
            TransactionStatus::PENDING,
            TransactionStatus::ONHOLD,
            TransactionStatus::CONFIRMED
        ])) {
            throw new \Exception(__("Invalid transaction status."));
        }
//dd($transaction);
//        dd(data_get($transaction, 'meta.account_id'));
        if (data_get($transaction, 'meta.account_id') == 'coinbase'){
            $this->confirmInvest($transaction);
        }else{
            $userAccount = get_user_account($transaction->user_id);
//        dd($userAccount);
        $ledgerBalance = $this->getLedgerBalance($userAccount->id);
//        dd($ledgerBalance);
        $this->createLedgerEntry($transaction, $ledgerBalance);
//        dd('s');
        if ($transaction->calc == TransactionCalcType::CREDIT) {
            $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->amount));
            $userAccount->save();
        }
        $this->updateUserLeadState($transaction);
    }
        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->completed_at = Carbon::now();
        $transaction->completed_by = $completedBy;
        $transaction->save();
        $transaction->fresh();

        if (has_deposit_bonus()) {
            $this->addDepositBonus($transaction);
        }

        if (referral_system()) {
            $this->addReferralCommissionDeposit($transaction);
        }

        return true;
    }
    public function confirmInvest($transaction)
    {
//        dd($request->all());
        $currency = base_currency();

//        $input = $request->all();

        $plan = PricingScheme::find(data_get($transaction, 'meta.scheme') );
//        dd($plan);

        $input['currency'] = $currency;
        $input['account_type'] = 'normal';
//        dd($input);
        // Payment Source
        $account = 'unknown';
        $amount = $transaction->total;
//        if($plan->is_discount == 1 && $plan->discount_price > 0) {
//            // Amount & Balance
//            $amount = ($plan->discount_price) ? (float)$plan->discount_price : 0;
//        }

        $discount =  0;
//        dd($request->get('discount'),$discount);
        $leverage_amount =  0;
        $days_to_pass_amount =  0;
        $profit_split_amount =  0;
        $payout_amount =  0;
        $swap_free_amount =  0;
//        dd( isset($request['swap_free']),get_hash($request['swap_free']),$swap_free_amount);
        $totalAmount = BigDecimal::of($amount)->plus($leverage_amount)->plus($days_to_pass_amount)->plus($profit_split_amount)->plus($payout_amount)->plus($swap_free_amount);
        $payableAmount = to_minus($totalAmount , $discount);
//        $actualWithdraw = $payableAmount;
//        dd($totalAmount,$discount,$swap_free_amount);

        $input['bank'] = [];

//            $accountFrom = PaymentMethod::STRIPE;
//            $accountFrom[0] = the_hash(AccountClassification::CREDIT_DEBIT_CARD);
//            $accountFrom[1] = the_hash(PaymentMethod::STRIPE);

        $type = AccountClassification::CREDIT_DEBIT_CARD;
        $source = PaymentMethod::COINBASE;
//        dd($source);



        if(in_array($type,['wallets','forex'])) {
            if (empty($payableAmount)) {
                throw ValidationException::withMessages([
                    'amount' => __('Sorry, the funded amount is not valid.')
                ]);
            } elseif (empty($balance)) {
                throw ValidationException::withMessages([
                    'account' => __('Sorry, not enough balance in selected account.')
                ]);
            }
            if (BigDecimal::of($payableAmount)->compareTo($balance) > 0) {
                throw ValidationException::withMessages(['amount' => ['title' => __('Insufficient balance!'), 'message' => __('The amount exceeds your available funds.')]]);

            }
        }
        // Funded Plan
        if (blank($plan) || (!blank($plan) && $plan->status != PricingInvestmentStatus::ACTIVE)) {
            throw ValidationException::withMessages(['plan' => __('The selected plan may not available or invalid.')]);
        }

        $input['source'] = $source;
        $input['max_drawdown_limit'] = $plan->max_drawdown_limit;
        $input['daily_drawdown_limit'] = $plan->daily_drawdown_limit;
        $input['profit_share_user'] = $plan->profit_share_user;
        $input['profit_share_admin'] = $plan->profit_share_admin;
        $input['leverage'] = $plan->leverage;
        $input['type'] = $type;
//        if($type == 'banks'){
        $input['name'] = auth()->user()->name;
        $input['email'] = auth()->user()->email;
        $input['phone_number'] = auth()->user()->profile_phone;
        $input['profile_whatsapp'] = '';
//        }
//        if($leverage_amount > 0){
//            $input['leverage'] = $input['leverage'] + 50;
//        }
        $input['days_to_pass'] = 'year';
        if($days_to_pass_amount > 0){
            $input['days_to_pass'] ='unlimited';
        }
//        if($profit_split_amount > 0){
//            $input['profit_share_user'] = 90;
//            $input['profit_share_admin'] = 10;
//        }
        $input['payouts'] ='monthly';
        if($payout_amount > 0){
            $input['payouts'] ='weekly';
        }
        if($swap_free_amount > 0){
//            $input['payouts'] ='weekly';
        }
        $investment = new PricingInvestormService();
        $subscription = $investment->processSubscriptionDetails($input, $plan, $amount,$discount,$leverage_amount,$days_to_pass_amount,$profit_split_amount,$payout_amount,$swap_free_amount);
//dd($subscription);
        if (empty($subscription)) {
            throw ValidationException::withMessages(["scheme" => __("Sorry unable process subscription")]);
        }

            $invest = $investment->confirmSubscription($subscription);
//        dd($invest);
//            event(new FundedEvent($invest));
            if ($plan->approval == FundedApproval::AUTO) {
                $investment->approveSubscription($invest, 'auto-approved');
                $invest->fresh();
            }
//        dd($invest);
            return true;
//            try {
//                ProcessEmail::dispatch('investment-placed-customer', data_get($invest, 'user'), null, $invest);
//                ProcessEmail::dispatch('investment-placed-admin', data_get($invest, 'user'), null, $invest);
//            } catch (\Exception $e) {
//                save_mailer_log($e, 'investment-placed');
//            }




    }

    public function confirmForexTransaction($transaction, $completedBy = [], $auto = false)
    {
        if (in_array($transaction->status, [TransactionStatus::COMPLETED])) {
            throw new \Exception(__("Transaction already completed."));
        }

        if (!in_array($transaction->status, [
            TransactionStatus::PENDING,
            TransactionStatus::ONHOLD,
            TransactionStatus::CONFIRMED
        ])) {
            throw new \Exception(__("Invalid transaction status."));
        }
//dd($transaction);
        $userAccount = ForexTrading::find($transaction->account_to);
        $depositUrl = config('forextrading.depositUrl');

        $actualDeposit = $transaction->amount;
        if($userAccount->currency == 'USC'){
            $actualDeposit = $actualDeposit * 100;
        }
        $dataArray = [
            'Login' => $userAccount->login,
            'Amount' => $actualDeposit,
            'Comment' => "Deposit/".$transaction->tnx_currency."/".$transaction->tnx_method,

        ];
        $depositResponse = $this->sendApiPostRequest($depositUrl, $dataArray);
        if ($depositResponse->status() == 200 && $depositResponse->object() == 10009) {
//            dd($depositResponse);
            $getUserResponse = $this->getUserApi($userAccount->login);
            $lastDeposit = true;
            if ($getUserResponse->status() == 200) {
                $this->updateUserAccount($getUserResponse, $lastDeposit);
//dd($transaction);
                $this->updateUserLeadState($transaction);

                $transaction->status = TransactionStatus::COMPLETED;
                $transaction->completed_at = Carbon::now();
                $transaction->completed_by = $completedBy;
                $transaction->save();
                $transaction->fresh();

//                $this->addTotalDeposit($transaction);

                if (has_deposit_bonus()) {
                    $this->addDepositBonus($transaction);
                }

                if (referral_system()) {
                    $this->addReferralCommissionDeposit($transaction);
                }
            } else {
                throw new \Exception(__("Direct Deposit Forex API error." . $getUserResponse->serverError()));
            }
        } else {
            throw new \Exception(__("Direct Deposit Forex API error." . $depositResponse->serverError()));

        }
//            else {
////                $this->updateUserAccount($getUserResponse,$lastDeposit);
//                return redirect()->back()->with('error', 'something wrong!.please try again');
//            }
//            return redirect()->back()->with('message', 'Successfully deposit');

//        dd($userAccount);
//        $ledgerBalance = $this->getLedgerBalance($userAccount->id);
////        dd($ledgerBalance);
//        $this->createLedgerEntry($transaction, $ledgerBalance);
////        dd('s');
//        if ($transaction->calc == TransactionCalcType::CREDIT) {
//            $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->amount));
//            $userAccount->save();
//        }


        return $userAccount->toArray();
    }
    public function updateUserLeadState($transaction)
    {
        if(in_array($transaction->type,[TransactionType::DEPOSIT,TransactionType::FOREX_TRADING_DEPOSIT_DIRECT]) && $transaction->total >= 100) {

            if ($transaction->user->state != UserState::CLIENT) {
                $transaction->user->state = UserState::CLIENT;
                $transaction->user->save();
            }
        }

    }
    public function confirmTransactionWithSource($transaction, $completedBy, $source)
    {
//        dd($source);
        if (in_array($transaction->status, [TransactionStatus::COMPLETED])) {
            throw new \Exception(__("Transaction already completed."));
        }

        if (!in_array($transaction->status, [
            TransactionStatus::PENDING,
            TransactionStatus::ONHOLD,
            TransactionStatus::CONFIRMED,
            TransactionStatus::NONE
        ])) {
            throw new \Exception(__("Invalid transaction status."));
        }
//        $source = AccountBalanceType::SCHEME_PROFIT_BONUS;
        $userAccount = get_user_account($transaction->user_id, $source);
//        dd($userAccount);
        $ledgerBalance = $this->getLedgerBalance($userAccount->id);
//        dd($ledgerBalance);
//        dd($ledgerBalance,$userAccount,$source);

        $this->createLedgerEntry($transaction, $ledgerBalance);

//        dd('s');
//        if ($transaction->calc == TransactionCalcType::CREDIT) {
//            $userAccount = get_user_account($transaction->user_id);
//            $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->amount));
//            $userAccount->save();
//        }

        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->completed_at = Carbon::now();
        $transaction->completed_by = $completedBy;
        $transaction->save();
        $transaction->fresh();

        return $userAccount->toArray();
    }

    public function confirmTransactionForInvestment($transaction, $completedBy = [], $auto = false)
    {
        if (in_array($transaction->status, [TransactionStatus::COMPLETED])) {
            throw new \Exception(__("Transaction already completed."));
        }

        if (!in_array($transaction->status, [
            TransactionStatus::PENDING,
            TransactionStatus::ONHOLD,
            TransactionStatus::CONFIRMED
        ])) {
            throw new \Exception(__("Invalid transaction status."));
        }

        $userAccount = get_user_account($transaction->user_id);
//        dd($userAccount);
//        $ledgerBalance = $this->getLedgerBalance($userAccount->id);
//        dd($ledgerBalance);
//        $this->createLedgerEntry($transaction, $ledgerBalance);
//        dd('s');
//        if ($transaction->calc == TransactionCalcType::CREDIT) {
//            $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->amount));
//            $userAccount->save();
//        }

        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->completed_at = Carbon::now();
        $transaction->completed_by = $completedBy;
        $transaction->save();
        $transaction->fresh();

//        if (has_deposit_bonus()) {
//            $this->addDepositBonus($transaction);
//        }
//
//        if (referral_system()) {
//            $this->addReferralCommissionDeposit($transaction);
//        }

        return $userAccount->toArray();
    }

    public function confirmReferralTransaction($transaction, $completedBy = [], $auto = false)
    {
        if (in_array($transaction->status, [TransactionStatus::COMPLETED])) {
            throw new \Exception(__("Transaction already completed."));
        }

        if (!in_array($transaction->status, [
            TransactionStatus::PENDING,
            TransactionStatus::ONHOLD,
            TransactionStatus::CONFIRMED,
            TransactionStatus::NONE
        ])) {
            throw new \Exception(__("Invalid transaction status."));
        }
        $source = AccountBalanceType::SCHEME_PROFIT_BONUS;
        $userAccount = get_user_account($transaction->user_id, $source);
        $ledgerBalance = $this->getLedgerBalance($userAccount->id);
//        dd($ledgerBalance);
//        dd($ledgerBalance,$userAccount,$source);

        $this->createLedgerEntry($transaction, $ledgerBalance);

//        dd('s');
//        if ($transaction->calc == TransactionCalcType::CREDIT) {
//            $userAccount = get_user_account($transaction->user_id);
//            $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->amount));
//            $userAccount->save();
//        }

        $transaction->status = TransactionStatus::COMPLETED;
//        $transaction->completed_at = Carbon::now();
        $transaction->completed_by = $completedBy;
        $transaction->save();
        $transaction->fresh();

        return $userAccount->toArray();
    }


    public function confirmRewardSalaryTransaction($transaction, $completedBy = [], $auto = false)
    {
        if (in_array($transaction->status, [TransactionStatus::COMPLETED])) {
            throw new \Exception(__("Transaction already completed."));
        }

        if (!in_array($transaction->status, [
            TransactionStatus::PENDING,
            TransactionStatus::ONHOLD,
            TransactionStatus::CONFIRMED,
            TransactionStatus::NONE
        ])) {
            throw new \Exception(__("Invalid transaction status."));
        }
        $source = AccountBalanceType::REWARD_SALARY;
        $userAccount = get_user_account($transaction->user_id, $source);
        $ledgerBalance = $this->getLedgerBalance($userAccount->id);
//        dd($ledgerBalance);
//        dd($ledgerBalance,$userAccount,$source);

        $this->createLedgerEntry($transaction, $ledgerBalance);

//        dd('s');
//        if ($transaction->calc == TransactionCalcType::CREDIT) {
//            $userAccount = get_user_account($transaction->user_id);
//            $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->amount));
//            $userAccount->save();
//        }

        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->completed_at = Carbon::now();
        $transaction->completed_by = $completedBy;
        $transaction->save();
        $transaction->fresh();

        return $userAccount->toArray();
    }

    /**
     * @param $reference
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    public function getTransactionByReference($reference)
    {
        return Transaction::where('reference', $reference)->first();
    }

    private function addDepositBonus($transaction)
    {
        if ($transaction->type != TransactionType::DEPOSIT) return true;
        if ($transaction->status != TransactionStatus::COMPLETED) return true;

        $tnxCount = Transaction::where('user_id', $transaction->user_id)
            ->where('type', TransactionType::DEPOSIT)
            ->where('status', TransactionStatus::COMPLETED)
            ->count();

        if ($tnxCount > 1) return true;

        $currency = base_currency();
        $bonus = deposit_bonus($transaction->amount);
        if (empty($bonus)) return true;

        $tnxData = [
            'type' => TransactionType::BONUS,
            'calc' => TransactionCalcType::CREDIT,
            'base_amount' => $bonus,
            'base_currency' => $currency,
            'amount' => $bonus,
            'currency' => $currency,
            'method' => 'system',
            'desc' => "Bonus for First Deposit",
            'user_id' => $transaction->user_id,
            'reference' => $transaction->tnx
        ];

        $completedBy = (!empty(system_admin())) ? ['id' => system_admin()->id, 'name' => system_admin()->name] : [];
        $createBy = (!empty(system_admin())) ? system_admin() : false;

        $bonusTransaction = $this->createManualTransaction($tnxData, $createBy);
        $this->confirmTransaction($bonusTransaction, $completedBy);

        return true;
    }
    private function addTotalDeposit($transaction)
    {
      $user = User::find($transaction->user_id);
        $user->total_deposit = BigDecimal::of($user->total_deposit)->plus($transaction->total);
        $user->total_equity = BigDecimal::of($user->total_equity)->plus($transaction->total);
        $user->save();
        return true;
    }

    public function addSignupBonus($user)
    {
        if (empty($user)) return true;
        if ($user->meta('registration_method') != 'email') return true;

        if (referral_system() && allow_bonus_joined('signup') && $user->has_valid_referer && gss('referral_signup_user_reward', 'no') == 'yes') {
            return true;
        }

        $currency = base_currency();
        $amount = signup_bonus('amount');
        if (empty($amount)) return true;

        $tnxData = [
            'type' => TransactionType::BONUS,
            'calc' => TransactionCalcType::CREDIT,
            'base_amount' => $amount,
            'base_currency' => $currency,
            'amount' => $amount,
            'currency' => $currency,
            'method' => 'system',
            'desc' => "Signup Bonus",
            'user_id' => $user->id
        ];

        $completedBy = (!empty(system_admin())) ? ['id' => system_admin()->id, 'name' => system_admin()->name] : [];
        $createBy = (!empty(system_admin())) ? system_admin() : false;

        $signupTransaction = $this->createManualTransaction($tnxData, $createBy);
        $this->confirmTransaction($signupTransaction, $completedBy);

        return true;
    }

    public function addReferralCommission($user)
    {
        if (empty($user) || !$user->has_valid_referrer) return true;

        $currency = base_currency();
        $createdBy = (!empty(system_admin())) ? system_admin() : false;

        $tnxData = [
            'type' => TransactionType::REFERRAL,
            'calc' => TransactionCalcType::CREDIT,
            'base_currency' => $currency,
            'currency' => $currency,
            'method' => 'system',
        ];

        $refererBonus = referral_bonus_referer('signup', 0);
        if (allow_bonus_referer('signup') && $refererBonus > 0) {
            $tnxData['base_amount'] = $tnxData['amount'] = $refererBonus;
            $tnxData['user_id'] = $user->refer;
            $tnxData['desc'] = "Commission for Referral Signup";

            $tnxData['referral'] = [
                'level' => 'lv1',
                'bonus' => gss('referral_signup_referer_bonus', 0),
                'calc' => 'fixed',
                'user' => $user->id,
                'type' => 'refer',
                'action' => 'signup',
            ];

            $this->createManualTransaction($tnxData, $createdBy);
        }

        $userBonus = referral_bonus_joined('signup', 0);
        if (allow_bonus_joined('signup') && $userBonus > 0) {
            $tnxData['base_amount'] = $tnxData['amount'] = $userBonus;
            $tnxData['user_id'] = $user->id;
            $tnxData['desc'] = "Commission for Referral Join";

            $tnxData['referral'] = [
                'level' => 'lv0',
                'bonus' => gss('referral_signup_user_bonus', 0),
                'calc' => 'fixed',
                'user' => $user->refer,
                'type' => 'join',
                'action' => 'signup',
            ];


            $this->createManualTransaction($tnxData, $createdBy);
        }

        return true;
    }

    public function addReferralCommissionDeposit($transaction)
    {
        if ($transaction->type != TransactionType::DEPOSIT || !$transaction->customer->has_valid_referrer) return true;

        $tnxCount = Transaction::where('user_id', $transaction->user_id)
            ->where('type', TransactionType::DEPOSIT)
            ->where('status', TransactionStatus::COMPLETED)
            ->count();

        if ($tnxCount > 1) return true;

        $createdBy = (!empty(system_admin())) ? system_admin() : false;
        $currency = base_currency();

        $tnxData = [
            'type' => TransactionType::REFERRAL,
            'calc' => TransactionCalcType::CREDIT,
            'base_currency' => $currency,
            'currency' => $currency,
            'method' => 'system',
            'desc' => __("Referral Deposit Bonus"),
            'reference' => $transaction->tnx,
        ];

        $refererBonus = referral_bonus_referer('deposit', $transaction->amount);
        if (allow_bonus_referer('deposit') && $refererBonus > 0) {
            $tnxData['base_amount'] = $tnxData['amount'] = $refererBonus;
            $tnxData['user_id'] = $transaction->customer->refer;

            $tnxData['referral'] = [
                'level' => 'lv1',
                'bonus' => gss('referral_deposit_referer_bonus', 0),
                'calc' => gss('referral_deposit_referer_type', 'percent'),
                'user' => $transaction->user_id,
                'type' => 'refer',
                'action' => 'deposit',
                'tnx_id' => $transaction->id,
                'tnx_amount' => $transaction->amount,
            ];

            $this->createManualTransaction($tnxData, $createdBy);
        }

        $userBonus = referral_bonus_joined('deposit', $transaction->amount);
        if (allow_bonus_joined('deposit') && $userBonus > 0) {
            $tnxData['base_amount'] = $tnxData['amount'] = $userBonus;
            $tnxData['user_id'] = $transaction->user_id;

            $tnxData['referral'] = [
                'level' => 'lv0',
                'bonus' => gss('referral_deposit_user_bonus', 0),
                'calc' => gss('referral_deposit_user_type', 'fixed'),
                'user' => $transaction->customer->refer,
                'type' => 'join',
                'action' => 'deposit',
                'tnx_id' => $transaction->id,
                'tnx_amount' => $transaction->amount,
            ];

            $this->createManualTransaction($tnxData, $createdBy);
        }

        return true;
    }

}
