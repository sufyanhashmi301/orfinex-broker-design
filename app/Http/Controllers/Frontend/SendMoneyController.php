<?php

namespace App\Http\Controllers\Frontend;

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
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransferHistoryExport;

use Session;
use Txn;
use Validator;

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
        if (! setting('transfer_status', 'permission') or ! \Auth::user()->transfer_status) {
            abort('403', __('Send Money Disable Now'));
        }

        $isStepOne = 'current';
        $isStepTwo = '';

        return view('frontend::send_money.now', compact('isStepOne', 'isStepTwo', 'forexAccounts'));
    }
    public function sendMoneyNow(Request $request)
    {
        // Check if the user has the permission to transfer
        if (!setting('transfer_status', 'permission') || !\Auth::user()->transfer_status) {
            abort(403, __('Send Money Disabled Now'));
        }
        if(auth()->user()->kyc < kyc_required_completed_level()) {
            notify()->error('KYC Pending: Please complete your KYC verification to proceed with your withdrawal', __('Error'));
            return redirect()->back();
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

            // Validate sender's account based on target type
            if ($targetType === 'forex') {
                $fromForexAccount = ForexAccount::where('login', $targetId)->where('user_id', \Auth::id())->first();
                if (!$fromForexAccount) {
                    throw new \Exception(__('The selected Forex account does not belong to you.'));
                }
                $balance = $this->forexApiService->getValidatedBalance(['login' => $targetId]);
//                dd($balance);
            } elseif ($targetType === 'wallet') {
                $wallet = get_user_account_by_wallet_id($targetId, \Auth::id());
                if (!$wallet) {
                    throw new \Exception(__('The selected Wallet account does not belong to you.'));
                }
                $balance = BigDecimal::of($wallet->amount);
            }

            // Validate amount range
            $min = setting('min_send', 'fee');
            $max = setting('max_send', 'fee');
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
            $toUser = $toUserForexAccount->user;

            // Create transaction entries with TxnStatus::None
            $sendDescription =__('Transfer Money to ') . $toUser->username . '-' . $receiverAccount;
            $txnInfoSender = Txn::new($amount, $charge, $totalAmount, 'system', $sendDescription, TxnType::SendMoney, TxnStatus::None, null, null, \Auth::id(), $toUser->id, 'User', [], $input['note'], $targetId, $targetType);

            $receiveDescription = __('Transfer Money from ') . \Auth::user()->username . '-' . $targetId;
            $txnInfoReceiver = Txn::new($amount, 0, $amount, 'system', $receiveDescription, TxnType::ReceiveMoney, TxnStatus::None, null, null, $toUser->id, \Auth::id(), 'User', [], $input['note'], $receiverAccount, 'forex');

            // Handle Forex-to-Forex or Wallet-to-Forex transfers
            if ($targetType == 'forex') {
                $this->forexToForexTransfer($targetId, $receiverAccount, $totalAmount, $txnInfoSender);
            } elseif ($targetType == 'wallet') {
                $this->walletToForexTransfer($wallet, $targetId, $receiverAccount, $totalAmount, $txnInfoSender, $txnInfoReceiver);
            }

            // Update transaction status to success
            Txn::update($txnInfoSender->tnx, TxnStatus::Success, \Auth::id(),  __('Transfer Successful'));
            Txn::update($txnInfoReceiver->tnx, TxnStatus::Success, $toUser->id,  __('Transfer Successful'));

            // Update MT5 balances for Forex accounts
            if ($targetType == 'forex') {
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

            // If there's a failure, reverse the payment if applicable
//            if (isset($wallet)) {
//                $this->reverseWalletPayment($wallet, $totalAmount, $txnInfoSender);
//            }

            // Log the error and notify the user
            Log::error(__('Transaction failed: ') . $e->getMessage());
            //            dd($e->getMessage());
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
        $data = [
            'FromLogin' => $targetId,
            'ToLogin' => $receiverAccount,
            'Amount' => $totalAmount,
            'Comments' => $comment
        ];
        $this->forexApiService->transferFunds($data);
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
                'Amount' => $totalAmount - $txnInfoSender->charge,
                'type' => 1, // Deposit into Forex
                'TransactionComments' => $comment
            ];
//            dd($depositData);

            // Perform the Forex deposit
            $depositResponse = $this->forexApiService->balanceOperation($depositData);
//dd($depositResponse);
            // Check if the Forex deposit operation was successful
            if (!$depositResponse['success'] || $depositResponse['result']['responseCode'] != 10009) {
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
        if (! setting('transfer_status', 'permission') or ! \Auth::user()->transfer_status) {
            abort('403', __('Send Money Disable Now'));
        }

        $isStepOne = 'current';
        $isStepTwo = '';

        return view('frontend::send_money.internal-now', compact('isStepOne', 'isStepTwo', 'forexAccounts'));
//        return view('frontend::send_money.internal-now', compact('isStepOne', 'isStepTwo', 'forexAccounts'));
    }

    public function sendMoneyInternalNow(Request $request)
    {
        if (!setting('transfer_status', 'permission') || !\Auth::user()->transfer_status) {
            abort(403, __('Send Money Disabled Now'));
        }
        if(auth()->user()->kyc < kyc_required_completed_level()) {
            notify()->error('KYC Pending: Please complete your KYC verification to proceed with your withdrawal', __('Error'));
            return redirect()->back();
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
        $fromUser = \Auth::user();
        $targetId = get_hash($input['target_id']);
        $receiverAccount = get_hash($input['receiver_account']);
        $targetType = $input['target_type']; // 'forex' or 'wallet'
        $receiverType = $input['receiver_type']; // 'forex' or 'wallet'

        //min & max range validation
        $min = setting('internal_min_send', 'fee');
        $max = setting('internal_max_send', 'fee');
        if ($amount < $min || $amount > $max) {
            $currencySymbol = setting('currency_symbol', 'global');
            $message = __('Please Send the Amount within the range ').$currencySymbol.$min.__(' to ').$currencySymbol.$max;
            notify()->error($message, __('Error'));

            return redirect()->back();
        }
        // ** Validate ownership for sender account **
        if ($targetType == 'forex') {
            $forexAccount = ForexAccount::where('login', $targetId)
                ->where('user_id', $fromUser->id)
                ->where('account_type', 'real')
                ->first();
            if (!$forexAccount) {
                notify()->error(__('The selected Forex account does not belong to you.'), __('Error'));
                return redirect()->back();
            }
            $balance = $this->forexApiService->getValidatedBalance(['login' => $targetId]);
//            dd($balance);
        } elseif ($targetType == 'wallet') {
            $wallet = get_user_account_by_wallet_id($targetId, $fromUser->id);
            if (!$wallet) {
                notify()->error(__('The selected Wallet account does not belong to you.'), __('Error'));
                return redirect()->back();
            }
            $balance = BigDecimal::of($wallet->amount);
        }

        $totalAmount = $amount + $this->calculateCharge($amount);

        // ** Check if the sender has sufficient funds **
        if (BigDecimal::of($totalAmount)->compareTo($balance) > 0) {
            notify()->error(__("Insufficient funds"), __('Error'));
            return redirect()->back();
        }

        // ** Validate ownership for receiver account **
        if ($receiverType == 'forex') {
            $toUserForexAccount = ForexAccount::where('login', $receiverAccount)
                ->where('user_id', $fromUser->id)
                ->where('account_type', 'real')->first();
            if (!$toUserForexAccount || $toUserForexAccount->user_id == null) {
                notify()->error(__('Receiver Forex account is invalid or inactive'), __('Error'));
                return redirect()->back();
            }
            $toUser = $toUserForexAccount->user;
        } elseif ($receiverType == 'wallet') {
            $receiverWallet = get_user_account_by_wallet_id($receiverAccount, $fromUser->id); // No need for user check here
            if (!$receiverWallet || $receiverWallet->user_id == null) {
                notify()->error(__('Receiver Wallet account not found'), __('Error'));
                return redirect()->back();
            }
            $toUser = $receiverWallet->user;
        }

        // ** Create transactions with TxnStatus::None before managing the ledger **
        // sender account/From Account
        $sendDescription = __('Transfer Money To ') . $toUser->username . '-' . $targetId . '(' . $targetType . ')';
        $txnInfoSender = Txn::new(
            $amount, $this->calculateCharge($amount), $totalAmount, 'system', $sendDescription,
            TxnType::SendMoneyInternal, TxnStatus::None, null, null, $fromUser->id, $toUser->id, 'User', [],
            $input['note'], $targetId, $targetType
        );
        // Receiver account/To Account
        $receiveDescription = __('Transfer Money From ') . $fromUser->username . '-' . $receiverAccount . '(' . $receiverType . ')';
        $txnInfoReceiver = Txn::new(
            $amount, 0, $amount, 'system', $receiveDescription, TxnType::ReceiveMoneyInternal, TxnStatus::None,
            null, null, $toUser->id, $fromUser->id, 'User', [], $input['note'], $receiverAccount, $receiverType
        );

        // ** Handle Transfer Scenarios **
        if ($targetType == 'forex' && $receiverType == 'forex') {
            // Forex-to-Forex Transfer
            $comment = 'Int/from/' . $targetId . '/to/' . $receiverAccount;
            $data = [
                'FromLogin' => $targetId,
                'ToLogin' => $receiverAccount,
                'Amount' => $totalAmount,
                'Comments' => $comment
            ];
            $this->forexApiService->transferFunds($data);

        } elseif ($targetType == 'forex' && $receiverType == 'wallet') {
            // Forex-to-Wallet Transfer
            $comment = 'Int/F/to/W/' . substr($txnInfoSender->tnx, -7) ;

            $withdrawData = [
                'login' => $targetId,
                'Amount' => $totalAmount,
                'type' => 2, // Withdraw from Forex
                'TransactionComments' => $comment
            ];

            $withdrawResponse = $this->forexApiService->balanceOperation($withdrawData);
            if ($withdrawResponse['success']) {
                $receiverLedgerBalance = $this->walletService->getLedgerBalance($receiverWallet->id);
                $this->walletService->createCreditLedgerEntry($txnInfoReceiver, $receiverLedgerBalance);
                $receiverWallet->amount = BigDecimal::of($receiverWallet->amount)->plus(BigDecimal::of($amount));
                $receiverWallet->save();
            } else {
                notify()->error(__('Forex to Wallet transfer failed'), __('Error'));
                return redirect()->back();
            }

        } elseif ($targetType == 'wallet' && $receiverType == 'forex') {
            // Wallet-to-Forex Transfer
            $ledgerBalance = $this->walletService->getLedgerBalance($wallet->id);
            $this->walletService->createDebitLedgerEntry($txnInfoSender, $ledgerBalance);
            $wallet->amount = $balance->minus(BigDecimal::of($totalAmount));
            $wallet->save();

            // Deposit to Forex
            $comment = 'Int/W/to/F/' . substr($txnInfoReceiver->tnx, -7) ;
            $depositData = [
                'login' => $receiverAccount,
                'Amount' => $amount,
                'type' => 1, // Deposit into Forex
                'TransactionComments' => $comment
            ];
            $this->forexApiService->balanceOperation($depositData);

        } elseif ($targetType == 'wallet' && $receiverType == 'wallet') {
            // Wallet-to-Wallet Transfer
            $ledgerBalance = $this->walletService->getLedgerBalance($wallet->id);
            $this->walletService->createDebitLedgerEntry($txnInfoSender, $ledgerBalance);
            $wallet->amount = $balance->minus(BigDecimal::of($totalAmount));
            $wallet->save();

            // Credit to the receiving wallet
            $receiverLedgerBalance = $this->walletService->getLedgerBalance($receiverWallet->id);
            $this->walletService->createCreditLedgerEntry($txnInfoReceiver, $receiverLedgerBalance);
            $receiverWallet->amount = BigDecimal::of($receiverWallet->amount)->plus(BigDecimal::of($amount));
            $receiverWallet->save();
        }

        // ** Update the transactions to TxnStatus::Success after ledger updates **
        Txn::update($txnInfoSender->tnx, TxnStatus::Success, $fromUser->id,  __('Transfer Successful'));
        Txn::update($txnInfoReceiver->tnx, TxnStatus::Success, $toUser->id,  __('Transfer Successful'));

        // Update MT5 balances for Forex accounts (if applicable)
        if ($targetType == 'forex') {
            mt5_update_balance($targetId, $this->forexApiService->getValidatedBalance(['login' => $targetId]));
        }
        if ($receiverType == 'forex') {
            mt5_update_balance($receiverAccount, $this->forexApiService->getValidatedBalance(['login' => $receiverAccount]));
        }

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
        $shortcodes = [
            '[[sender_name]]' => $txnInfoSender->user->full_name,
            '[[receiver_name]]' => $toUser->full_name,
            '[[txn]]' => $txnInfoSender->tnx,
            '[[account_from]]' => $targetId,
            '[[account_to]]' => $receiverAccount,
            '[[amount]]' => $txnInfoSender->amount,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
            '[[status]]' =>  'Completed',
        ];
        $this->mailNotify($txnInfoSender->user->email, 'internal_transfer_sender', $shortcodes);
        return redirect()->route('user.notify');

    }

    private function calculateCharge($amount)
    {
        $chargeType = setting('internal_send_charge_type', 'fee');
        $charge = setting('internal_send_charge', 'fee');
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
