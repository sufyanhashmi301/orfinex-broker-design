<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ForexAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class PositionController extends Controller
{

    public function index()
    {
        $realForexAccounts = ForexAccount::realActiveAccount()->traderType()->orderBy('balance', 'desc')->get();
        $demoForexAccounts = ForexAccount::demoActiveAccount()->traderType()->orderBy('balance', 'desc')->get();
        $archiveForexAccounts = ForexAccount::archiveAccount()->traderType()->orderBy('balance', 'desc')->get();

        return view('frontend::user.forex.trades_history', compact('realForexAccounts', 'demoForexAccounts', 'archiveForexAccounts'));
    }

    public function getOrders()
    {
        $table = 'mt5_deals_' . Carbon::now()->year;

        $orders = DB::connection('mt5_db')
            ->table($table)
            ->when(request('trade_date'), function ($query) {
                $tradeDate = request('trade_date');

                list($startDate, $endDate) = explode(' to ', $tradeDate);
                $startDate = Carbon::parse($startDate)->startOfDay();
                $endDate = Carbon::parse($endDate)->endOfDay();
                $query->whereBetween('Time', [$startDate, $endDate]);
            })
            ->when(request('account_login'), function ($query) {
                $query->where('login', request('account_login'));
            })
            ->when(request('trade_status'), function ($query) {
                $status = request('trade_status');

                if ($status === 'close') {
                    $query->whereColumn('Volume', '=', 'VolumeClosed');
                }
                elseif ($status === 'open') {
                    $query->whereColumn('Volume', '<>', 'VolumeClosed');
                }
            })
            ->whereIn('Action', [0, 1])
            ->orderBy('Time', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('frontend::user.forex.include.__trades_data', compact('orders'));
    }
}
