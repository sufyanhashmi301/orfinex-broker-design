<?php

namespace App\Http\Controllers\Backend;

use App\Enums\GatewayType;
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
        if ($request->ajax()) {
            $filters = $request->only(['email', 'status', 'type', 'created_at']);
          
            if ($id) {
                $data = Transaction::where('user_id', $id)->latest();
                
            } else {
                $data = Transaction::query()->latest();
            }
            $data->applyFilters($filters);
           
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', 'backend.transaction.include.__txn_status')
                ->editColumn('type', 'backend.transaction.include.__txn_type')
                ->editColumn('final_amount', 'backend.transaction.include.__txn_amount')
                ->editColumn('charge', function ($request) {
                    return $request->charge.' '.setting('site_currency', 'global');
                })
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('action', 'backend.transaction.include.__action')
                ->rawColumns(['status', 'type', 'final_amount', 'username','action'])
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
        if($data->status->value=='pending'){
            return view('backend.withdraw.include.__withdraw_action', compact('data', 'id'))->render();
        }else{
            return view('backend.transaction.modals.view', compact('data', 'id'))->render();
        }
        
        
    }
   
}
