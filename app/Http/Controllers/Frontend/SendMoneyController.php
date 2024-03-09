<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ForexAccountStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\ForexAccount;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\ForexApiTrait;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use Txn;
use Validator;

class SendMoneyController extends Controller
{
    use ForexApiTrait;
    public function sendMoney(Request $request)
    {
        $clientIp = request()->ip();
        if(!in_array($clientIp,['127.0.0.1' , '::1'])) {
            if (auth()->user()->ib_login) {
                $getUserResponse = $this->getUserApi(auth()->user()->ib_login);
                if ($getUserResponse->status() == 200 && isset($getUserResponse->object()->Login)) {
                    $balance = $getUserResponse->object()->Balance;
                    auth()->user()->update(['ib_balance' => $balance]);
                    auth()->setUser(auth()->user()->fresh());
                }
            }
            $this->syncForexAccounts(auth()->id());
        }
        $forexAccounts = ForexAccount::with('schema')
            ->where('user_id', auth()->id())
            ->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)
            ->orderBy('id', 'desc')
            ->get();
        if (! setting('transfer_status', 'permission') or ! \Auth::user()->transfer_status) {
            abort('403', 'Send Money Disable Now');
        }

        $isStepOne = 'current';
        $isStepTwo = '';

        return view('frontend::send_money.now', compact('isStepOne', 'isStepTwo', 'forexAccounts'));
    }
    public function sendMoneyNow(Request $request)
    {
        notify()->error(__('Send Money Disable Now'), 'Error');
        return redirect()->back();
        if (! setting('transfer_status', 'permission') || ! \Auth::user()->transfer_status) {
            abort('403', 'Send Money Disable Now');
        }

        $validator = Validator::make($request->all(), [
            'target_id' => ['required', 'different:receiver_account'],
            'receiver_account' => ['required', 'different:target_id'],
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'],
        ],[
            'target_id.required' => __('Kindly select the sender account to transfer'),
            'receiver_account.required' => __('Kindly select the receiver account to transfer')
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
        $input = $request->all();
        $targetId = $input['target_id'];
        $fromForexAccount = ForexAccount::where('login', $targetId)->first();
        if (!$fromForexAccount->schema->is_external_transfer) {
            notify()->error(__("You haven't allowed external transfer of :plan accounts. Kindly choose different account",['plan'=>$fromForexAccount->schema->title]), 'Error');
            return redirect()->back();
        }
        if (!$fromForexAccount->schema->is_internal_transfer) {
            notify()->error(__("You haven't allowed internal transfer of :plan accounts. Kindly choose different account",['plan'=>$fromForexAccount->schema->title]), 'Error');
            return redirect()->back();
        }
        //daily limit
        $todayTransaction = Transaction::where('type', TxnType::SendMoney)->whereDate('created_at', Carbon::today())->count();
        $dayLimit = (float) Setting('send_money_day_limit', 'fee');
        if ($todayTransaction >= $dayLimit) {
            notify()->error(__('Today Send Money limit has been reached'), 'Error');

            return redirect()->back();
        }

//dd($input);
        $fromUser = \Auth::user();
        $receiverAccount = $input['receiver_account'];
        $toUserForexAccount = ForexAccount::where('login', $receiverAccount)->first();

//        dd($toUserForexAccount);
        if(!$toUserForexAccount){
            notify()->error(__('Please add a valid receiver Account!. or May be your receiver account is not active'), 'Error');
            return redirect()->back();
        }
        $toUser = $toUserForexAccount->user;

        if (! $toUser) {
            notify()->error(__('Receiver User Not Found'), 'Error');
            return redirect()->back();
        }
        $this->isValidForexAccount($receiverAccount);

        $amount = (float) $input['amount'];
        $min = setting('min_send', 'fee');
        $max = setting('max_send', 'fee');
        if ($amount < $min || $amount > $max) {
            $currencySymbol = setting('currency_symbol', 'global');
            $message = 'Please Send the Amount within the range '.$currencySymbol.$min.' to '.$currencySymbol.$max;
            notify()->error($message, 'Error');

            return redirect()->back();
        }

        $chargeType = Setting('send_charge_type', 'fee');

        $charge = (float) Setting('send_charge', 'fee');

        if ($chargeType == 'percentage') {
            $charge = $amount * ($charge / 100);
        }

        $totalAmount = $amount + $charge;

        $balance = $this->getForexAccountBalance($targetId);
//        dd($balance);
        if (BigDecimal::of($totalAmount)->compareTo($balance) > 0) {
            notify()->error(__("Sorry, you don't have sufficient funds in your account to complete this action. Please add funds to proceed."), 'Error');
            return redirect()->back();
        }

        //withdraw balance
        $targetType = 'forex_withdraw';
        $comment = 'ext/transfer/to/'.$receiverAccount;
        $withdrawResponse = $this->forexWithdraw($targetId, $totalAmount,$comment);
        if(!$withdrawResponse){
            return redirect()->back();
        }

        $sendDescription = 'Transfer Money To '.$toUser->username.'-'.$receiverAccount;
        $txnInfoSender = Txn::new($amount, $charge, $totalAmount, 'system', $sendDescription,
            TxnType::SendMoney, TxnStatus::Success, null, null, $fromUser->id, $toUser->id,'User', [], $input['note'], $targetId, $targetType);

//        $toUser->increment('balance', $amount);
        $comment =  "ext/transfer/from/".$targetId;
        $this->ForexDeposit($receiverAccount,$amount,$comment);
        $receiveDescription = 'Transfer Money Form '.$fromUser->username.'-'.$targetId;
        $txnInfo = Txn::new($amount, $charge, $totalAmount, 'system', $receiveDescription,
            TxnType::ReceiveMoney, TxnStatus::Success, null, null, $toUser->id, $fromUser->id,  'User', [], $input['note'], $receiverAccount, 'forex_deposit');

        notify()->success('Successfully Send Money', 'success');

        $symbol = setting('currency_symbol', 'global');

        $notify = [
            'card-header' => 'Success Your Send Money Process',
            'title' => $symbol.$txnInfo->amount.' Send Money Successfully',
            'p' => 'The Send Money has been successfully sent to the '.$toUser->first_name.' '.$toUser->last_name.' account # '.$receiverAccount,
            'strong' => 'Transaction ID: '.$txnInfo->tnx,
            'action' => route('user.send-money.view'),
            'a' => 'Send Money again',
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
        $this->mailNotify($txnInfo->user->email, 'external_transfer_sender', $shortcodes);

        return redirect()->route('user.notify');

    }
    public function sendMoneyInternal()
    {
        $balance = BigDecimal::of(auth()->user()->ib_balance);
//        dd(auth()->user()->ib_login);
        $clientIp = request()->ip();
        if(!in_array($clientIp,['127.0.0.1' , '::1'])) {
            if (auth()->user()->ib_login) {
                $getUserResponse = $this->getUserApi(auth()->user()->ib_login);
                if ($getUserResponse->status() == 200 && isset($getUserResponse->object()->Login)) {
                    $balance = $getUserResponse->object()->Balance;
                    auth()->user()->update(['ib_balance' => $balance]);
                    auth()->setUser(auth()->user()->fresh());
                }
            }
            $this->syncForexAccounts(auth()->id());
        }

        $forexAccounts = ForexAccount::with('schema')
            ->where('user_id', auth()->id())
            ->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)
            ->orderBy('id', 'desc')
            ->get();
        if (! setting('transfer_status', 'permission') or ! \Auth::user()->transfer_status) {
            abort('403', 'Send Money Disable Now');
        }

        $isStepOne = 'current';
        $isStepTwo = '';

        return view('frontend.default.send_money.internal-now', compact('isStepOne', 'isStepTwo', 'forexAccounts'));
//        return view('frontend::send_money.internal-now', compact('isStepOne', 'isStepTwo', 'forexAccounts'));
    }
    public function sendMoneyInternalNow(Request $request)
    {
//        notify()->error(__('Send Money Disable Now'), 'Error');
//        return redirect()->back();
//        dd($targetId,$targetType);
        if (! setting('transfer_status', 'permission') || ! \Auth::user()->transfer_status) {
            abort('403', 'Send Money Disable Now');
        }

        $validator = Validator::make($request->all(), [
            'target_id' => ['required', 'different:receiver_account'],
            'receiver_account' => ['required', 'different:target_id'],
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'],
        ],[
            'target_id.required' => __('Kindly select the account from to transfer'),
            'receiver_account.required' => __('Kindly select the receiver account to transfer')
        ]);
        $targetType = $request->input('target_type');

//        dd($request->all());
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
        //daily limit
//        $todayTransaction = Transaction::where('type', TxnType::SendMoney)->whereDate('created_at', Carbon::today())->count();
//        $dayLimit = (float) Setting('send_money_day_limit', 'fee');
//        if ($todayTransaction >= $dayLimit) {
//            notify()->error(__('Today Send Money limit has been reached'), 'Error');
//            return redirect()->back();
//        }
        $input = $request->all();
        $amount = (float) $input['amount'];
        $targetId = $input['target_id'];
//dd($input);
        $fromUser = \Auth::user();
        $receiverAccount = $input['receiver_account'];
        $toUserForexAccount = ForexAccount::where('login', $receiverAccount)->first();
//        dd($toUserForexAccount);
        if(!$toUserForexAccount){
            notify()->error(__('Please add a valid receiver Account!. or May be your receiver account is not active'), 'Error');
            return redirect()->back();
        }
        $toUser = $toUserForexAccount->user;

        if (! $toUser) {
            notify()->error(__('Receiver User Not Found'), 'Error');
            return redirect()->back();
        }
        $this->isValidForexAccount($receiverAccount);

        $min = setting('internal_min_send', 'fee');
        $max = setting('internal_max_send', 'fee');
        if ($amount < $min || $amount > $max) {
            $currencySymbol = setting('currency_symbol', 'global');
            $message = 'Please Send the Amount within the range '.$currencySymbol.$min.' to '.$currencySymbol.$max;
            notify()->error($message, 'Error');

            return redirect()->back();
        }

        $chargeType = Setting('internal_send_charge_type', 'fee');

        $charge = (float) Setting('internal_send_charge', 'fee');

        if ($chargeType == 'percentage') {
            $charge = $amount * ($charge / 100);
        }
        $user = auth()->user();
        $totalAmount = $amount + $charge;
        if($targetType == 'forex') {
            $balance = $this->getForexAccountBalance($targetId);
        }elseif($targetType == 'wallet'){
            $balance = BigDecimal::of($user->profit_balance);
        }
//        dd($balance);
        if (BigDecimal::of($totalAmount)->compareTo($balance) > 0) {
            notify()->error(__("Sorry, you don't have sufficient funds in your account to complete this action. Please add funds to proceed."), 'Error');
            return redirect()->back();
        }
        if($targetType == 'forex') {
            //withdraw balance
            $targetType = 'forex_withdraw';
            $comment = 'int/transfer/to/' . $receiverAccount;
            $withdrawResponse = $this->forexWithdraw($targetId, $totalAmount, $comment);
            if(!$withdrawResponse){
                return redirect()->back();
            }
        }elseif($targetType == 'wallet'){
            $targetType = 'withdraw';
            $user->decrement('profit_balance', $totalAmount);
        }

        $sendDescription = 'Transfer Money To '.$toUser->username.'-'.$receiverAccount;
        $txnInfoSender = Txn::new($amount, $charge, $totalAmount, 'system', $sendDescription, TxnType::SendMoneyInternal, TxnStatus::Success, null, null, $fromUser->id, $toUser->id,'User', [], $input['note'], $targetId, $targetType);

//        $toUser->increment('balance', $amount);
        $comment =  "int/transfer/from/".$targetId;
        $this->ForexDeposit($receiverAccount,$amount,$comment);
        $receiveDescription = 'Transfer Money From '.$fromUser->username.'-'.$targetId;
        $txnInfo = Txn::new($amount, $charge, $totalAmount, 'system', $receiveDescription, TxnType::ReceiveMoney, TxnStatus::Success, null, null, $toUser->id, $fromUser->id, 'User', [], $input['note'], $receiverAccount, 'forex_deposit');

        notify()->success('Successfully Send Money', 'success');

        $symbol = setting('currency_symbol', 'global');

        $notify = [
            'card-header' => 'Success Your Send Money Process',
            'title' => $symbol.$txnInfo->amount.' Send Money Successfully',
            'p' => 'The Send Money has been successfully sent to the '.$toUser->first_name.' '.$toUser->last_name.' account # '.$receiverAccount,
            'strong' => 'Transaction ID: '.$txnInfo->tnx,
            'action' => route('user.send-money.internal-view'),
            'a' => 'Send Money again',
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
        $this->mailNotify($txnInfo->user->email, 'internal_transfer_sender', $shortcodes);
        return redirect()->route('user.notify');

    }

    public function sendMoneyLog(Request $request)
    {

        $sendMoneys = Transaction::search(request('query'), function ($query) {
            $query->where('user_id', auth()->user()->id)
                ->where('type', TxnType::SendMoney)
                ->when(request('date'), function ($query) {
                    $query->whereDay('created_at', '=', Carbon::parse(request('date'))->format('d'));
                });
        })->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('frontend::send_money.log', compact('sendMoneys'));
    }
}
