<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\AccountBalanceType;
use App\Enums\ForexTradingAccountTypesStatus;
use App\Enums\ForexTradingStatus;
use App\Enums\IBStatus;
use App\Enums\TransactionCalcType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\UserState;
use App\Events\IBTransferEvent;
use App\Http\Controllers\Controller;
use App\Models\ForexTrading;
use App\Models\IbQuestion;
use App\Models\IbQuestionAnswer;
use App\Models\Referral;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ProfileService;
use App\Services\Transaction\TransactionService;
use App\Traits\ForexApi;
use App\Traits\ForexApiTrait;
use App\Traits\ProfileTrait;
use Brick\Math\BigDecimal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class IBController extends Controller
{
    use ForexApiTrait,ProfileTrait;
    private $profileService;


    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.ib-program.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.ib-program.form');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//        dd($request->all());
        $this->validate($request, $this->getValidationRules());

        $formData = $request->input('fields');
        $userIbProgram = IbQuestionAnswer::updateOrCreate([
            'user_id' => auth()->id(), // Assuming you are storing the user_id
            'fields' => json_encode($formData),
        ]);
        if(auth()->user()->ib_status != \App\Enums\IBStatus::APPROVED) {
            auth()->user()->update(['ib_status' => IBStatus::PENDING]);
        }
        return response()->json(['reload' => true,'modal' => true, 'success' => __("IB request has successfully created. Admin will review your request")]);

    }

    // Your processing logic goes here
    private function getValidationRules()
    {
        $rules = [];
        $ibQuestions = IbQuestion::where('status', true)->get();
        // Assuming $ibQuestions is your JSON data
        foreach ($ibQuestions as $ibQuestion) {
            foreach (json_decode($ibQuestion->fields) as $field) {
                if ($field->validation === 'required') {
                    // Add a rule for the current field if validation is required
                    $rules["fields.{$field->name}"] = 'required';
                }
            }
        }

        // Add any additional rules as needed

        return $rules;
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ibTransferBalance(Request $request)
    {
//        dd($request->all());
        $user_id = auth()->user()->id;
//        if ($request->get('account_from') == AccountBalanceType::MAIN) {
//            $sourceFrom = AccountBalanceType::MAIN;
//            $account = get_user_account($user_id);
//            $accountFromID = $account->id;
//            $accountFromName =  w2n($sourceFrom);
//            $balanceFrom = $this->getAccountBalance($sourceFrom, true);
//        } else {
        $sourceFrom = auth()->user()->ib_login;
        if (!$sourceFrom) {
            throw ValidationException::withMessages(['invalid' => __("You have not assigned any IB Login!.Kindly contact with support team!")]);
        }
        $accountFromID = $sourceFrom;
        $accountFromName = 'IB Account';

        $balanceFrom = $this->getForexAccountBalance($sourceFrom);

        $request->merge(['account_from'=>$sourceFrom]);
//        }
//        dd($sourceFrom,$balanceFrom,$request->all());
        $min = 0;
        if($request->account_to == '- Select -'){
            $request->merge(['account_to'=>'']);
        }
//        $balance = user_balance(AccType('main'));
        $balanceFrom = (BigDecimal::of($balanceFrom)->compareTo($min) != 1) ? false : $balanceFrom;
        $this->validate($request, [
            'account_to' => ['required'],
            'amount' => ['required', 'numeric', 'gt:0', 'lte:' . $balanceFrom . ''],
        ], [
            'account_to.required' => __('Choose a valid account to transfer funds.'),
//            'code.exists' => __('Enter a valid account to transfer funds.'),
            'amount.required' => __('Enter a valid amount to transfer funds.'),
            'amount.numeric' => __('Enter a valid amount to transfer funds.'),
        ]);

        if ($request->get('account_from') == $request->get('account_to')) {
            throw ValidationException::withMessages(['invalid' => __("Kindly choose different account to transfer.")]);
        }


        $min = 0;
        $amount = $request->get('amount');
        if (BigDecimal::of($balanceFrom)->compareTo($min) != 1) {
            throw ValidationException::withMessages(['invalid' => __("You do not have enough funds in your account to transfer.")]);
        }
        if (BigDecimal::of($amount)->compareTo($balanceFrom) > 0) {
            throw ValidationException::withMessages(['amount' => ['title' => __('Insufficient balance!'), 'message' => __('The amount exceeds your available funds.')]]);
        }

        if (BigDecimal::of($amount)->compareTo($min) != 1) {
            throw ValidationException::withMessages(['invalid' => __("The amount is required to transfer.")]);
        }

        //Transfer To
        if ($request->get('account_to') == AccountBalanceType::MAIN) {
            $sourceTo = AccountBalanceType::MAIN;
            $account = get_user_account($user_id);
            $accountToID = $account->id;
            $accountToName = w2n($sourceTo);
        } else {
            $forexTrading = ForexTrading::realActiveAccount()->where('id', $request->get('account_to'))->first();
            if (!$forexTrading) {
                throw ValidationException::withMessages(['invalid' => __("Please select valid To Trading Account!.May be your account is not active")]);
            }
            $sourceTo = $forexTrading->login;
            $accountToID = $sourceTo;
            $accountToName = $sourceTo;
            $this->isValidForexAccount($sourceTo);
        }
//dd('s');
        //***************** Validation complete *******************

        $this->ibTransferProcess($request, $user_id, $amount, $sourceFrom, $sourceTo);

        //debit entry of user
        $tnxData = [
            'user_id' => $user_id,
            'base_amount' => $amount,
            'amount' => $amount,
            'calc' => TransactionCalcType::DEBIT,
            'type' => TransactionType::IB_TRANSFER,
            'base_currency' => base_currency(),
            'currency' => base_currency(),
            'base_fees' => 0,
            'amount_fees' => 0,
            'exchange_rate' => 1,
            'method' => 'system',
            'desc' => 'Transferred to ' . $accountToName . '',
            'pay_to' => $sourceTo,
            'pay_from' => $sourceFrom,
        ];

        $this->wrapInTransaction(function ($tnxData, $user_id, $sourceFrom, $accountFromID) {
            $transactionService = new TransactionService();
            $transaction = $transactionService->createForexTradingManualTransaction($tnxData, $accountFromID);

//                ProcessEmail::dispatch('inner-transfer-debited', data_get($transaction, 'customer'), null, $transaction);
            $completedBy = (!empty(system_admin())) ? ['id' => system_admin()->id, 'name' => system_admin()->name] : [];
            if ($sourceFrom == AccountBalanceType::MAIN) {
                $transactionService->confirmInternalTransferTransactionWithSource($transaction, $completedBy, $sourceFrom);
            } else {
                $transaction->status = TransactionStatus::COMPLETED;
                $transaction->completed_at = \Illuminate\Support\Carbon::now();
                $transaction->completed_by = $completedBy;
                $transaction->save();
                $transaction->fresh();
            }
//            event(new IBTransferEvent($transaction));

        }, $tnxData, $user_id, $sourceFrom, $accountFromID);

        //credit entry of transferred user
//            $transferUserId = $transferUser->id;
//            $tnxData['user_id'] = $transferUserId;
        $tnxData['calc'] = TransactionCalcType::CREDIT;
        $tnxData['desc'] = 'Transferred from account ' . $accountFromName . '';

        $this->wrapInTransaction(function ($tnxData, $user_id, $sourceTo, $accountToID) {
            $transactionService = new TransactionService();
            $transaction = $transactionService->createForexTradingManualTransaction($tnxData, $accountToID);
//                ProcessEmail::dispatch('inner-transfer-debited', data_get($transaction, 'customer'), null, $transaction);
            $completedBy = (!empty(system_admin())) ? ['id' => system_admin()->id, 'name' => system_admin()->name] : [];
            if ($sourceTo == AccountBalanceType::MAIN) {
                $transactionService->confirmInternalTransferTransactionWithSource($transaction, $completedBy, $sourceTo);
            } else {
                $transaction->status = TransactionStatus::COMPLETED;
                $transaction->completed_at = \Illuminate\Support\Carbon::now();
                $transaction->completed_by = $completedBy;
                $transaction->save();
                $transaction->fresh();
            }

        }, $tnxData, $user_id, $sourceTo, $accountToID);
        return response()->json(['reload' => true, 'success' => __("Successfully transferred.")]);

//            return redirect()->back()->with(['success' => __('Successfully transferred')]);
//        }
//        else {
//            throw ValidationException::withMessages(['amount' => __('Opps! We unable to process your request. Please reload the page and try again.')]);
//        }

    }
    public function ibTransferProcess(Request $request, $user_id, $amount, $sourceFrom, $sourceTo)
    {

        if ($request->get('account_from') == AccountBalanceType::MAIN) {
            $account = get_user_account($user_id, $sourceFrom);
            $account->amount = to_minus($account->amount, $amount);
            $account->save();
        } else {
            $comment = "IB-Tr/To/,'#".$sourceTo."'/".get_ref_code(auth()->user()->id);
            $this->forexWithdraw($sourceFrom, $amount, $comment);
        }

        if ($request->get('account_to') == AccountBalanceType::MAIN) {

            $account = get_user_account($user_id, $sourceTo);
            $account->amount = to_sum($account->amount, $amount);
            $account->save();
        } else {
            $comment = "IB-Tr/From/".$sourceFrom."/".get_ref_code(auth()->user()->id);
            $fx = ForexTrading::where('login',$sourceTo)->first();
            if($fx->currency == 'USC'){
                $scale = (is_crypto($fx->currency)) ? dp_calc('crypto') : dp_calc('fiat');
//                $amount = BigDecimal::of($amount)->dividedBy(100, $scale, RoundingMode::HALF_DOWN);
                $amount =$amount*100;
            }
            $this->forexDeposit($sourceTo, $amount, $comment);

        }

    }

    public function partnerDashboard()
    {
        $balance = BigDecimal::of(0);
        if(auth()->user()->ib_login) {
            $getUserResponse = $this->getUserApi(auth()->user()->ib_login);
//            dd($getUserResponse->object(),$getUserResponse->object()->Login);
            if ($getUserResponse->status() == 200 && isset($getUserResponse->object()->Login)) {
//                $this->updateUserAccount($getUserResponse);
                $balance = $getUserResponse->object()->Balance;
            }
        }
        $childUsers = Referral::where('refer_by', auth()->user()->id)->pluck('user_id');
        $userId = auth()->user()->id;
        $leads = User::whereIn("id", function ($query) use ($userId) {
            $query->select('user_id')
                ->from('referrals')
                ->where('refer_by', $userId);
        })->where('state', UserState::LEAD)->count();
        $clients = User::whereIn("id", function ($query) use ($userId) {
            $query->select('user_id')
                ->from('referrals')
                ->where('refer_by', $userId);
        })->where('state', UserState::CLIENT)->count();
        $retiring = User::whereIn("id", function ($query) use ($userId) {
            $query->select('user_id')
                ->from('referrals')
                ->where('refer_by', $userId);
        })->where('state', UserState::RETIRING)->count();
        $retired = User::whereIn("id", function ($query) use ($userId) {
            $query->select('user_id')
                ->from('referrals')
                ->where('refer_by', $userId);
        })->where('state', UserState::RETIRED)->count();
        $totalVolume = ForexTrading::whereIn("user_id", function ($query) use ($userId) {
            $query->select('user_id')
                ->from('referrals')
                ->where('refer_by', $userId);
        })
            ->where('account_type', ForexTradingAccountTypesStatus::REAL)
            ->where('status', ForexTradingStatus::ACTIVE)
            ->sum('total_volume');
//        dd($totalVolume);

//        $leads = User::whereHas('referrals', function (Builder $query) {
//            $query->where('state', UserState::LEAD);
//        })->whereIn('id',$childUsers)->count();
//        $clients = User::has('user_real_trading_accounts_client')
//            ->whereIn('id',$childUsers)->get();
//        dd($childUsers,$leads);
        $totalDeposit = Transaction::deposit()
            ->where('user_id',$userId)
            ->completed()
            ->sumTotal()
            ->first()
            ->toArray()['total'];
        $totalWithdraw= Transaction::withdraw()
            ->where('user_id',$userId)
            ->completed()
            ->sumTotal()
            ->first()
            ->toArray()['total'];
        $dw = BigDecimal::of($totalDeposit)->minus($totalWithdraw);
//        dd($totalDeposit,$totalWithdraw);

        return view('user.partner-dashboard',compact('balance','leads','clients','retiring','retired','dw','totalVolume'));
    }
}
