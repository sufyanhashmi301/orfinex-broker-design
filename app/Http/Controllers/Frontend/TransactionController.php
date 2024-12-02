<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ForexAccountStatus;
use App\Http\Controllers\Controller;
use App\Models\ForexAccount;
use App\Models\Transaction;
use App\Exports\AllTransactionsExport;
use App\Traits\ForexApiTrait;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;



class TransactionController extends Controller
{
    use ForexApiTrait;
    public function transactions()
    {
        $transactions = Transaction::search(request('query'))
            ->query(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->when(request('transaction_date'), function ($query) {
                        $filter = request('transaction_date');

                        if (in_array($filter, ['3_days', '5_days', '15_days'])) {
                            $daysAgo = substr($filter, 0, strpos($filter, '_'));
                            $query->where('created_at', '>=', Carbon::now()->subDays($daysAgo)->startOfDay());
                        } elseif ($filter == '1_month') {
                            $query->where('created_at', '>=', Carbon::now()->subMonth()->startOfDay());
                        } elseif ($filter == '3_months') {
                            $query->where('created_at', '>=', Carbon::now()->subMonths(3)->startOfDay());
                        }
                    })
                    ->when(request('transaction_status'), function ($query) {
                        $query->where('status', request('transaction_status'));
                    })
                    ->when(request('transaction_type'), function ($query) {
                        $query->where('type', request('transaction_type'));
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        if (request()->ajax()) {
            return view('frontend::user.transaction.include.__transaction_row', compact('transactions'))->render();
        }

        return view('frontend::user.transaction.index', compact('transactions'));
    }

    public function export(Request $request)
    {
        return Excel::download(new AllTransactionsExport($request), 'All-History.xlsx');
    }


    public function forexTransactions()
    {
//        dd(request('start_date'),request('end_date'));
        $start = Carbon::now()->subDay(1)->startOfDay()->timestamp;
        $end = Carbon::now()->endOfDay()->timestamp;
//        dd(request('start_date'),request('end_date'),request('receiver_account'));
        if (request('start_date')) {
            $start = Carbon::parse(request('start_date'))->startOfDay()->timestamp;
        }
        if (request('end_date')) {
            $end = Carbon::parse(request('end_date'))->endOfDay()->timestamp;
        }
//            dd($startIbCalc);
        $login = request('login');
        $data = [];
        if ($login) {
            $response = $this->getOrderList($login, $start, $end);
            if ($response) {
                $data = $response->object();
            }
        }
        $transactions = new Collection($data);
        $forexAccounts = ForexAccount::where('user_id', auth()->id())->traderType()
            ->where('account_type', 'real')
            ->where('status',ForexAccountStatus::Ongoing)
            ->get();

        return view('frontend::user.transaction.forex-orders', compact('transactions','forexAccounts'));
    }
}
