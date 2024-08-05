<?php

namespace App\Services;

use App\Http\Requests\StoreSymbolGroupRequest;
use App\Models\Symbol;
use App\Models\SymbolGroup;
use App\Models\SymbolGroupHasSymbols;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class SymbolGroupService
{
    public function create(StoreSymbolGroupRequest $request)
    {
        try {
            return SymbolGroup::create($request->validated());
        } catch (Exception $e) {
            throw new Exception("Failed to create symbol group: " . $e->getMessage());
        }
    }

    

    public function delete(SymbolGroup $symbolGroup)
    {
        try {
            SymbolGroupHasSymbols::where('symbol_group_id',$symbolGroup->id)->delete();
            return $symbolGroup->delete();
        } catch (Exception $e) {
      
            throw new Exception("Failed to delete symbol group: " . $e->getMessage());
        }
    }
    public function createSymbolGroupWithSymbols($groupName, $symbolIds)
    {
        try {
            DB::transaction(function () use ($groupName, $symbolIds) {
                $symbolGroup = SymbolGroup::create([
                    'symbol_group' => $groupName,
                    'platform_type' => 'mt5'
                ]);
                foreach ($symbolIds as $symbolId) {
                    try {
                        $symbol = Symbol::findOrFail($symbolId);
                        SymbolGroupHasSymbols::create([
                            'symbol_id' => $symbol->id,
                            'symbol_group_id' => $symbolGroup->id,
                            'symbol_name' => $symbol->symbol
                        ]);
                    } catch (ModelNotFoundException $e) {
                        throw new Exception("Symbol with ID {$symbolId} not found.");
                    }
                }
            });
        } catch (Exception $e) {
            throw new Exception("Failed to create symbol group with symbols: " . $e->getMessage());
        }
    }
    public function updateSymbolGroupWithSymbols($id, $name, $symbols)
    {
        try {
            $symbolGroup = SymbolGroup::findOrFail($id);
            $symbolGroup->symbol_group = $name;
            $symbolGroup->save();
            $symbolGroupSymbols = [];
            foreach ($symbols as $symbolId) {
                try {
                    $symbol = Symbol::findOrFail($symbolId);
                    $symbolGroupSymbols[$symbolId] = ['symbol_name' => $symbol->symbol];
                } catch (ModelNotFoundException $e) {
                    throw new Exception("Symbol with ID {$symbolId} not found.");
                }
            }
            $symbolGroup->symbols()->sync($symbolGroupSymbols);
        } catch (Exception $e) {
            throw new Exception("Failed to update symbol group with symbols: " . $e->getMessage());
        }
    }
}