<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ForexAccountStatus;
use App\Models\Account;
use App\Models\User;
use Txn;
use Purifier;
use DataTables;
use Carbon\Carbon;
use App\Models\Rate;
use App\Enums\TxnType;
use App\Models\Invest;
use App\Models\Country;
use App\Models\Gateway;
use App\Enums\TxnStatus;
use App\Enums\GatewayType;
use App\Enums\InvestStatus;
use App\Enums\TxnTargetType;
use App\Models\Transaction;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use App\Models\ForexAccount;
use Illuminate\Http\Request;
use App\Models\DepositMethod;
use App\Models\LevelReferral;
use App\Traits\ForexApiTrait;
use App\Exports\DepositsExport;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class DepositController extends Controller
{
    use NotifyTrait, ImageUpload, ForexApiTrait;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:deposit-list|deposit-action', ['only' => ['pending', 'history']]);
        $this->middleware('permission:deposit-action', ['only' => ['depositAction', 'actionNow']]);
    }

    //-------------------------------------------  Deposit method start ---------------------------------------------------------------

    public function methodList($type)
    {
        $button = [
            'name' => __('ADD NEW'),
            'icon' => 'plus',
            'route' => route('admin.deposit.method.create', $type),
        ];

        $depositMethods = DepositMethod::where('type', $type)->get();

        return view('backend.deposit.method_list', compact('depositMethods', 'button', 'type'));
    }

    public function createMethod($type)
    {
        $gateways = Gateway::where('status', true)->get();
        $rates_with_countries = Rate::with('country')->get();

        return view('backend.deposit.create_method', compact('type', 'gateways', 'rates_with_countries'));
    }

    public function methodStore(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'logo' => 'required_if:type,==,manual',
            'name' => 'required',
            'gateway_id' => 'required_if:type,==,auto',
            'method_code' => 'unique:deposit_methods,gateway_code|required_if:type,==,manual',
            'currency' => 'required',
            'currency_symbol' => 'required',
            'charge' => 'required',
            'charge_type' => 'required',
            'rate' => 'required',
            'minimum_deposit' => 'required',
            'maximum_deposit' => 'required',
            'processing_time' => 'required',
            'status' => 'required',
            'field_options' => 'required_if:type,==,manual',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        if (isset($input['gateway_id'])) {
            $gateway = Gateway::find($input['gateway_id']);
            $methodCode = $gateway->gateway_code . '-' . strtolower($input['currency']);
        }

        $data = [
            'logo' => isset($input['logo']) ? self::imageUploadTrait($input['logo']) : null,
            'name' => $input['name'],
            'type' => $input['type'],
            'gateway_id' => $input['gateway_id'] ?? null,
            'gateway_code' => $input['method_code'] ?? $methodCode,
            'currency' => $input['currency'],
            'currency_symbol' => $input['currency_symbol'],
            'charge' => $input['charge'],
            'charge_type' => $input['charge_type'],
            'rate' => $input['rate'],
            'minimum_deposit' => $input['minimum_deposit'],
            'maximum_deposit' => $input['maximum_deposit'],
            'processing_time' => $input['processing_time'],
            'country' => isset($input['country']) ? $input['country'] : ['All'],
            'status' => $input['status'],
            'field_options' => isset($input['field_options']) ? json_encode($input['field_options']) : null,
            'payment_details' => isset($input['payment_details']) ? Purifier::clean(htmlspecialchars_decode($input['payment_details'])) : null,
        ];

        $depositMethod = DepositMethod::create($data);
        notify()->success($depositMethod->name . ' ' . __(' Method Created'));

        return redirect()->route('admin.deposit.method.list', $depositMethod->type);
    }

    public function methodEdit($type)
    {
        $gateways = Gateway::where('status', true)->get();
        $method = DepositMethod::find(\request('id'));
        $supported_currencies = Gateway::find($method->gateway_id)->supported_currencies ?? [];

        return view('backend.deposit.edit_method', compact('method', 'type', 'gateways', 'supported_currencies'));
    }

    public function methodUpdate($id, Request $request)
    {
        $input = $request->all();
//        dd($input);
        $validator = Validator::make($input, [
            'name' => 'required',
            'gateway_id' => 'required_if:type,==,auto',
            'currency' => 'required',
            'currency_symbol' => 'required',
            'charge' => 'required',
            'charge_type' => 'required',
            'rate' => 'required',
            'minimum_deposit' => 'required',
            'maximum_deposit' => 'required',
            'processing_time' => 'required',
            'status' => 'required',
            'field_options' => 'required_if:type,==,manual',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);  // Laravel will return status 422 automatically
        }

        $depositMethod = DepositMethod::find($id);

        $user = \Auth::user();
        if ($depositMethod->type == GatewayType::Automatic) {
            if (!$user->can('automatic-gateway-manage')) {
//                return redirect()->route('admin.deposit.method.list', $depositMethod->type);
                return response()->json(['redirect'=> route('admin.deposit.method.list', $depositMethod->type)]);

            }

        } else {
            if (!$user->can('manual-gateway-manage')) {
                return response()->json(['redirect'=> route('admin.deposit.method.list', $depositMethod->type)]);

//                return redirect()->route('admin.deposit.method.list', $depositMethod->type);
            }
        }

        $data = [
            'name' => $input['name'],
            'type' => $input['type'],
            'gateway_id' => $input['gateway_id'] ?? null,
            'currency' => $input['currency'],
            'currency_symbol' => $input['currency_symbol'],
            'charge' => $input['charge'],
            'charge_type' => $input['charge_type'],
            'rate' => $input['rate'],
            'minimum_deposit' => $input['minimum_deposit'],
            'maximum_deposit' => $input['maximum_deposit'],
            'processing_time' => $input['processing_time'],
            'country' => isset($input['country']) ? $input['country'] : ['All'],
            'status' => $input['status'],
            'field_options' => isset($input['field_options']) ? json_encode($input['field_options']) : null,
            'payment_details' => isset($input['payment_details']) ? Purifier::clean(htmlspecialchars_decode($input['payment_details'])) : null,
        ];
//dd($data);
        if ($request->hasFile('logo')) {
            $logo = self::imageUploadTrait($input['logo'], $depositMethod->logo);
            $data = array_merge($data, ['logo' => $logo]);
        }

        $depositMethod->update($data);
        notify()->success($depositMethod->name . ' ' . __(' Method Updated'));
        return response()->json(['redirect'=> route('admin.deposit.method.list', $depositMethod->type)]);
        

//        return redirect()->route('admin.deposit.method.list', $depositMethod->type);
    }

    //-------------------------------------------  Deposit method end ---------------------------------------------------------------

    public function pending(Request $request)
    {

        if ($request->ajax()) {
            $data = Transaction::where('status', 'pending')->where(function ($query) {
                return $query->where('type', TxnType::ManualDeposit)
                    ->orWhere('type', TxnType::Investment);
            })->latest();

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', 'backend.transaction.include.__txn_status')
                ->editColumn('type', 'backend.transaction.include.__txn_type')
                ->editColumn('amount', 'backend.transaction.include.__txn_amount')
                ->editColumn('charge', function ($request) {
                    return $request->charge . ' ' . setting('site_currency', 'global');
                })
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('action', 'backend.deposit.include.__action')
                ->rawColumns(['action', 'status', 'type', 'amount', 'username'])
                ->make(true);
        }

        return view('backend.deposit.manual');
    }

    public function history(Request $request)
    {

        $filters = $request->only(['email', 'status',  'created_at']);

        if ($request->ajax()) {
            $data = Transaction::where(function ($query) {
                $query->where('type', TxnType::ManualDeposit)
                    ->orWhere('type', TxnType::Deposit);
            })->latest();
            $data->applyFilters($filters);
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', 'backend.transaction.include.__txn_status')
                ->editColumn('type', 'backend.transaction.include.__txn_type')
                ->editColumn('final_amount', 'backend.transaction.include.__txn_amount')
                ->editColumn('charge', function ($request) {
                    return $request->charge . ' ' . setting('site_currency', 'global');
                })
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('action', 'backend.transaction.include.__action')
                ->rawColumns(['status', 'type', 'final_amount', 'username','action'])
                ->make(true);
        }

        return view('backend.deposit.history');
    }

    public function depositAction($id)
    {

        $data = Transaction::find($id);
        $gateway = $this->gateway($data->method);
        return view('backend.deposit.include.__deposit_action', compact('data', 'id', 'gateway'))->render();
    }

    public function gateway($code)
    {
        $gateway = DepositMethod::code($code)->first();
        if($gateway){
            if ($gateway->type == GatewayType::Manual->value) {
                $fieldOptions = $gateway->field_options;
                $paymentDetails = $gateway->payment_details;
                $gateway = array_merge($gateway->toArray(), ['credentials' => view('frontend::gateway.include.manual', compact('fieldOptions', 'paymentDetails'))->render()]);
            }else{
                $gatewayCurrency =  is_custom_rate($gateway->gateway->gateway_code) ?? $gateway->currency;
                $gateway['currency'] = $gatewayCurrency;
            }
            return $gateway;
        }
    }


    public function actionNow(Request $request)
    {

        $input = $request->all();
        $id = $input['id'];
        $approvalCause = $input['message'];
        $transaction = Transaction::find($id);

        $shortcodes = [
            '[[full_name]]' => $transaction->user->full_name,
            '[[txn]]' => $transaction->tnx,
            '[[gateway_name]]' => $transaction->method,
            '[[deposit_amount]]' => $transaction->amount,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
            '[[message]]' => $transaction->approval_cause,
            '[[status]]' => isset($input['approve']) ? 'approved' : 'Rejected',
        ];

        if (isset($input['approve'])) {

                $transaction->amount = $input['final_amount'];
                $transaction->final_amount = $input['final_amount'];
                $transaction->pay_amount = $input['final_amount'];
                if(isset($input['pay_amount'])) {
                    $transaction->pay_amount = $input['pay_amount'];
                }
                $transaction->save();
                $transaction = $transaction->fresh();



            Txn::update($transaction->tnx, TxnStatus::Success, $transaction->user_id, $approvalCause);


            $this->mailNotify($transaction->user->email, 'user_manual_deposit_approve', $shortcodes);

            notify()->success('Approve successfully');

        } elseif (isset($input['reject'])) {
            $invest = Invest::where('transaction_id', $id)->first();

            if ($invest) {
                $invest->delete();
            }
            Txn::update($transaction->tnx, TxnStatus::Failed, $transaction->user_id, $approvalCause);
            $this->mailNotify($transaction->user->email, 'user_manual_deposit_reject', $shortcodes);
            notify()->success('Reject successfully');
        }

        $this->pushNotify('user_manual_deposit_request', $shortcodes, route('user.deposit.log'), $transaction->user->id);
        $this->smsNotify('user_manual_deposit_request', $shortcodes, $transaction->user->phone);

        return redirect()->back();
    }

    public function export(Request $request)
    {

        return Excel::download(new DepositsExport($request), 'deposits.xlsx');
    }
    public function view($id)
    {
        $transaction = Transaction::find($id);
        return response()->json(['transaction'=>$transaction]);
    }

    public function destroy($id)
    {
//        dd($id);
        try {
            // Find the method by its ID and delete it
            $method = DepositMethod::findOrFail($id);
            if(Transaction::where('method',$method->gateway_code)->exists()){
                notify()->error(__('This method is associated with existing transactions, and therefore cannot be deleted: :method', ['method' => $method->name]), 'Error');
                return redirect()->back();
            }
            $method->delete();
            notify()->success('Successfully deleted method');

            return redirect()->back();
        } catch (\Exception $e) {
            notify()->error('Something went wrong, Please check error log', 'Error Log');
            return redirect()->back();
        }
    }

    public function addDeposit()
    {
        $gateways = DepositMethod::where('status', 1)->get();
        $users = User::where('status',1)->get();

//        dd($gateways);
//        $clientIp = request()->ip();
//        if (!in_array($clientIp, ['127.0.0.1', '::1'])) {
//            $this->syncForexAccounts(auth()->id());
//        }
        $forexAccounts = ForexAccount::with('schema')->traderType()
//            ->where('user_id', auth()->id())
            ->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)
            ->orderBy('id', 'desc')
            ->get();
        return view('backend.deposit.add_deposit', compact( 'users','gateways', 'forexAccounts'));
    }
    public function getUserAccounts($userId)
    {
        $userId = get_hash($userId);
        $forexAccounts = ForexAccount::with('schema')
            ->where('user_id', $userId)
            ->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)
            ->orderBy('id', 'desc')
            ->get();
        $wallets = get_all_wallets($userId);
        return response()->json([
            'forexAccounts' => $forexAccounts->map(function ($account) {
                            // Ensure wallet_name is included
                            return [
                                'login' => $account->login,
                                'equity' => get_mt5_account_equity($account->login),
                                'account_name' => $account->account_name, // This is the accessor you defined
                            ];
                        }),
            'wallets' => $wallets->map(function ($wallet) {
                        // Ensure wallet_name is included
                        return [
                            'id' => $wallet->id,
                            'wallet_id' => $wallet->wallet_id,
                            'wallet_name' => $wallet->wallet_name, // This is the accessor you defined
                            'amount' => $wallet->amount
                        ];
                    })
        ]);

    }
    public function depositNow(Request $request)
    {

//        if (!setting('user_deposit', 'permission') || !\Auth::user()->deposit_status) {
//            abort('403', __('Deposit Disabled Now'));
//        }
//dd($request->all());
        // Validate request input
        $validator = Validator::make($request->all(), [
            'user_id' => ['required'], // Removed integer validation because wallet id and forex login are not the same type
            'target_id' => ['required'], // Removed integer validation because wallet id and forex login are not the same type
            'gateway_code' => 'required',
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9]{1,4})?$/'],
        ], [
            'target_id.required' => __('Kindly select an account for deposit'),
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(),  __('Error'));
            return redirect()->back();
        }
        $userID = get_hash($request->user_id);
        $user = User::findOrFail($userID);

        $input = $request->all();
        $gatewayInfo = DepositMethod::code($input['gateway_code'])->first();
        $amount = $input['amount'];
//        dd($amount);

        // Check deposit amount against the gateway's limits
        if ($amount < $gatewayInfo->minimum_deposit || $amount > $gatewayInfo->maximum_deposit) {
            $currencySymbol = setting('currency_symbol', 'global');
            $message = __('Please deposit the amount within the range') . $currencySymbol . $gatewayInfo->minimum_deposit . __('to')  . $currencySymbol . $gatewayInfo->maximum_deposit;
            notify()->error($message,  __('Error'));
            return redirect()->back();
        }

        // Determine whether it's a forex account or a wallet
        $targetId = $input['target_id'];
        $targetType = TxnTargetType::ForexDeposit->value; // Default to forex_deposit

    // Check if the selected target is a forex account
    $forexAccount = ForexAccount::where('login', $targetId)->first();
//    dd($forexAccount,$targetId);

    if ($forexAccount) {
        // It's a Forex account, handle the Forex-specific validation
//        if (isset($forexAccount->schema->first_min_deposit) && $forexAccount->schema->first_min_deposit > 0) {
//            if (!$forexAccount->first_min_deposit_paid && $amount < $forexAccount->schema->first_min_deposit) {
//                $currencySymbol = setting('currency_symbol', 'global');
//                $message =  __('Please deposit the first minimum amount of') . $currencySymbol . $forexAccount->schema->first_min_deposit;
//                notify()->error($message, __('Error'));
//                return redirect()->back();
//            }
//        }
    } else {
        // If it's not a Forex account, it must be a wallet, so change target type to wallet
        $account = get_user_account_by_wallet_id($targetId,$user->id);
        if ($account) {
            $targetType = TxnTargetType::Wallet->value;
        } else {
            notify()->error(__('The selected account does not exist'), __('Error'));
            return redirect()->back();
        }
    }
    // Proceed with transaction
    $charge = $gatewayInfo->charge_type == 'percentage' ? (($gatewayInfo->charge / 100) * $amount) : $gatewayInfo->charge;
    $finalAmount = (float)$amount + (float)$charge;
    $payAmount = $finalAmount * $gatewayInfo->rate;
    $depositType =  TxnType::ManualDeposit;;

    if (isset($input['manual_data'])) {
        $depositType = TxnType::ManualDeposit;
        $manualData = $input['manual_data'];
        foreach ($manualData as $key => $value) {
            if (is_file($value)) {
                $manualData[$key] = self::depositImageUploadTrait($value);
            }
        }
    }
//    dd($input);
    $approvalCause = isset($request->approval_cause) ? $request->approval_cause : 'none';

    // Create transaction with the appropriate target_id and target_type
    $txnInfo = Txn::new(
        $input['amount'], $charge, $finalAmount, $gatewayInfo->gateway_code,
        __('Deposit With ') . $gatewayInfo->name . __(' by Admin'), $depositType, TxnStatus::Pending,
        $gatewayInfo->currency, $payAmount, $userID, null, 'User',
        $manualData ?? [], $approvalCause, $targetId, $targetType
    );
//    dd($txnInfo);
$shortcodes = [
    '[[full_name]]' => $txnInfo->user->full_name,
    '[[txn]]' => $txnInfo->tnx,
    '[[gateway_name]]' => $txnInfo->method,
    '[[deposit_amount]]' => $txnInfo->amount,
    '[[site_title]]' => setting('site_title', 'global'),
    '[[site_url]]' => route('home'),
    '[[message]]' => $txnInfo->approval_cause,
    '[[status]]' =>  'approved' ,
];
    if ($request->is_auto_approve == true) {
        Txn::update($txnInfo->tnx, TxnStatus::Success, $txnInfo->user_id, $approvalCause);
        $this->mailNotify($txnInfo->user->email, 'user_manual_deposit_approve', $shortcodes);
        notify()->success('Approve successfully');
        return redirect()->back();
    }

        $shortcodes['[[status]]'] = 'Pending';
        $this->mailNotify($txnInfo->user->email, 'user_manual_deposit_request', $shortcodes);
        $this->mailNotify(setting('site_email', 'global'), 'manual_deposit_request', $shortcodes);
        $this->pushNotify('manual_deposit_request', $shortcodes, route('user.deposit.log'), $user->id);

        notify()->success('Successfully added pending deposit request');
      return redirect()->back();
}

}

