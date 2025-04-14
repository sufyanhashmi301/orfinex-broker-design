<?php

namespace App\Http\Controllers\Backend;

use App\Enums\GatewayType;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use DataTables;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use App\Models\DepositMethod;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        $this->middleware('permission:transaction-list');

    }

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws \Exception
     */
    public function transactions(Request $request, $id = null)
    {
        $loggedInUser = auth()->user();

        if ($request->ajax()) {
            $filters = $request->only(['email', 'status', 'type', 'created_at']);

            // Check if the logged-in user is a Super-Admin
            if ($loggedInUser->hasRole('Super-Admin')) {
                if ($id) {
                    // Fetch transactions for a specific user (if ID is provided)
                    $data = Transaction::where('user_id', $id)->latest();
                } else {
                    // Fetch all transactions
                    $data = Transaction::query()->latest();
                }
            } else {
                // Get attached user IDs for non-Super-Admin users
                $attachedUserIds = $loggedInUser->users->pluck('id');

                if ($attachedUserIds->isNotEmpty()) {
                    if ($id) {
                        // Fetch transactions for the specified user and ensure they are attached
                        $data = Transaction::where('user_id', $id)
                            ->whereIn('user_id', $attachedUserIds)
                            ->latest();
                    } else {
                        // Fetch transactions for attached users only
                        $data = Transaction::whereIn('user_id', $attachedUserIds)->latest();
                    }
                } else {
                    // If no users are attached, return all users
                    if ($id) {
                        // Fetch transactions for a specific user (if ID is provided)
                        $data = Transaction::where('user_id', $id)->latest();
                    } else {
                        // Fetch all transactions
                        $data = Transaction::query()->latest();
                    }
                }

            }

            // Apply additional filters if any
            $data->applyFilters($filters);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return '<span class="text-nowrap">' . $row->created_at . '</span>';
                })
                ->addColumn('action_by', function ($row) {
                    return '<span class="text-nowrap">' . optional($row->staff)->name ?? '-' . '</span>';
                })

                ->editColumn('status', 'backend.transaction.include.__txn_status')
                ->editColumn('type', 'backend.transaction.include.__txn_type')
                ->editColumn('final_amount', 'backend.transaction.include.__txn_amount')
                ->editColumn('charge', function ($request) {
                    return $request->charge . ' ' . setting('site_currency', 'global');
                })
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('action', 'backend.transaction.include.__action')
                ->editColumn('created_at', function ($row) {
                    if (!empty($row->manual_field_data) && $row->manual_field_data !== '[]') {
                        $manualData = json_decode($row->manual_field_data, true);

                        if (is_array($manualData) && isset($manualData['time'])) {
                            return \Carbon\Carbon::parse($manualData['time'])->format('M d, Y h:i A');
                        }
                    }

                    // Fallback to created_at
                    return $row->created_at;
                })
                ->rawColumns(['created_at', 'status', 'action_by', 'type', 'final_amount', 'username', 'action'])
                ->make(true);
        }

        return view('backend.transaction.index');
    }

    public function export(Request $request)
    {

        return Excel::download(new TransactionsExport($request), 'transactions.xlsx');
    }
    public function view($id)
    {
        $data = Transaction::find($id);

        if($data->status->value=='pending' && ($data->type == TxnType::Withdraw || $data->type == TxnType::WithdrawAuto)){
            return view('backend.withdraw.include.__withdraw_action', compact('data', 'id'))->render();
        }elseif($data->status->value=='pending' && ($data->type == TxnType::Deposit || $data->type == TxnType::ManualDeposit)){
            $gateway = $this->gateway($data->method);
            return view('backend.deposit.include.__deposit_action', compact('data', 'id', 'gateway'))->render();
        }else{
            return view('backend.transaction.modals.view', compact('data', 'id'))->render();
        }


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

}
