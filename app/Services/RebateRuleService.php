<?php

namespace App\Services;

use App\Http\Requests\StoreSymbolGroupRequest;
use App\Models\RebateRule;
use App\Models\RebateRuleHasGroups;
use App\Models\Symbol;
use App\Models\SymbolGroup;
use App\Models\SymbolGroupHasSymbols;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class RebateRuleService
{
    public function create(StoreSymbolGroupRequest $request)
    {
        try {
            return RebateRule::create($request->validated());
        } catch (Exception $e) {
            throw new Exception("Failed to create rebate rule: " . $e->getMessage());
        }
    }
    public function createRebateRule($data)
    {
        $symbolGroupIds = $data->symbol_groups;
        $ibGroupIds = $data->ib_groups ?? []; // Allow nullable IB Groups

        try {
            $rebateRule = RebateRule::create([
                'title' => $data->title,
                'rule_type_id' => $data->rule_type_id,
                'rebate_amount' => $data->rebate_amount,
                'per_lot' => $data->per_lot,
                'status' => $data->status,
            ]);
            $rebateRule->symbolGroups()->attach($symbolGroupIds);
            $rebateRule->ibGroups()->attach($ibGroupIds); // Attach IB Groups

            return $rebateRule;
        } catch (Exception $e) {
            throw new Exception("Failed to create rebate rule with symbol and IB groups: " . $e->getMessage());
        }
    }
    public function updateRebateRule($id, $request)
    {
        $symbolGroups = $request->symbol_groups;
        $ibGroups = $request->ib_groups ?? []; // Allow nullable IB Groups

        try {
            $rebateRule = RebateRule::findOrFail($id);
            $rebateRule->update([
                'title' => $request->title,
                'rule_type_id' => $request->rule_type_id,
                'rebate_amount' => $request->rebate_amount,
                'per_lot' => $request->per_lot,
                'status' => $request->status,
            ]);
            $rebateRule->symbolGroups()->sync($symbolGroups);
            $rebateRule->ibGroups()->sync($ibGroups); // Sync IB Groups

            return $rebateRule;
        } catch (Exception $e) {
            throw new Exception("Failed to update rebate rule with symbols and IB groups: " . $e->getMessage());
        }
    }

    public function delete(RebateRule $rebateRule)
    {
        try {
            $rebateRule = RebateRule::find($rebateRule->id);
            $rebateRule->symbolGroups()->detach();
            return $rebateRule->delete();
        } catch (Exception $e) {

            throw new Exception("Failed to delete rebate Rule: " . $e->getMessage());
        }
    }




}
