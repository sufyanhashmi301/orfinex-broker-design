<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ForexAccountStatus;
use App\Http\Controllers\Controller;
use App\Models\ForexAccount;
use App\Models\Transaction;
use App\Exports\AllTransactionsExport;
use App\Traits\ForexApiTrait;
use App\Services\ForexApiService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;



class TransactionController extends Controller
{
    use ForexApiTrait;
    protected $forexApiService;

    public function __construct(ForexApiService $forexApiService)
    {
        $this->forexApiService = $forexApiService;
    }

    public function transactions()
    {
        $realForexAccounts = ForexAccount::realActiveAccount()->traderType()
            ->orderBy('balance', 'desc')
            ->get();

        $query = Transaction::where('user_id', auth()->user()->id);

        if (request('transaction_date')) {
            $filter = request('transaction_date');
            if (in_array($filter, ['3_days', '5_days', '15_days'])) {
                $daysAgo = substr($filter, 0, strpos($filter, '_'));
                $query->where('created_at', '>=', Carbon::now()->subDays($daysAgo)->startOfDay());
            } elseif ($filter == '1_month') {
                $query->where('created_at', '>=', Carbon::now()->subMonth()->startOfDay());
            } elseif ($filter == '3_months') {
                $query->where('created_at', '>=', Carbon::now()->subMonths(3)->startOfDay());
            }
        }

        if (request('transaction_status')) {
            $query->where('status', request('transaction_status'));
        }

        if (request('transaction_type')) {
            $query->where('type', request('transaction_type'));
        }

        if (request('forex_account')) {
            $query->where('target_id', request('forex_account'));
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());

        if (request()->ajax()) {
            return response()->json([
                'html' => view('frontend::user.transaction.include.__transaction_row', compact('transactions'))->render(),
                'pagination' => (string) $transactions->links(),
                'total' => $transactions->total(),
            ]);
        }

        return view('frontend::user.transaction.index', compact('transactions', 'realForexAccounts'));
    }

    public function export(Request $request)
    {
        $query = Transaction::where('user_id', auth()->user()->id);

        if ($request->date) {
            $filter = $request->date;
            if (in_array($filter, ['3_days', '5_days', '15_days'])) {
                $daysAgo = substr($filter, 0, strpos($filter, '_'));
                $query->where('created_at', '>=', Carbon::now()->subDays($daysAgo)->startOfDay());
            } elseif ($filter == '1_month') {
                $query->where('created_at', '>=', Carbon::now()->subMonth()->startOfDay());
            } elseif ($filter == '3_months') {
                $query->where('created_at', '>=', Carbon::now()->subMonths(3)->startOfDay());
            }
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->forex_account) {
            $query->where('target_id', $request->forex_account);
        }

        $transactions = $query->get();

        return Excel::download(new AllTransactionsExport($transactions), 'Filtered-Transactions.xlsx');
    }


    public function forexTransactions()
    {

        $login = request('login');
        $data['login'] = $login;

        $start = Carbon::now()->subDay(1)->startOfDay()->timestamp;
        $end = Carbon::now()->endOfDay()->timestamp;

        if (request('start_date')) {
            $start = Carbon::parse(request('start_date'))->format('d/m/Y');
            $data['FromDate'] = $start;
        }
        if (request('end_date')) {
            $end = Carbon::parse(request('end_date'))->format('d/m/Y');
            $data['ToDate'] = $end;
        }

        //dd($data);

        $orders = [];
        $transactions = [];

        if ($login) {
            if (request('type') == 'trades-report'){
                $response = $this->forexApiService->getOrders($data);
                if ($response) {
                    $orders = collect($response['result']);
                }
            }
            elseif (request('type') == 'balance-report') {
                $response = $this->forexApiService->getBalanceReport($data);
                //dd($response);
                if ($response) {
                    $transactions = collect($response['result']);
                }
            }

        }
        $forexAccounts = ForexAccount::where('user_id', auth()->id())->traderType()
            ->where('account_type', 'real')
            ->where('status',ForexAccountStatus::Ongoing)
            ->get();

        return view('frontend::user.transaction.forex-orders', compact('forexAccounts', 'orders', 'transactions'));
    }
}
