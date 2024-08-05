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

class SymbolGroupController extends Controller
{
    protected $symbolGroupService;

    public function __construct(SymbolGroupService $symbolGroupService)
    {
        $this->symbolGroupService = $symbolGroupService;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SymbolGroup::with('symbols')->latest('updated_at')->get();
    
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('symbols', function($row) {
                    return $row->symbols->pluck('symbol')->implode(', ');
                })
                ->addColumn('action', 'backend.symbol_groups.include.__action')
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.symbol_groups.all');
    }

    public function create()
    {
        $symbols = Symbol::pluck('symbol','id')->toArray();
        return response()->json(['symbols'=>$symbols]);
    }
    public function store(StoreSymbolGroupRequest $request)
    {
        $this->symbolGroupService->createSymbolGroupWithSymbols($request->name, $request->symbols);
        notify()->success(__('Symbol Group updated successfully.'));
        return redirect()->route('admin.symbol-groups.index');
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
   

    public function destroy(SymbolGroup $symbolGroup)
    {
        $this->symbolGroupService->delete($symbolGroup);
        notify()->success(__('Symbol Group deleted successfully.'));
        return redirect()->route('admin.symbol-groups.index');
    }
}
