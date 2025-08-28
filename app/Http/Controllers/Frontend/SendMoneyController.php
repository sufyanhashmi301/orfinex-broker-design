<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\AccountBalanceType;
use App\Enums\ForexAccountStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\ForexAccount;
use App\Models\Transaction;
use App\Models\User;
use App\Rules\ForexLoginBelongsToUser;
use App\Services\ForexApiService;
use App\Services\WalletService;
use App\Traits\ForexApiTrait;
use App\Traits\NotifyTrait;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransferHistoryExport;
use Txn;

class SendMoneyController extends Controller
{
    use ForexApiTrait,NotifyTrait;
    protected $forexApiService;
    protected $walletService;

    public function __construct(ForexApiService $forexApiService,WalletService $walletService)
    {
        $this->forexApiService = $forexApiService;
        $this->walletService = $walletService;
    }
    //external transfer
    public function sendMoney(Request $request)
    {
//        $clientIp = request()->ip();
//        if(!in_array($clientIp,['127.0.0.1' , '::1'])) {
//            if (auth()->user()->ib_login) {
//                $getUserResponse = $this->getUserApi(auth()->user()->ib_login);
//                if ($getUserResponse->status() == 200 && isset($getUserResponse->object()->Login)) {
//                    $balance = $getUserResponse->object()->Balance;
//                    auth()->user()->update(['ib_balance' => $balance]);
//                    auth()->setUser(auth()->user()->fresh());
//                }
//            }
////            $this->syncForexAccounts(auth()->id());
//        }
        $forexAccounts = ForexAccount::with('schema')->traderType()
            ->where('user_id', auth()->id())
            ->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)
            ->orderBy('id', 'desc')
            ->get();
        if (! setting('is_external_transfer', 'transfer_external') or ! \Auth::user()->transfer_status) {
            abort('403', __('Send Money Disable Now'));
        }

        $isStepOne = 'current';
        $isStepTwo = '';

        return view('frontend::send_money.now', compact('isStepOne', 'isStepTwo', 'forexAccounts'));
    }
    public function sendMoneyNow(Request $request)
{
    // Check if the user has the permission to transfer
    if (!setting('is_external_transfer', 'transfer_external') || !\Auth::user()->transfer_status) {
        abort(403, __('Send Money Disabled Now'));
    }

    // Check if KYC is completed
  if (!setting('external_transfer_amount', 'kyc_permissions') && auth()->user()->kyc < kyc_required_completed_level()) {
        notify()->error('KYC Pending: Please complete your KYC verification to proceed with your external amount transfer', __('Error'));
        return redirect()->route('user.kyc');
    }

    // Validate inputs
    $validator = Validator::make($request->all(), [
        'target_id' => ['required', 'different:receiver_account'],
        'receiver_account' => ['required', 'different:target_id'],
        'amount' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'],
    ], [
        'target_id.required' => __('Please select the sender account to transfer from'),
        'receiver_account.required' => __('Please select the receiver account to transfer to'),
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        notify()->error($validator->errors()->first(), __('Error'));
        return redirect()->back();
    }

    // Begin a database transaction
    \DB::beginTransaction();

    try {
        $input = $request->all();
        $targetId = get_hash($input['target_id']);
        $receiverAccount = $input['receiver_account'];
        $amount = (float)$input['amount'];
        $targetType = $request->input('target_type'); // 'forex' or 'wallet'

        // Check if external transfers are enabled
        if (!setting('is_external_transfer', 'transfer_external')) {
            abort(403, __('External transfers are currently disabled.'));
        }

        // Check if automatic approval is enabled
        $isAutoApprove = setting('is_external_transfer_auto_approve', 'transfer_external');
        if (!$isAutoApprove) {
            // If automatic approval is disabled, set the transaction status to pending
            $transactionStatus = TxnStatus::Pending;
        } else {
            // If automatic approval is enabled, set the transaction status to success
            $transactionStatus = TxnStatus::Success;
        }

        // Check daily send limit for successful transactions only
        $dailyLimit = setting('external_send_daily_limit', 'fee');
        $todayTransfers = Transaction::where('user_id', \Auth::id())
            ->where('type', TxnType::SendMoney)
            ->where('status', TxnStatus::Success) // Only count successful transactions
            ->whereDate('created_at', today())
            ->count();

        if ($todayTransfers > $dailyLimit) {
            notify()->error(__('You have reached the daily transfer limit.'), __('Error'));
            return redirect()->back();
        }

        // Validate sender's account based on target type
        if ($targetType === 'forex') {
            $fromForexAccount = ForexAccount::where('login', $targetId)->where('user_id', \Auth::id())->first();
            if (!$fromForexAccount) {
                throw new \Exception(__('The selected Forex account does not belong to you.'));
            }
            $scaledAmount = apply_cent_account_adjustment($targetId, $amount);
            $balance = $this->forexApiService->getValidatedBalance(['login' => $targetId]);

            if (BigDecimal::of($scaledAmount)->compareTo(BigDecimal::of($balance)) > 0) {
                throw new \Exception(__("Insufficient funds in your account."));
            }
        } elseif ($targetType === 'wallet') {
            $wallet = get_user_account_by_wallet_id($targetId, \Auth::id());
            if (!$wallet) {
                throw new \Exception(__('The selected Wallet account does not belong to you.'));
            }
            if ($wallet->balance === AccountBalanceType::IB_WALLET) {
                $ibMinLimit = setting('min_ib_wallet_withdraw_limit', 'withdraw_settings');
                if ($amount < $ibMinLimit) {
                    notify()->error(__('You must transfer at least :limit from IB Wallet.', [
                        'limit' => setting('currency_symbol', 'global') . $ibMinLimit
                    ]), 'Error');
                    return redirect()->back();
                }
            }
            $balance = BigDecimal::of($wallet->amount);
        }

        // Validate amount range
        $min = setting('external_min_send', 'transfer_external');
        $max = setting('external_max_send', 'transfer_external');
        if ($amount < $min || $amount > $max) {
            throw new \Exception(__('Please send an amount within the range ') . setting('currency_symbol', 'global') . $min . ' - ' . $max);
        }

        // Calculate charge and total amount
        $charge = $this->calculateChargeExternal($amount);
        $totalAmount = $amount + $charge;

        // Check if sender's balance is sufficient
        if (BigDecimal::of($totalAmount)->compareTo($balance) > 0) {
            throw new \Exception(__("Insufficient funds in your account."));
        }

        // Validate receiver's Forex account
        $toUserForexAccount = ForexAccount::where('login', $receiverAccount)->first();
        if (!$toUserForexAccount) {
            throw new \Exception(__('Receiver Forex account is invalid or inactive.'));
        }

        // Check if the first minimum deposit is required and not yet paid for the receiver's account
        if (isset($toUserForexAccount->schema->first_min_deposit) && $toUserForexAccount->schema->first_min_deposit > 0) {
            if (!$toUserForexAccount->first_min_deposit_paid && $amount < $toUserForexAccount->schema->first_min_deposit) {
                $currencySymbol = setting('currency_symbol', 'global');
                $message = __('Please deposit the first minimum amount of ') . $currencySymbol . $toUserForexAccount->schema->first_min_deposit;
                notify()->error($message, __('Error'));
                return redirect()->back();
            }
        }

        $toUser = $toUserForexAccount->user;

        // Create transaction entries with the appropriate status
        $sendDescription = __('Transfer Money to ') . $toUser->username . '-' . $receiverAccount;
        $txnInfoSender = Txn::new($amount, $charge, $totalAmount, 'system', $sendDescription, TxnType::SendMoney, $transactionStatus, null, null, \Auth::id(), $toUser->id, 'User', [], $input['note'], $targetId, $targetType);

        $receiveDescription = __('Transfer Money from ') . \Auth::user()->username . '-' . $targetId;
        $txnInfoReceiver = Txn::new($amount, 0, $amount, 'system', $receiveDescription, TxnType::ReceiveMoney, $transactionStatus, null, null, $toUser->id, \Auth::id(), 'User', [], $input['note'], $receiverAccount, 'forex');

        // Handle Forex-to-Forex or Wallet-to-Forex transfers
        if ($targetType == 'forex') {
            $this->forexToForexTransfer($targetId, $receiverAccount, $totalAmount, $txnInfoSender);
        } elseif ($targetType == 'wallet') {
            $this->walletToForexTransfer($wallet, $targetId, $receiverAccount, $totalAmount, $txnInfoSender, $txnInfoReceiver);
        }

        // Update transaction status to success (if automatic approval is enabled)
        if ($transactionStatus === TxnStatus::Success) {
            Txn::update($txnInfoSender->tnx, TxnStatus::Success, \Auth::id(), __('Transfer Successful'));
            Txn::update($txnInfoReceiver->tnx, TxnStatus::Success, $toUser->id, __('Transfer Successful'));
        }

        // Update MT5 balances for Forex accounts
        if ($targetType == 'forex') {
            // Mark first minimum deposit as paid for the receiver's account
            if (isset($toUserForexAccount->schema->first_min_deposit) && $toUserForexAccount->schema->first_min_deposit > 0) {
                if (!$toUserForexAccount->first_min_deposit_paid && $amount >= $toUserForexAccount->schema->first_min_deposit) {
                    $toUserForexAccount->first_min_deposit_paid = 1;
                    $toUserForexAccount->save();
                }
            }
            mt5_update_balance($targetId, $this->forexApiService->getValidatedBalance(['login' => $targetId]));
        }
        mt5_update_balance($receiverAccount, $this->forexApiService->getValidatedBalance(['login' => $receiverAccount]));

        // Commit the transaction
        \DB::commit();

        // Notify the user of success
        notify()->success(__('Successfully Sent Money'), __('Success'));

        // Send Email Notifications
        $this->sendEmailNotificationExternal($txnInfoSender, $toUser, $receiverAccount);

        return redirect()->route('user.notify');

    } catch (\Exception $e) {
        // Rollback the transaction in case of failure
        \DB::rollBack();

        // Log the error and notify the user
        Log::error(__('Transaction failed: ') . $e->getMessage());
        notify()->error($e->getMessage(), __('Error'));

        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
}
    private function calculateChargeExternal($amount)
    {
        $chargeType = setting('send_charge_type', 'fee');
        $charge = (float) setting('send_charge', 'fee');

        if ($chargeType === 'percentage') {
            $charge = $amount * ($charge / 100);
        }

        return $charge;
    }
    private function forexToForexTransfer($targetId, $receiverAccount, $totalAmount, $txnInfoSender)
    {
        // Perform Forex-to-Forex transfer
        $comment = 'ext/' . $targetId . '/to/' . $receiverAccount;
        $this->adjustedForexToForexTransfer($targetId, $receiverAccount, $totalAmount,$txnInfoSender->amount, $comment);

    }
    private function adjustedForexToForexTransfer($senderLogin, $receiverLogin, $amountToDeduct,$amountToCredit, $comment)
    {
        $senderIsCent = $this->isCentAccount($senderLogin);
        $receiverIsCent = $this->isCentAccount($receiverLogin);

        // Determine scaled amounts
        $amountToDeduct = $senderIsCent ? $amountToDeduct * 100 : $amountToDeduct;
        $amountToCredit = $receiverIsCent ? $amountToCredit * 100 : $amountToCredit;

        $api = $this->forexApiService;

        // 1. Deduct from sender
        $withdrawData = [
            'login' => $senderLogin,
            'Amount' => $amountToDeduct,
            'type' => 2,
            'TransactionComments' => $comment
        ];
        
        $withdrawResponse = $api->balanceOperation($withdrawData);
        if (!$withdrawResponse['success'] || 
            !isset($withdrawResponse['result']['responseCode']) ||
            !($withdrawResponse['result']['responseCode'] == 10009 || $withdrawResponse['result']['responseCode'] === 'MT_RET_REQUEST_DONE')) {
            
            $errorMsg = $withdrawResponse['messages'] ?? 'Unknown API error';
            Log::error("Forex withdrawal failed", [
                'sender_login' => $senderLogin,
                'amount' => $amountToDeduct,
                'response' => $withdrawResponse,
                'error' => $errorMsg
            ]);
            throw new \Exception(__('Forex Deduction Failed: ') . $errorMsg);
        }

        // 2. Credit to receiver with rollback capability
        $depositData = [
            'login' => $receiverLogin,
            'Amount' => $amountToCredit,
            'type' => 1,
            'TransactionComments' => $comment
        ];
        
        $depositResponse = $api->balanceOperation($depositData);
        if (!$depositResponse['success'] || 
            !isset($depositResponse['result']['responseCode']) ||
            !($depositResponse['result']['responseCode'] == 10009 || $depositResponse['result']['responseCode'] === 'MT_RET_REQUEST_DONE')) {
            
            // Rollback the withdrawal from sender
            try {
                $rollbackData = [
                    'login' => $senderLogin,
                    'Amount' => $amountToDeduct,
                    'type' => 1, // Deposit back to sender
                    'TransactionComments' => 'Rollback/' . $comment
                ];
                $rollbackResponse = $api->balanceOperation($rollbackData);
                
                if (!$rollbackResponse['success']) {
                    Log::critical("Failed to rollback forex withdrawal after deposit failure", [
                        'sender_login' => $senderLogin,
                        'receiver_login' => $receiverLogin,
                        'amount' => $amountToDeduct,
                        'original_error' => $depositResponse['messages'] ?? 'Deposit failed',
                        'rollback_error' => $rollbackResponse['messages'] ?? 'Rollback failed'
                    ]);
                }
            } catch (\Exception $rollbackError) {
                Log::critical("Exception during forex rollback", [
                    'sender_login' => $senderLogin,
                    'receiver_login' => $receiverLogin,
                    'amount' => $amountToDeduct,
                    'rollback_error' => $rollbackError->getMessage()
                ]);
            }
            
            $errorMsg = $depositResponse['messages'] ?? 'Unknown API error';
            Log::error("Forex deposit failed", [
                'receiver_login' => $receiverLogin,
                'amount' => $amountToCredit,
                'response' => $depositResponse,
                'error' => $errorMsg
            ]);
            throw new \Exception(__('Forex Deposit Failed: ') . $errorMsg);
        }
    }

    /**
     * Process Forex to Forex transfer with proper error handling and rollback
     */
    private function processForexToForexTransfer($senderLogin, $receiverLogin, $amountToDeduct, $amountToCredit, $comment)
    {
        try {
            $this->adjustedForexToForexTransfer($senderLogin, $receiverLogin, $amountToDeduct, $amountToCredit, $comment);
        } catch (\Exception $e) {
            Log::error("Forex to Forex transfer failed", [
                'sender_login' => $senderLogin,
                'receiver_login' => $receiverLogin,
                'amount_to_deduct' => $amountToDeduct,
                'amount_to_credit' => $amountToCredit,
                'error' => $e->getMessage()
            ]);
            throw new \Exception(__('Forex transfer failed. Please try again later.'));
        }
    }

    /**
     * Process Forex to Wallet transfer with proper error handling
     */
    private function processForexToWalletTransfer($targetId, $receiverWallet, $totalAmount, $amount, $txnInfoSender, $txnInfoReceiver)
    {
        $comment = 'Int/F/to/W/' . substr($txnInfoSender->tnx, -7);

        $withdrawData = [
            'login' => $targetId,
            'Amount' => apply_cent_account_adjustment($targetId, $totalAmount),
            'type' => 2, // Withdraw from Forex
            'TransactionComments' => $comment
        ];

        try {
            $withdrawResponse = $this->forexApiService->balanceOperation($withdrawData);
            
            if (!$withdrawResponse['success'] || 
                !isset($withdrawResponse['result']['responseCode']) ||
                !($withdrawResponse['result']['responseCode'] == 10009 || $withdrawResponse['result']['responseCode'] === 'MT_RET_REQUEST_DONE')) {
                
                $errorMsg = $withdrawResponse['messages'] ?? 'Unknown API error';
                throw new \Exception(__('Forex withdrawal failed: ') . $errorMsg);
            }

            // Process wallet credit with error handling
            try {
                $receiverLedgerBalance = $this->walletService->getLedgerBalance($receiverWallet->id);
                $this->walletService->createCreditLedgerEntry($txnInfoReceiver, $receiverLedgerBalance);
                $receiverWallet->amount = BigDecimal::of($receiverWallet->amount)->plus(BigDecimal::of($amount));
                $receiverWallet->save();
            } catch (\Exception $walletError) {
                // Try to reverse the forex operation
                try {
                    $reverseData = [
                        'login' => $targetId,
                        'Amount' => apply_cent_account_adjustment($targetId, $totalAmount),
                        'type' => 1, // Deposit back to Forex
                        'TransactionComments' => 'Reverse/' . $comment
                    ];
                    $reverseResponse = $this->forexApiService->balanceOperation($reverseData);
                    if (!$reverseResponse['success']) {
                        Log::critical("Failed to reverse forex operation after wallet error", [
                            'original_error' => $walletError->getMessage(),
                            'reverse_response' => $reverseResponse,
                            'forex_account' => $targetId,
                            'amount' => $totalAmount
                        ]);
                    }
                } catch (\Exception $reverseError) {
                    Log::critical("Exception during forex reverse operation after wallet error", [
                        'original_error' => $walletError->getMessage(),
                        'reverse_error' => $reverseError->getMessage(),
                        'forex_account' => $targetId,
                        'amount' => $totalAmount
                    ]);
                }
                throw $walletError;
            }

        } catch (\Exception $e) {
            Log::error("Forex to Wallet transfer failed", [
                'target_id' => $targetId,
                'receiver_wallet_id' => $receiverWallet->id,
                'total_amount' => $totalAmount,
                'error' => $e->getMessage()
            ]);
            throw new \Exception(__('Forex to Wallet transfer failed. Please try again later.'));
        }
    }

    /**
     * Process Wallet to Forex transfer with proper error handling
     */
    private function processWalletToForexTransfer($wallet, $receiverAccount, $totalAmount, $amount, $balance, $txnInfoSender, $txnInfoReceiver)
    {
        try {
            // First, process wallet debit
            $ledgerBalance = $this->walletService->getLedgerBalance($wallet->id);
            $this->walletService->createDebitLedgerEntry($txnInfoSender, $ledgerBalance);
            $wallet->amount = $balance->minus(BigDecimal::of($totalAmount));
            $wallet->save();

            // Then, deposit to Forex
            $comment = 'Int/W/to/F/' . substr($txnInfoReceiver->tnx, -7);
            $depositData = [
                'login' => $receiverAccount,
                'Amount' => apply_cent_account_adjustment($receiverAccount, $amount),
                'type' => 1, // Deposit into Forex
                'TransactionComments' => $comment
            ];

            $depositResponse = $this->forexApiService->balanceOperation($depositData);
            
            if (!$depositResponse['success'] || 
                !isset($depositResponse['result']['responseCode']) ||
                !($depositResponse['result']['responseCode'] == 10009 || $depositResponse['result']['responseCode'] === 'MT_RET_REQUEST_DONE')) {
                
                // Rollback wallet transaction
                $wallet->amount = $balance; // Restore original balance
                $wallet->save();
                
                // Delete the ledger entry
                DB::table('ledgers')->where('transaction_id', $txnInfoSender->id)->delete();
                
                $errorMsg = $depositResponse['messages'] ?? 'Unknown API error';
                throw new \Exception(__('Forex deposit failed: ') . $errorMsg);
            }

        } catch (\Exception $e) {
            Log::error("Wallet to Forex transfer failed", [
                'wallet_id' => $wallet->id,
                'receiver_account' => $receiverAccount,
                'total_amount' => $totalAmount,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
            throw new \Exception(__('Wallet to Forex transfer failed. Please try again later.'));
        }
    }

    /**
     * Process Wallet to Wallet transfer with proper error handling
     */
    private function processWalletToWalletTransfer($wallet, $receiverWallet, $totalAmount, $amount, $balance, $txnInfoSender, $txnInfoReceiver)
    {
        try {
            // Process sender wallet debit
            $ledgerBalance = $this->walletService->getLedgerBalance($wallet->id);
            $this->walletService->createDebitLedgerEntry($txnInfoSender, $ledgerBalance);
            $wallet->amount = $balance->minus(BigDecimal::of($totalAmount));
            $wallet->save();

            try {
                // Process receiver wallet credit
                $receiverLedgerBalance = $this->walletService->getLedgerBalance($receiverWallet->id);
                $this->walletService->createCreditLedgerEntry($txnInfoReceiver, $receiverLedgerBalance);
                $receiverWallet->amount = BigDecimal::of($receiverWallet->amount)->plus(BigDecimal::of($amount));
                $receiverWallet->save();
            } catch (\Exception $receiverError) {
                // Rollback sender wallet transaction
                $wallet->amount = $balance; // Restore original balance
                $wallet->save();
                
                // Delete the sender's ledger entry
                DB::table('ledgers')->where('transaction_id', $txnInfoSender->id)->delete();
                
                throw $receiverError;
            }

        } catch (\Exception $e) {
            Log::error("Wallet to Wallet transfer failed", [
                'sender_wallet_id' => $wallet->id,
                'receiver_wallet_id' => $receiverWallet->id,
                'total_amount' => $totalAmount,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
            throw new \Exception(__('Wallet transfer failed. Please try again later.'));
        }
    }
    private function isCentAccount($login)
    {
        $account = \App\Models\ForexAccount::where('login', $login)->with('schema')->first();
        return $account && $account->schema && $account->schema->is_cent_account;
    }

    private function walletToForexTransfer($wallet, $targetId, $receiverAccount, $totalAmount, $txnInfoSender, $txnInfoReceiver)
    {
//        try {
            // Start a database transaction to ensure all or nothing execution
            \DB::beginTransaction();

            // Get the ledger balance before debiting
            $ledgerBalance = $this->walletService->getLedgerBalance($wallet->id);

            // Check if the ledgerBalance retrieval was successful
            if (!$ledgerBalance) {
                throw new \Exception(__('Failed to retrieve wallet balance: '));
            }

            // Create a debit ledger entry and reduce wallet balance
            $this->walletService->createDebitLedgerEntry($txnInfoSender, $ledgerBalance);
            $wallet->amount = BigDecimal::of($wallet->amount)->minus(BigDecimal::of($totalAmount));
            $wallet->save();

            // Prepare data for Forex deposit
            $comment = 'ext/W/' . substr($txnInfoReceiver->tnx, -7);
            $depositData = [
                'login' => $receiverAccount,
                'Amount' => apply_cent_account_adjustment($receiverAccount, $txnInfoSender->amount),
                'type' => 1, // Deposit into Forex
                'TransactionComments' => $comment
            ];
//            dd($depositData);

            // Perform the Forex deposit
            $depositResponse = $this->forexApiService->balanceOperation($depositData);
//dd($depositResponse);
            // Check if the Forex deposit operation was successful
            if (!$depositResponse['success'] || 
                !($depositResponse['result']['responseCode'] == 10009 || $depositResponse['result']['responseCode'] === 'MT_RET_REQUEST_DONE')
            ) {
                $responseMessage = $depositResponse['messages'][0] ?? __('Forex Deposit Failed');
                throw new \Exception(__('Forex Deposit Failed: ') . $responseMessage);
            }

            // Commit the transaction after successful Forex deposit
            \DB::commit();

            // Notify the user of the success
            notify()->success(__('Deposit into Forex account successful!'), 'Success');

//        } catch (\Exception $e) {
//            // Rollback the transaction on failure
//            \DB::rollBack();
//
//            // Reverse the wallet debit if Forex deposit fails
//            $this->reverseWalletPayment($wallet, $totalAmount, $txnInfoSender);
//
//            // Log the error and notify the user
//            Log::error('Forex Deposit Failed', [
//                'message' => $e->getMessage(),
//                'depositData' => $depositData ?? [],
//                'response' => $depositResponse ?? null,
//            ]);
//
//            // Notify the user with the exact error message
//            notify()->error($e->getMessage(), 'Error');
//
//            // Redirect back with error
//            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
//        }
    }
    private function reverseWalletPayment($wallet, $totalAmount, $txnInfoSender)
    {
        // Reverse wallet payment if a failure occurs
        $ledgerBalance = $this->walletService->getLedgerBalance($wallet->id);
        $this->walletService->createCreditLedgerEntry($txnInfoSender, $ledgerBalance);

        // Update the wallet balance
        $wallet->amount = BigDecimal::of($wallet->amount)->plus(BigDecimal::of($totalAmount));
        $wallet->save();

        // Notify user of reversal
        notify()->error(__('Transaction failed. Wallet payment has been reversed.'), 'Error');
    }
    private function sendEmailNotificationExternal($txnInfoSender, $toUser, $receiverAccount)
    {
        $symbol = setting('currency_symbol', 'global');

        $notify = [
            'card-header' => __('Transfer Success'),
            'title' => $symbol . $txnInfoSender->amount . __(' Transferred Successfully'),
            'p' => __('You have successfully transferred ') . $symbol . $txnInfoSender->amount . ' to ' . $toUser->first_name . ' ' . $toUser->last_name . ' (Account: ' . $receiverAccount . ')',
            'strong' => __('Transaction ID: ') . $txnInfoSender->tnx,
            'action' => route('user.send-money.view'),
            'a' => __('Transfer Again'),
            'view_name' => 'send_money',
        ];
        Session::put('user_notify', $notify);

        $shortcodes = [
            '[[sender_name]]' => $txnInfoSender->user->full_name,
            '[[receiver_name]]' => $toUser->full_name,
            '[[txn]]' => $txnInfoSender->tnx,
            '[[account_from]]' => $txnInfoSender->target_id,
            '[[account_to]]' => $receiverAccount,
            '[[amount]]' => $txnInfoSender->amount,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
            '[[status]]' => 'Completed',
        ];

        // Send email notifications
        $this->mailNotify($txnInfoSender->user->email, 'external_transfer_sender', $shortcodes);
    }

    //internal transfer
    public function sendMoneyInternal()
    {
//        $balance = BigDecimal::of(auth()->user()->ib_balance);
//        dd(auth()->user()->ib_login);
//        $clientIp = request()->ip();
//        if(!in_array($clientIp,['127.0.0.1' , '::1'])) {
//            if (auth()->user()->ib_login) {
//                $getUserResponse = $this->getUserApi(auth()->user()->ib_login);
//                if ($getUserResponse->status() == 200 && isset($getUserResponse->object()->Login)) {
//                    $balance = $getUserResponse->object()->Balance;
//                    auth()->user()->update(['ib_balance' => $balance]);
//                    auth()->setUser(auth()->user()->fresh());
//                }
//            }
//            $this->syncForexAccounts(auth()->id());
//        }
        $forexAccounts = ForexAccount::with('schema')->traderType()
            ->where('user_id', auth()->id())
            ->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)
            ->orderBy('id', 'desc')
            ->get();
        if (! setting('is_internal_transfer', 'transfer_internal') or ! \Auth::user()->transfer_status) {
            abort('403', __('Send Money Disable Now'));
        }

        $isStepOne = 'current';
        $isStepTwo = '';

        return view('frontend::send_money.internal-now', compact('isStepOne', 'isStepTwo', 'forexAccounts'));
//        return view('frontend::send_money.internal-now', compact('isStepOne', 'isStepTwo', 'forexAccounts'));
    }

   public function sendMoneyInternalNow(Request $request)
{
    // Check if transfers are enabled
    if (!setting('is_internal_transfer', 'transfer_internal') || !Auth::user()->transfer_status) {
        abort(403, __('Send Money Disabled Now'));
    }
    // Check if KYC is completed
    if (!setting('internal_transfer_amount', 'kyc_permissions') && auth()->user()->kyc < kyc_required_completed_level()) {
        notify()->error('KYC Pending: Please complete your KYC verification to proceed with your internal amount transfer', __('Error'));
        return redirect()->route('user.kyc');
    }

    // Validation for input fields
    $validator = Validator::make($request->all(), [
        'target_id' => ['required', 'different:receiver_account'],
        'receiver_account' => ['required', 'different:target_id'],
        'amount' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'],
    ], [
        'target_id.required' => __('Kindly select the account from which to transfer'),
        'receiver_account.required' => __('Kindly select the receiver account to transfer to')
    ]);

    if ($validator->fails()) {
        notify()->error($validator->errors()->first(), __('Error'));
        return redirect()->back();
    }

    $input = $request->all();
    $amount = (float) $input['amount'];
    $fromUser = Auth::user();
    $targetId = get_hash($input['target_id']);
    $receiverAccount = get_hash($input['receiver_account']);
    $targetType = $input['target_type']; // 'forex' or 'wallet'
    $receiverType = $input['receiver_type']; // 'forex' or 'wallet'
    
    // Min & max range validation
    $min = setting('internal_min_send', 'transfer_internal');
    $max = setting('internal_max_send', 'transfer_internal');
    if ($amount < $min || $amount > $max) {
        $currencySymbol = setting('currency_symbol', 'global');
        $message = __('Please Send the Amount within the range ') . $currencySymbol . $min . __(' to ') . $currencySymbol . $max;
        notify()->error($message, __('Error'));
        return redirect()->back();
    }

    // Check daily send limit for successful transactions only
    $dailyLimit = setting('internal_send_daily_limit', 'transfer_internal');
    $todayTransfers = Transaction::where('user_id', $fromUser->id)
        ->where('type', TxnType::SendMoneyInternal)
        ->where('status', TxnStatus::Success) // Only count successful transactions
        ->whereDate('created_at', today())
        ->count();

    if ($todayTransfers >= $dailyLimit) {
        notify()->error(__('You have reached the daily transfer limit.'), __('Error'));
        return redirect()->back();
    }

    // Start database transaction for atomic operations
    DB::beginTransaction();
    
    try {
        // Validate ownership for sender account and get balance
        $wallet = null;
        $balance = null;
        $forexAccount = null;
        $receiverWallet = null;
        $toUserForexAccount = null;
        $toUser = null;

        if ($targetType == 'forex') {
            $forexAccount = ForexAccount::where('login', $targetId)
                ->where('user_id', $fromUser->id)
                ->where('account_type', 'real')
                ->first();

            if (!$forexAccount) {
                throw new \Exception(__('The selected Forex account does not belong to you.'));
            }

            // Get balance with error handling
            try {
                $scaledAmount = apply_cent_account_adjustment($targetId, $amount);
                $balance = $this->forexApiService->getValidatedBalance(['login' => $targetId]);
                
                // Check if balance retrieval failed (service returns BigDecimal(0) on failure)
                // We need to validate if the account actually has balance or if API failed
                $balanceCheckResponse = $this->forexApiService->getBalance(['login' => $targetId]);
                if (!$balanceCheckResponse['success']) {
                    throw new \Exception(__('Unable to retrieve account balance. Please try again later.'));
                }
            } catch (\Exception $e) {
                Log::error("Forex balance retrieval failed for account {$targetId}: " . $e->getMessage());
                throw new \Exception(__('Unable to retrieve account balance. Please try again later.'));
            }

            if (BigDecimal::of($scaledAmount)->compareTo(BigDecimal::of($balance)) > 0) {
                throw new \Exception(__('Insufficient funds'));
            }
        } elseif ($targetType == 'wallet') {
            $wallet = get_user_account_by_wallet_id($targetId, $fromUser->id);
            if (!$wallet) {
                throw new \Exception(__('The selected Wallet account does not belong to you.'));
            }
            
            if ($wallet->balance === AccountBalanceType::IB_WALLET) {
                $ibMinLimit = setting('min_ib_wallet_withdraw_limit', 'withdraw_settings');
                if ($amount < $ibMinLimit) {
                    throw new \Exception(__('You must transfer at least :limit from IB Wallet.', [
                        'limit' => setting('currency_symbol', 'global') . $ibMinLimit
                    ]));
                }
            }
            $balance = BigDecimal::of($wallet->amount);
        }

        $totalAmount = $amount + $this->calculateCharge($amount);

        // Check if the sender has sufficient funds
        if (BigDecimal::of($totalAmount)->compareTo($balance) > 0) {
            throw new \Exception(__("Insufficient funds"));
        }

        // Validate ownership for receiver account
        if ($receiverType == 'forex') {
            $toUserForexAccount = ForexAccount::where('login', $receiverAccount)
                ->where('user_id', $fromUser->id)
                ->where('account_type', 'real')
                ->first();

            if (!$toUserForexAccount || $toUserForexAccount->user_id == null) {
                throw new \Exception(__('Receiver Forex account is invalid or inactive'));
            }

            // Check if the first minimum deposit is required and not yet paid
            if (isset($toUserForexAccount->schema->first_min_deposit) && $toUserForexAccount->schema->first_min_deposit > 0) {
                if (!$toUserForexAccount->first_min_deposit_paid && $amount < $toUserForexAccount->schema->first_min_deposit) {
                    $currencySymbol = setting('currency_symbol', 'global');
                    $message = __('Please deposit the first minimum amount of ') . $currencySymbol . $toUserForexAccount->schema->first_min_deposit;
                    throw new \Exception($message);
                }
            }

            $toUser = $toUserForexAccount->user;
        } elseif ($receiverType == 'wallet') {
            $receiverWallet = get_user_account_by_wallet_id($receiverAccount, $fromUser->id);
            if (!$receiverWallet || $receiverWallet->user_id == null) {
                throw new \Exception(__('Receiver Wallet account not found'));
            }
            $toUser = $receiverWallet->user;
        }

        // Create transactions with TxnStatus::Pending before managing the ledger
        $sendDescription = __('Transfer Money To ') . $fromUser->username . '-' . $receiverAccount . '(' . $receiverType . ')';
        $txnInfoSender = Txn::new(
            $amount, $this->calculateCharge($amount), $totalAmount, 'system', $sendDescription,
            TxnType::SendMoneyInternal, TxnStatus::Pending, null, null, $fromUser->id, $toUser->id, 'User', [],
            $input['note'], $targetId, $targetType
        );

        $receiveDescription = __('Receive Money From ') . $toUser->username . '-' . $targetId . '(' . $targetType . ')';
        $txnInfoReceiver = Txn::new(
            $amount, 0, $amount, 'system', $receiveDescription, TxnType::ReceiveMoneyInternal, TxnStatus::Pending,
            null, null, $toUser->id, $fromUser->id, 'User', [], $input['note'], $receiverAccount, $receiverType
        );

        // Handle Transfer Scenarios with proper error handling
        if ($targetType == 'forex' && $receiverType == 'forex') {
            // Forex-to-Forex Transfer
            $comment = 'Int/from/' . $targetId . '/to/' . $receiverAccount;
            $this->processForexToForexTransfer($targetId, $receiverAccount, $totalAmount, $txnInfoSender->amount, $comment);

        } elseif ($targetType == 'forex' && $receiverType == 'wallet') {
            // Forex-to-Wallet Transfer
            $this->processForexToWalletTransfer($targetId, $receiverWallet, $totalAmount, $amount, $txnInfoSender, $txnInfoReceiver);

        } elseif ($targetType == 'wallet' && $receiverType == 'forex') {
            // Wallet-to-Forex Transfer
            $this->processWalletToForexTransfer($wallet, $receiverAccount, $totalAmount, $amount, $balance, $txnInfoSender, $txnInfoReceiver);

        } elseif ($targetType == 'wallet' && $receiverType == 'wallet') {
            // Wallet-to-Wallet Transfer
            $this->processWalletToWalletTransfer($wallet, $receiverWallet, $totalAmount, $amount, $balance, $txnInfoSender, $txnInfoReceiver);
        }

        // Update the transactions to TxnStatus::Success after ledger updates
        Txn::update($txnInfoSender->tnx, TxnStatus::Success, $fromUser->id, __('Transfer Successful'));
        Txn::update($txnInfoReceiver->tnx, TxnStatus::Success, $toUser->id, __('Transfer Successful'));

        // Update MT5 balances for Forex accounts (if applicable) with error handling
        try {
            if ($targetType == 'forex') {
                $balanceResponse = $this->forexApiService->getBalance(['login' => $targetId]);
                if ($balanceResponse['success']) {
                    $updatedBalance = $this->forexApiService->getValidatedBalance(['login' => $targetId]);
                    mt5_update_balance($targetId, $updatedBalance);
                } else {
                    Log::warning("Failed to get balance for MT5 update", ['account' => $targetId, 'response' => $balanceResponse]);
                }
            }
            if ($receiverType == 'forex') {
                $balanceResponse = $this->forexApiService->getBalance(['login' => $receiverAccount]);
                if ($balanceResponse['success']) {
                    $updatedBalance = $this->forexApiService->getValidatedBalance(['login' => $receiverAccount]);
                    mt5_update_balance($receiverAccount, $updatedBalance);
                } else {
                    Log::warning("Failed to get balance for MT5 update", ['account' => $receiverAccount, 'response' => $balanceResponse]);
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the transaction as this is non-critical
            Log::warning("Failed to update MT5 balance after transfer: " . $e->getMessage());
        }

        // Commit the database transaction
        DB::commit();

        notify()->success(__('Successfully Send Money'), __('Success'));

        $symbol = setting('currency_symbol', 'global');
        $notify = [
            'card-header' => __('Success Your Send Money Process'),
            'title' => $symbol . $txnInfoSender->amount . __(' Send Money Successfully'),
            'p' => __('The Send Money has been successfully sent to the ') . $toUser->first_name . ' ' . $toUser->last_name . __(' account # ') . $receiverAccount,
            'strong' => __('Transaction ID: ') . $txnInfoSender->tnx,
            'action' => route('user.send-money.internal-view'),
            'a' => __('Send Money again'),
            'view_name' => 'send_money',
        ];
        Session::put('user_notify', $notify);

        // Send email notification with error handling
        try {
            $shortcodes = [
                '[[sender_name]]' => $txnInfoSender->user->full_name,
                '[[receiver_name]]' => $toUser->full_name,
                '[[txn]]' => $txnInfoSender->tnx,
                '[[account_from]]' => $targetId,
                '[[account_to]]' => $receiverAccount,
                '[[amount]]' => $txnInfoSender->amount,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
                '[[status]]' => 'Completed',
            ];
            $this->mailNotify($txnInfoSender->user->email, 'internal_transfer_sender', $shortcodes);
        } catch (\Exception $e) {
            // Log email failure but don't fail the transaction
            Log::warning("Failed to send transfer notification email: " . $e->getMessage());
        }

        return redirect()->route('user.notify');

    } catch (\Exception $e) {
        // Rollback the database transaction
        DB::rollback();
        
        // Log the error with context
        Log::error("Internal transfer failed", [
            'user_id' => $fromUser->id,
            'amount' => $amount,
            'target_id' => $targetId,
            'receiver_account' => $receiverAccount,
            'target_type' => $targetType,
            'receiver_type' => $receiverType,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        // Update transactions to failed status if they exist
        if (isset($txnInfoSender) && $txnInfoSender) {
            try {
                Txn::update($txnInfoSender->tnx, TxnStatus::Failed, $fromUser->id, $e->getMessage());
            } catch (\Exception $updateError) {
                Log::error("Failed to update sender transaction to failed status: " . $updateError->getMessage());
            }
        }
        
        if (isset($txnInfoReceiver) && $txnInfoReceiver) {
            try {
                Txn::update($txnInfoReceiver->tnx, TxnStatus::Failed, $toUser->id ?? $fromUser->id, $e->getMessage());
            } catch (\Exception $updateError) {
                Log::error("Failed to update receiver transaction to failed status: " . $updateError->getMessage());
            }
        }

        // Show user-friendly error message
        $errorMessage = $e->getMessage();
        if (strpos($errorMessage, 'API') !== false || strpos($errorMessage, 'balance') !== false) {
            $errorMessage = __('Transfer service is temporarily unavailable. Please try again later.');
        }
        
        notify()->error($errorMessage, __('Error'));
        return redirect()->back();
    }
}
    private function calculateCharge($amount)
    {
        $chargeType = setting('internal_send_charge_type', 'transfer_internal');
        $charge = setting('internal_send_charge', 'transfer_internal');
        return $chargeType === 'percentage' ? ($amount * ($charge / 100)) : $charge;
    }

    public function sendMoneyLog(Request $request)
    {

        $sendMoneys = Transaction::search(request('query'), function ($query) {
            $query->where('user_id', auth()->user()->id)
                ->whereIn('type', [TxnType::SendMoney,TxnType::SendMoneyInternal,TxnType::ReceiveMoneyInternal])
                ->when(request('date'), function ($query) {
                    $query->whereDay('created_at', '=', Carbon::parse(request('date'))->format('d'));
                });
        })->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('frontend::send_money.log', compact('sendMoneys'));
    }

    public function export(Request $request)
    {
        return Excel::download(new TransferHistoryExport($request), 'Transfer-History.xlsx');
    }
}
