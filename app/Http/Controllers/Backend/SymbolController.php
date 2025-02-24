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

            // Step 1: Query mt5_symbols from mt5_db connection
            $mt5Query = DB::connection('mt5_db')
                ->table('mt5_symbols')
                ->select('Symbol_ID', 'Symbol', 'Path', 'Description', 'ContractSize');

            // Apply Filters for mt5_symbols
            if ($request->filled('global_search')) {
                $mt5Query->where(function ($query) use ($request) {
                    $query->where('Symbol', 'LIKE', '%' . $request->global_search . '%')
                        ->orWhere('Description', 'LIKE', '%' . $request->global_search . '%')
                        ->orWhere('Path', 'LIKE', '%' . $request->global_search . '%');
                });
            }

            if ($request->filled('contact_size')) {
                $mt5Query->where('ContractSize', 'LIKE', '%' . $request->contact_size . '%');
            }

            if ($request->filled('path')) {
                $mt5Query->where('Path', 'LIKE', '%' . $request->path . '%');
            }

            // Fetch all mt5_symbols
            $mt5Symbols = $mt5Query->get();


            $combined = $mt5Symbols;
            $localSymbols = DB::table('symbols')
                ->select('symbol', 'status')
                ->get()
                ->keyBy('symbol');
            // Step 4: Apply Status Filter After Combining
            if ($request->filled('status')) {
                // Step 2: Query symbols from default connection

                // Step 3: Combine the results
                $combined = $mt5Symbols->map(function ($item) use ($localSymbols) {
                    // Check if the symbol exists in the local symbols table
                    if (isset($localSymbols[$item->Symbol])) {
                        $item->status = ($localSymbols[$item->Symbol]->status == 1) ? 'Enabled' : 'Disabled';
                    } else {
                        $item->status = 'Disabled'; // If not found, it's considered Disabled
                    }
                    return $item;
                });

                if ($request->status == 1) {
                    $combined = $combined->filter(function ($item) {
                        return $item->status == 'Enabled';
                    });
                } elseif ($request->status == 0) {
                    $combined = $combined->filter(function ($item) {
                        return $item->status == 'Disabled';
                    });
                }
            }

            // Step 5: Get Existing Symbols and Pass Them to View
            $existingSymbols = $localSymbols->keys()->toArray();

            // Step 6: Return DataTables Response
            return Datatables::of(collect($combined))
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($existingSymbols) {
                    return view('backend.symbols.include.__action', [
                        'Symbol_ID' => $row->Symbol_ID,
                        'Symbol' => $row->Symbol,
                        'existingSymbols' => $existingSymbols // Pass the variable here
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
