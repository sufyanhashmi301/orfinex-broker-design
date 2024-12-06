<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Symbol;
use App\Services\SymbolService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class SymbolController extends Controller
{
    protected $symbolService;

    public function __construct(SymbolService $symbolService)
    {
        $this->symbolService = $symbolService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::connection('mt5_db')
            ->table('mt5_symbols')
            ->select('Symbol_ID','Symbol','Path','Description','ContractSize');
            $existingSymbols = Symbol::pluck('symbol')->toArray();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($existingSymbols) {
                return view('backend.symbols.include.__action', [
                    'Symbol_ID' => $row->Symbol_ID,
                    'Symbol' => $row->Symbol,
                    'existingSymbols' => $existingSymbols
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('backend.symbols.all');
    }

    public function store(Request $request)
    {

        $symbolId = $request->id;

        $result = $this->symbolService->createSymbolFromMt5($symbolId);

        if ($result['success']) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }

    }

    public function storeAllSymbols(Request $request)
    {
        // Call the method to store all symbols
        $result = $this->symbolService->storeAllSymbolsFromMt5();

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => __('Successfully stored all symbols.'),
                'success_count' => $result['success_count'],
                'failure_count' => $result['failure_count']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('Failed to store some symbols.'),
                'success_count' => $result['success_count'],
                'failure_count' => $result['failure_count']
            ]);
        }
    }
}
