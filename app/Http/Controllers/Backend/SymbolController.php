<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\SymbolService;
use App\Exports\SymbolsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
class SymbolController extends Controller
{
    protected $symbolService;

    public function __construct(SymbolService $symbolService)
    {
        $this->symbolService = $symbolService;
    }
    public function index(Request $request)
    {
        $mt5Query = DB::connection('mt5_db')
            ->table('mt5_symbols')
            ->select('Symbol_ID', 'Symbol', 'Path', 'Description', 'ContractSize');

        if ($request->filled('global_search')) {
            $searchTerm = '%' . $request->global_search . '%';
            $mt5Query->where(function ($query) use ($searchTerm) {
                $query->where('Symbol', 'LIKE', $searchTerm)
                    ->orWhere('Description', 'LIKE', $searchTerm)
                    ->orWhere('Path', 'LIKE', $searchTerm);
            });
        }

        if ($request->filled('contact_size')) {
            $mt5Query->where('ContractSize', 'LIKE', '%' . $request->contact_size . '%');
        }

        if ($request->filled('path')) {
            $mt5Query->where('Path', 'LIKE', '%' . $request->path . '%');
        }

        // Paginate while keeping filters
        $mt5Symbols = $mt5Query->paginate(50)->appends($request->query());

        // Fetch local symbols with status
        $localSymbols = DB::table('symbols')
            ->select('symbol', 'status')
            ->whereIn('symbol', $mt5Symbols->pluck('Symbol')->toArray())
            ->get()
            ->keyBy('symbol');

        // Merge status dynamically
        foreach ($mt5Symbols as $symbol) {
            $symbol->status = isset($localSymbols[$symbol->Symbol]) && $localSymbols[$symbol->Symbol]->status == 1
                ? 'Enabled'
                : 'Disabled';
        }

        // Apply Status Filter (After Merging)
        if ($request->filled('status')) {
            $statusFilter = $request->status == "1" ? 'Enabled' : 'Disabled';
            $filteredSymbols = $mt5Symbols->filter(function ($symbol) use ($statusFilter) {
                return $symbol->status === $statusFilter;
            });

            // Convert filtered results back to paginated format
            $mt5Symbols = $this->paginateCollection($filteredSymbols, 50, $request);
        }

        if ($request->ajax()) {
            return response()->json([
                'table' => view('backend.symbols.include.__symbols_table', compact('mt5Symbols'))->render(),
                'pagination' => view('backend.symbols.include.__pagination', compact('mt5Symbols'))->render()
            ]);
        }

        return view('backend.symbols.all', compact('mt5Symbols'));
    }

    /**
     * Convert a Laravel Collection to Pagination (Fixes filter pagination issue)
     */
    private function paginateCollection($items, $perPage, $request)
    {
        $currentPage = Paginator::resolveCurrentPage() ?: 1;
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $currentPageItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->all();

        return new LengthAwarePaginator($currentPageItems, $items->count(), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }




    public function updateStatus(Request $request)
    {
        $result = $this->symbolService->createSymbolFromMt5($request->id);
        return response()->json(['success' => $result['success'], 'message' =>$result['message'] ]);
    }

    public function enableAll()
    {
        $result = $this->symbolService->storeAllSymbolsFromMt5();
        return response()->json(['success' => $result['success'], 'message' =>$result['message'] ]);
    }
    
    public function export(Request $request)
    {
        try {
            return Excel::download(
                new SymbolsExport($request), 
                'symbols_export_' . now()->format('Y-m-d_H-i-s') . '.xlsx'
            );
        } catch (\Exception $e) {
            \Log::error('Export failed: ' . $e->getMessage());
            return back()->with('error', 'Export failed. Please try again.');
        }
    }
    public function checkSymbolGroups(Request $request, $symbolId)
{
    try {
        $symbol = \App\Models\Symbol::findOrFail($symbolId);
        
        // Get symbol groups that contain this symbol using the pivot table
        $symbolGroups = \App\Models\SymbolGroup::whereHas('symbols', function($query) use ($symbolId) {
            $query->where('symbols.id', $symbolId);
        })->select('id', 'title')->get();
            
        return response()->json([
            'success' => true,
            'symbol' => $symbol->symbol, // assuming your symbol model has a 'symbol' field
            'groups' => $symbolGroups,
            'group_count' => $symbolGroups->count()
        ]);
        
    } catch (\Exception $e) {
        \Log::error("Symbol Groups Check Error: " . $e->getMessage());
        return response()->json([
            'error' => 'An error occurred while checking symbol groups.'
        ], 500);
    }
}
}

