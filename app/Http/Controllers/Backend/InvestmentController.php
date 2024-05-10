<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ForexAccountStatus;
use App\Http\Controllers\Controller;
use App\Models\ForexAccount;
use App\Models\Invest;
use DataTables;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class   InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:investment-list');

    }

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws Exception
     */
    public function forexAccounts(Request $request, $type = 'real', $id = null)
    {
//        dd($request->all(),$type,$id);
        if ($request->ajax()) {

            if ($id) {
                $data = ForexAccount::with('schema')->where('user_id', $id)->where('account_type', $type)->latest();
            } else {
                $data = ForexAccount::query()->with('schema')->where('account_type', $type)->latest();
            }
//dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('ib_number', 'backend.user.include.__ib_number')
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('balance', 'backend.investment.include.__balance_mt5')
                ->addColumn('equity', 'backend.investment.include.__equity_mt5')
                ->addColumn('credit', 'backend.investment.include.__credit_mt5')
                ->addColumn('schema', 'backend.investment.include.__invest_schema')
                ->addColumn('status', 'backend.investment.include.__status')
                ->addColumn('action', '')
                ->rawColumns(['ib_number', 'schema', 'username','balance','equity','credit', 'status','action'])
                ->make(true);
        }
        $realForexAccounts = ForexAccount::where('account_type', $type)
            ->where('status', ForexAccountStatus::Ongoing)->pluck('login');
//dd($realForexAccounts);
        $withBalance = DB::connection('mt5_db')
            ->table('mt5_accounts')
            ->whereIn('Login', $realForexAccounts)
            ->where('Balance', '>',0)->count();
        $withoutBalance = DB::connection('mt5_db')
            ->table('mt5_accounts')
            ->whereIn('Login', $realForexAccounts)
            ->where('Balance', 0)->count();
        $unActiveAccounts = ForexAccount::where('account_type',$type)->where('status','!=',ForexAccountStatus::Ongoing)->count();

        $data = [
        'TotalAccounts' => ForexAccount::where('account_type', $type)->count(),
        'withBalance' => $withBalance,
        'withoutBalance' => $withoutBalance,
        'unActiveAccounts' => $unActiveAccounts,
        ];
        return view('backend.investment.index',compact('data','type'));
    }

}
