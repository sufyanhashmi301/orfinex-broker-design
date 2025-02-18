<?php

namespace App\Http\Controllers\Backend;

use Txn;
use Purifier;
use DataTables;
use App\Enums\TxnType;
use App\Models\Gateway;
use App\Enums\TxnStatus;
use App\Enums\GatewayType;
use App\Models\Transaction;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Illuminate\Http\Request;
use App\Models\DepositMethod;
use App\Traits\ForexApiTrait;
use App\Exports\DepositsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Enums\InvestmentPhaseApproval;
use App\Models\AccountTypeInvestment;
use App\Models\AccountTypeInvestmentSnapshot;
use App\Services\AccountActivityService;
use App\Services\UserAffiliateService;
use Illuminate\Support\Facades\Validator;
use App\Services\AccountTypeInvestmentPaymentService;

class DepositController extends Controller
{
    use NotifyTrait, ImageUpload, ForexApiTrait;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    private $investment_payment;
    public $affiliate;


    public function __construct(AccountTypeInvestmentPaymentService $investment_payment, UserAffiliateService $userAffiliate)
    {
        $this->investment_payment = $investment_payment;
        $this->affiliate = $userAffiliate;
        $this->middleware('permission:deposit-list|deposit-action', ['only' => ['pending', 'history']]);
        $this->middleware('permission:deposit-action', ['only' => ['depositAction', 'actionNow']]);
    }

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
                ->rawColumns(['status', 'type', 'final_amount', 'username', 'action'])
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
        if ($gateway) {
            if ($gateway->type == GatewayType::Manual->value) {
                $fieldOptions = $gateway->field_options;
                $paymentDetails = $gateway->payment_details;
                $gateway = array_merge($gateway->toArray(), ['credentials' => view('frontend::gateway.include.manual', compact('fieldOptions', 'paymentDetails'))->render()]);
            } else {
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

        if (isset($input['approve'])) {

            $new_account = $this->investment_payment->investmentActive($transaction->target_id);

            AccountActivityService::log($new_account, AccountActivityStatusEnums::ACTIVE);

            Txn::update($transaction->tnx, TxnStatus::Success, $transaction->user_id, $approvalCause);

            notify()->success('Payment Approved Successfully!');
        } elseif (isset($input['reject'])) {

            // Delete Account
            AccountTypeInvestment::where('id', $transaction->target_id)->delete();
            AccountTypeInvestmentSnapshot::where('account_type_investment_id', $transaction->target_id)->delete();

            Txn::update($transaction->tnx, TxnStatus::Failed, $transaction->user_id, $approvalCause);
            notify()->success('Payment Rejected Successfully!');
        }

        return redirect()->back();
    }

    public function export(Request $request)
    {

        return Excel::download(new DepositsExport($request), 'deposits.xlsx');
    }
    public function view($id)
    {
        $transaction = Transaction::find($id);
        return response()->json(['transaction' => $transaction]);
    }
}
