<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRebateRuleRequest;
use App\Models\RebateRule;
use App\Models\SymbolGroup;
use App\Services\RebateRuleService;
use Illuminate\Http\Request;
use DataTables;
class RebateRuleController extends Controller
{
    protected $rebateRuleService;

    public function __construct(RebateRuleService $rebateRuleService)
    {
        $this->rebateRuleService = $rebateRuleService;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = RebateRule::with('groups')->latest('updated_at')->get();
    
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('groups', function($row) {
                    return $row->groups->pluck('symbol_group')->implode(', ');
                })
                ->addColumn('status', 'backend.rebate_rules.include.__status')
                ->addColumn('action', 'backend.rebate_rules.include.__action')
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('backend.rebate_rules.all');
    }

    public function create()
    {
        $symbolGroups = SymbolGroup::pluck('symbol_group','id')->toArray();
        return response()->json(['symbolGroups'=>$symbolGroups]);
    }
    public function store(StoreRebateRuleRequest $request)
    {
        try {
            $this->rebateRuleService->createRebateRule($request);
            notify()->success(__('Rebate Rule created successfully.'));
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
   
    public function edit($id)
    {
        $rebateRule = RebateRule::with('groups')->find($id);
        $allSymbolGroups = SymbolGroup::all();
    
        return response()->json([
            'rebateRule' => $rebateRule,
            'allSymbolGroups' => $allSymbolGroups
        ]);
    }
    public function show(RebateRule $rebateRule)
    {
        return response()->json($rebateRule);
    }
    public function update(StoreRebateRuleRequest $request, $id)
    {
        $this->rebateRuleService->updateRebateRule($id, $request);
        notify()->success(__('Rebate Rule updated successfully.'));
        return redirect()->route('admin.rebate-rules.index');
    }
   

    public function destroy(RebateRule $rebateRule)
    {
        $this->rebateRuleService->delete($rebateRule);
        notify()->success(__('Rebate Rule deleted successfully.'));
        return redirect()->route('admin.rebate-rules.index');
    }
}
