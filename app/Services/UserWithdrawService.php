<?php

namespace App\Services;

use Txn;
use Carbon\Carbon;
use App\Enums\TxnType;
use App\Models\Wallet;
use App\Enums\TxnStatus;
use Brick\Math\BigDecimal;
use App\Models\Transaction;
use App\Traits\NotifyTrait;
use App\Models\WithdrawAccount;
use App\Models\WithdrawalSchedule;
use Illuminate\Support\Facades\Auth;

class UserWithdrawService
{

	use NotifyTrait;

	private function checkAllowed() {
		if (!setting('user_withdraw', 'permission') || !Auth::user()->withdraw_status) {
			abort('403', __('Withdraw Disable Now'));
		}
	}

	private function checkSchedule() {
		$withdrawOffDays = WithdrawalSchedule::where('status', 0)->pluck('name')->toArray();
		$date = Carbon::now();
		$today = $date->format('l');
		if (in_array($today, $withdrawOffDays)) {
			abort('403', __('Today is the off day for withdraw'));
		}
	}

	private function checkDailyLimit() {
		$todayTransaction = Transaction::where('user_id', Auth::id())
			->where(function ($query) {
				$query->where('type', TxnType::Withdraw)
					->orWhere('type', TxnType::WithdrawAuto);
			})
			->whereDate('created_at', Carbon::today())
			->count();
		$dayLimit = (float)Setting('withdraw_day_limit', 'fee');
		if ($todayTransaction >= $dayLimit) {
			notify()->error(__('Today Withdraw limit has been reached'), 'Error');
			return false;
		}

		return true;
	}

	private function checkRange($amount, $withdrawMethod) {
		if ($amount < $withdrawMethod->min_withdraw || $amount > $withdrawMethod->max_withdraw) {
            $currencySymbol = setting('currency_symbol', 'global');
            $message = 'Enter an amount within the specified range: ' . $currencySymbol . number_format($withdrawMethod->min_withdraw, 2) . ' to ' . $currencySymbol . number_format($withdrawMethod->max_withdraw, 2);
            notify()->error($message, 'Error');
            return false;
        }
		return true;
	}

	private function checkInsufficientBalance($wallet, $totalAmount) {
		$available_balance = $wallet->available_balance;
        if ($totalAmount->compareTo($available_balance) > 0) {
            notify()->error(__('Insufficient balance in your wallet.'), 'Error');
            return false;
        }

		return true;
	}

	private function createTransaction($amount, $fee, $totalAmount, $withdrawMethod, $withdrawAccount, $payAmount, $wallet) {
		$type = $withdrawMethod->type == 'auto' ? TxnType::WithdrawAuto : TxnType::Withdraw;

		// Creating Transaction
        $transaction = Txn::new(
            $amount,
            $fee,
            $totalAmount,
            $withdrawMethod->name,
            'Withdraw With ' . $withdrawAccount->method_name,
            $type,
            TxnStatus::Pending,
            $withdrawMethod->currency,
            $payAmount,
            Auth::id(),
            null,
            'User',
            json_decode($withdrawAccount->credentials, true),
            'none',
            $wallet->id,
            $wallet->slug . '_withdraw'
        );

		return $transaction;
	}

	private function doEmail($transaction, $withdrawMethod, $withdrawAccount) {
		$shortcodes = [
            '[[site_url]]' => route('home'),
            '[[site_title]]' => setting('site_title', 'global'),
            '[[full_name]]' => $transaction->user->full_name,
            '[[transaction_id]]' => $transaction->tnx,
            '[[amount]]' => $transaction->amount . ' ' . setting('site_currency'),
            '[[withdrawal_method_name]]' => $withdrawMethod->name,
            '[[processing_time]]' => $withdrawMethod->required_time . ' ' . $withdrawAccount->method->required_time_format,
            '[[status]]' => 'pending',
        ];
        $this->mailNotify(Auth::user()->email, 'withdraw_request', $shortcodes);
        $this->mailNotify(setting('site_email', 'global'), 'withdraw_request_admin', $shortcodes);
	}

	public function main($input) {
        $amount = (float)$input['amount'];
        $withdrawAccount = WithdrawAccount::find($input['withdraw_account']);
        $withdrawMethod = $withdrawAccount->method;
		$wallet_id = $input['wallet_id'];
        $wallet = Wallet::find($wallet_id);

		// basic checks
		$this->checkAllowed();
        $this->checkSchedule();
		$check_daily_limit = $this->checkDailyLimit();
		$check_range = $this->checkRange($amount, $withdrawMethod);

		// calculating fee
        $fee = $withdrawMethod->charge_type == 'percentage' ? (($withdrawMethod->charge / 100) * $amount) : $withdrawMethod->charge;
        $totalAmount = BigDecimal::of($amount + $fee)->abs();

		// Check Insufficient Balance
		$check_insufficient_balance = $this->checkInsufficientBalance($wallet, $totalAmount);

		// Send redirect if any check is failed
		if(!$check_daily_limit || !$check_range || !$check_insufficient_balance) {
			return ['redirect_back' => true];
		}

		// calculating amount after conversion
		$totalAmount = $totalAmount->toFloat();
        $payAmount = ($amount * $withdrawMethod->rate) - ($fee * $withdrawMethod->rate);
        
		// Create Transaction
		$transaction = $this->createTransaction($amount, $fee, $totalAmount, $withdrawMethod, $withdrawAccount, $payAmount, $wallet);

		// Remove the balance from wallet
        $wallet->available_balance = $wallet->available_balance - $totalAmount;
        $wallet->save();

		// Do emails
		$this->doEmail($transaction, $withdrawMethod, $withdrawAccount);

		// Auto Payment system
        // if ($withdrawMethod->type == 'auto') {
        //     $gatewayCode = $withdrawMethod->gateway->gateway_code;
        //     return self::withdrawAutoGateway($gatewayCode, $transaction);
        // }

		return [
			'transaction' => $transaction,
			'redirect_back' => false
		];
        
	}
}
