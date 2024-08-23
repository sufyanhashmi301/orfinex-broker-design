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

        $symbolGroupIds =$data->symbol_groups;

        try {
                $rebateRule = RebateRule::create([
                    'title' => $data->title,
                    'rule_type_id' => $data->rule_type_id,
                    'rebate_amount' => $data->rebate_amount,
                    'per_lot' => $data->per_lot,
                    'status' => $data->status,
                ]);
                $rebateRule->symbolGroups()->attach($symbolGroupIds); // Attach symbol groups to the rebate rule

        } catch (Exception $e) {
            throw new Exception("Failed to create rebate rule with symbol groups: " . $e->getMessage());
        }
    }
    public function updateRebateRule($id, $request)
    {

        $symbolGroups = $request->symbol_groups;
        try {
            $rebateRule = RebateRule::findOrFail($id);
            $rebateRule->title = $request->title;
            $rebateRule->rule_type_id = $request->rule_type_id;
             $rebateRule->rebate_amount = $request->rebate_amount;
             $rebateRule->per_lot = $request->per_lot;
             $rebateRule->status = $request->status;
            $rebateRule->update();
            $rebateRule->symbolGroups()->sync($symbolGroups);
        } catch (Exception $e) {
            throw new Exception("Failed to update rebate Rule with symbols: " . $e->getMessage());
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
