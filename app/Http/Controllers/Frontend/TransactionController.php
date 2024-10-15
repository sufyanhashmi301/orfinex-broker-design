<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ForexAccountStatus;
use App\Http\Controllers\Controller;
use App\Models\ForexAccount;
use App\Models\Transaction;
use App\Traits\ForexApiTrait;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TransactionController extends Controller
{
    use ForexApiTrait;
    public function transactions()
    {
//        dd(request('date'));
        $transactions = Transaction::search(request('query'), function ($query) {
            $query->where('user_id', auth()->user()->id)
                ->when(request('date'), function ($query) {
                    $query->whereDay('created_at', '=', Carbon::parse(request('date'))->format('d'));
                });
        })->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')
            ->paginate(10)->withQueryString();
//            ->get();
//        dd($transactions);

        return view('frontend::user.transaction.index', compact('transactions'));
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
