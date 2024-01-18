<?php

namespace App\Http\Controllers\Backend;

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
        if ($request->ajax()) {

            if ($id) {
                $data = ForexAccount::with('schema')->where('user_id', $id)->latest();
            } else {
                $data = ForexAccount::query()->with('schema')->latest();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('icon', 'backend.investment.include.__invest_icon')
                ->addColumn('username', 'backend.transaction.include.__user')
//                ->addColumn('schema', 'backend.investment.include.__invest_schema')
                ->rawColumns(['icon', 'schema', 'username'])
                ->make(true);
        }

        return view('backend.investment.index');
    }
}
