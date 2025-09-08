<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSymbolGroupRequest;
use App\Http\Requests\UpdateSymbolGroupRequest;
use App\Models\Symbol;
use App\Models\SymbolGroup;
use App\Services\SymbolGroupService;
use Illuminate\Http\Request;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SymbolGroupsExport;

class SymbolGroupController extends Controller
{
    protected $symbolGroupService;

    public function __construct(SymbolGroupService $symbolGroupService)
    {
        $this->symbolGroupService = $symbolGroupService;
    }

    /**
     * Apply filters to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyFilters($query, Request $request)
    {
        // Apply global search on the group title
        if ($request->filled('global_search')) {
            $search = $request->global_search;
            $query->where('title', 'like', "%{$search}%");
        }

        // Apply filter for associated symbols
        if ($request->filled('symbol_filter')) {
            $symbolSearch = $request->symbol_filter;
            $query->whereHas('symbols', function ($q) use ($symbolSearch) {
                $q->where('symbol', 'like', "%{$symbolSearch}%");
            });
        }
        
        // Apply filter for creation date
        if ($request->filled('created_at_filter')) {
            $query->whereDate('created_at', $request->created_at_filter);
        }

        return $query;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = SymbolGroup::with('symbols')->latest('updated_at');
            
            // Apply all filters
            $this->applyFilters($query, $request);

            $data = $query->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('symbols', function($row) {
                    return view('backend.symbol_groups.include.__symbols', [
                        'symbols' => $row->symbols
                    ])->render();
                })
                ->addColumn('action', 'backend.symbol_groups.include.__action')
                ->rawColumns(['symbols', 'action'])
                ->make(true);
        }
        
        $symbols = Symbol::where('status', true)->get();
        return view('backend.symbol_groups.all', compact('symbols'));
    }

    public function create()
    {
        $symbols = Symbol::pluck('symbol', 'id')->toArray();
        return response()->json(['symbols' => $symbols]);
    }

    public function store(StoreSymbolGroupRequest $request)
    {
        try {
            $this->symbolGroupService->createSymbolGroupWithSymbols($request->name, $request->symbols);
            notify()->success(__('Symbol Group created successfully.'));
            return redirect()->back();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $symbolGroup = SymbolGroup::with('symbols')->find($id);
        $allSymbols = Symbol::all();

        return response()->json([
            'symbolGroup' => $symbolGroup,
            'allSymbols' => $allSymbols
        ]);
    }

    public function show(SymbolGroup $symbolGroup)
    {
        return response()->json($symbolGroup);
    }

    public function update(StoreSymbolGroupRequest $request, $id)
    {
        $this->symbolGroupService->updateSymbolGroupWithSymbols($id, $request->name, $request->symbols);
        notify()->success(__('Symbol Group updated successfully.'));
        return redirect()->route('admin.symbol-groups.index');
    }

    public function destroy(Request $request, SymbolGroup $symbolGroup)
{
    try {
        // Handle the AJAX request to check for attached rebate rules
        if ($request->has('check_rules')) {
            // Use the singular relationship name rebateRule() instead of rebateRules()
            $rebateRules = $symbolGroup->rebateRule()
                ->select('rebate_rules.id', 'rebate_rules.title')
                ->get();
            
            return response()->json([
                'success' => true,
                'rules' => $rebateRules,
                'rule_count' => $rebateRules->count()
            ]);
        }

        // Check for attached rules before actual deletion using the singular relationship
        if ($symbolGroup->rebateRule()->exists()) {
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Cannot delete this symbol group because it is associated with rebate rules.'
                ], 422);
            }
            notify()->error(__('Cannot delete this symbol group. Please detach associated rebate rules first.'));
            return redirect()->back();
        }

        // Proceed with deletion
        $this->symbolGroupService->delete($symbolGroup);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Symbol Group deleted successfully.'
            ]);
        }

        notify()->success(__('Symbol Group deleted successfully.'));
        return redirect()->route('admin.symbol-groups.index');

    } catch (\Exception $e) {
        \Log::error("Symbol Group Deletion Error: " . $e->getMessage());
        
        if ($request->ajax()) {
            return response()->json([
                'error' => 'An error occurred while checking for attached rebate rules.'
            ], 500);
        }

        notify()->error(__('An error occurred. Please try again.'), 'Error');
        return redirect()->back();
    }
}
    public function export(Request $request)
    {
        // Start with the base query
        $query = SymbolGroup::with('symbols');
        
        // Apply the same filters as the DataTable (identical to the applyFilters method)
        if ($request->filled('global_search')) {
            $query->where('title', 'like', '%' . $request->global_search . '%');
        }
        
        if ($request->filled('symbol_filter')) {
            $query->whereHas('symbols', function($q) use ($request) {
                $q->where('symbol', 'like', '%' . $request->symbol_filter . '%');
            });
        }
        
        if ($request->filled('created_at_filter')) {
            $query->whereDate('created_at', $request->created_at_filter);
        }
        
        // Get the filtered results
        $symbolGroups = $query->get();
        
        // Generate file name with timestamp
        $fileName = 'symbol-groups-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
        
        return Excel::download(new SymbolGroupsExport($symbolGroups), $fileName);
    }
}
