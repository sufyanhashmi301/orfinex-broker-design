<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRebateRuleRequest;
use App\Models\ForexSchema;
use App\Models\IbGroup;
use App\Models\MultiLevel;
use App\Models\RebateRule;
use App\Models\SymbolGroup;
use App\Models\UserIbRule;
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
            $data = RebateRule::with(['symbolGroups', 'forexSchemas'])->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('symbolGroups', function($row) {
                    return view('backend.rebate_rules.include.__symbol_groups', [
                        'symbolGroups' => $row->symbolGroups->pluck('title')
                    ])->render();
                })
                ->addColumn('forexSchemas', function($row) {
                    return view('backend.rebate_rules.include.__forex_schemas', [
                        'forexSchemas' => $row->forexSchemas->pluck('title')
                    ])->render();
                })
                ->addColumn('ibGroups', function($row) {
                    return view('backend.rebate_rules.include.__ib_groups', [
                        'ibGroups' => $row->ibGroups->pluck('name')
                    ])->render();
                })
                ->addColumn('status', 'backend.rebate_rules.include.__status')
                ->addColumn('action', 'backend.rebate_rules.include.__action')
                ->rawColumns(['symbolGroups', 'forexSchemas', 'ibGroups', 'status', 'action'])
                ->make(true);
        }
        $ibGroups = IbGroup::pluck('name', 'id')->toArray(); // Fetch IB Groups
        $forexSchemas = ForexSchema::pluck('title', 'id')->toArray(); // Fetch Forex Schemas

        return view('backend.rebate_rules.all',[ 'ibGroups' => $ibGroups,'forexSchemas' => $forexSchemas]);
    }


    public function create()
    {
        $symbolGroups = SymbolGroup::pluck('title', 'id')->toArray();
        $forexSchemas = ForexSchema::pluck('title', 'id')->toArray(); // Fetch Forex Schemas
        return response()->json(['symbolGroups' => $symbolGroups, 'forexSchemas' => $forexSchemas]);
    }

    public function store(StoreRebateRuleRequest $request)
    {
        try {
            $rebateRule = $this->rebateRuleService->createRebateRule($request);
            $rebateRule->forexSchemas()->attach($request->forex_schemas); // Attach Forex Schemas
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function edit($id)
    {
        $rebateRule = RebateRule::with('symbolGroups', 'forexSchemas')->find($id);
        $allSymbolGroups = SymbolGroup::all();
        $allForexSchemas = ForexSchema::pluck('title', 'id')->toArray();

        return view('backend.rebate_rules.include.__edit_form', compact('rebateRule', 'allSymbolGroups', 'allForexSchemas'));
    }

    public function show(RebateRule $rebateRule)
    {
        return response()->json($rebateRule);
    }
    public function update(StoreRebateRuleRequest $request, $id)
    {
//        try {
            $rebateRule = $this->rebateRuleService->updateRebateRule($id, $request);

            // Sync IB Groups with the Rebate Rule
//            $rebateRule->ibGroups()->sync($request->ib_groups);
//            dd($request->ib_groups);
        $rebateRule->forexSchemas()->sync($request->forex_schemas); // Sync Forex Schemas

            // Manage UserIbRule for each synced IB Group
//            foreach ($request->ib_groups as $ibGroupId) {
//                $this->manageUserRebateRulesForIbGroup($ibGroupId, $rebateRule->id);
//            }

            notify()->success(__('Rebate Rule updated successfully.'));
            return redirect()->route('admin.rebate-rules.index');
//        } catch (\Exception $e) {
//            return redirect()->back()->withErrors($e->getMessage());
//        }
    }


    public function destroy(RebateRule $rebateRule)
    {
        if($rebateRule->ibGroups()->count() > 0) {
            notify()->error(__('Sorry,Cannot delete this rebate rule because it is still associated with Ib group. Please detach the levels first'));
            return redirect()->back();
        }
        $this->rebateRuleService->delete($rebateRule);
        notify()->success(__('Rebate Rule deleted successfully.'));
        return redirect()->route('admin.rebate-rules.index');
    }

    public function updateStatus(Request $request)
    {
        $rebateRule = RebateRule::find($request->id);

        if ($rebateRule) {
            $rebateRule->status = $request->status;
            $rebateRule->save();

            // Use the notify helper function
            notify(__('Rebate Rule updated successfully.'));

            // Return JSON response
            return response()->json([
                'success' => true,
                'status' => $request->status,
                'message' => __('Rebate Rule updated successfully.')
            ]);
        }

        // Return JSON response for error
        return response()->json([
            'success' => false,
            'message' => __('Error updating status.')
        ], 404);
    }

    protected function manageUserRebateRulesForIbGroup($ibGroupId, $rebateRuleId)
    {
        $ibGroup = IbGroup::with('users')->find($ibGroupId);

        if (!$ibGroup) {
            return; // Exit if the IB Group doesn't exist
        }

        $users = $ibGroup->users; // Get all users associated with the IB Group
//dd($users);
        foreach ($users as $user) {
            // Remove all UserIbRule entries if the user has no IB Group assigned
            if (is_null($user->ib_group_id)) {
                UserIbRule::where('user_id', $user->id)->delete();
                continue;
            }

            // Ensure the user's IB Group matches the current IB Group
            if ($user->ib_group_id != $ibGroupId) {
                continue; // Skip this user if the group doesn't match
            }

            // Check if UserIbRule already exists
            $existingRule = UserIbRule::where('user_id', $user->id)
                ->where('ib_group_id', $ibGroupId)
                ->where('rebate_rule_id', $rebateRuleId)
                ->first();

            if (!$existingRule) {
                // Add missing UserIbRule
                UserIbRule::create([
                    'user_id' => $user->id,
                    'ib_group_id' => $ibGroupId,
                    'rebate_rule_id' => $rebateRuleId,
                    // Add additional fields if necessary
                ]);
            }
        }

        // Remove extra UserIbRules for this IB Group and users in the group
        UserIbRule::where('ib_group_id', $ibGroupId)
            ->where('rebate_rule_id', $rebateRuleId)
            ->whereIn('user_id', $users->pluck('id')) // Ensure only users in this group are affected
            ->delete();
    }


}
