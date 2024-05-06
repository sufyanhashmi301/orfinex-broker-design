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

class InvestmentController extends Controller
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
    public function forexAccountsReal(Request $request, $id = null)
    {
//        dd($request->all());
        if ($request->ajax()) {

            if ($id) {
                $data = ForexAccount::with('schema')->where('user_id', $id)->latest();
            } else {
                $data = ForexAccount::query()->with('schema')->latest();
            }
//dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('ib_number', 'backend.user.include.__ib_number')
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('balance', 'backend.investment.include.__balance_mt5')
//                ->addColumn('equity', 'backend.investment.include.__equity_mt5')
                ->addColumn('credit', 'backend.investment.include.__credit_mt5')
                ->addColumn('schema', 'backend.investment.include.__invest_schema')
                ->addColumn('status', 'backend.investment.include.__status')
                ->addColumn('action', '')
                ->rawColumns(['ib_number', 'schema', 'username','balance','credit', 'status','action'])
                ->make(true);
        }
        $withBalance = ForexAccount::where('account_type','real')->where('balance', '>',0)->count();
        $withoutBalance = ForexAccount::where('account_type','real')->where('balance',0)->count();
        $unActiveAccounts = ForexAccount::where('account_type','real')->where('status','!=',ForexAccountStatus::Ongoing)->count();

        $data = [
        'TotalAccounts' => ForexAccount::count(),
        'withBalance' => $withBalance,
        'withoutBalance' => $withoutBalance,
        'unActiveAccounts' => $unActiveAccounts,
        ];
        return view('backend.investment.index',compact('data'));
    }
}
