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

            // Start query on mt5_symbols
            $data = DB::connection('mt5_db')
                ->table('mt5_symbols')
                ->select('mt5_symbols.Symbol_ID', 'mt5_symbols.Symbol', 'mt5_symbols.Path', 'mt5_symbols.Description', 'mt5_symbols.ContractSize')
                ->leftJoin(DB::raw('symbols as s'), 's.symbol', '=', 'mt5_symbols.Symbol') // Join to check existence and status
                ->selectRaw('IF(s.status = 1, "Enabled", "Disabled") as status'); // Check status

            // Apply filters
            if ($request->filled('global_search')) {
                $data->where(function ($query) use ($request) {
                    $query->where('mt5_symbols.Symbol', 'LIKE', '%' . $request->global_search . '%')
                        ->orWhere('mt5_symbols.Description', 'LIKE', '%' . $request->global_search . '%')
                        ->orWhere('mt5_symbols.Path', 'LIKE', '%' . $request->global_search . '%');
                });
            }

            if ($request->filled('contact_size')) {
                $data->where('mt5_symbols.ContractSize', 'LIKE', '%' . $request->contact_size . '%');
            }

            if ($request->filled('path')) {
                $data->where('mt5_symbols.Path', 'LIKE', '%' . $request->path . '%');
            }

            if ($request->filled('status')) {
                if ($request->status == 1) {
                    // Show Enabled: Symbols existing with status = 1
                    $data->where('s.status', '=', 1);
                } elseif ($request->status == 0) {
                    // Show Disabled: Either not existing or status = 0
                    $data->where(function ($query) {
                        $query->whereNull('s.symbol')
                            ->orWhere('s.status', '=', 0);
                    });
                }
            }

            $existingSymbols = Symbol::pluck('symbol')->toArray();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('action', function ($row) use ($existingSymbols) {
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
