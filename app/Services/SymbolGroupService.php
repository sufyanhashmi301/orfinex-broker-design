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
            $symbolGroup = SymbolGroup::find($symbolGroup->id);
            $symbolGroup->symbols()->detach(); // Detach all associated symbols
            return $symbolGroup->delete();


        } catch (Exception $e) {

            throw new Exception("Failed to delete symbol group: " . $e->getMessage());
        }
    }
    public function createSymbolGroupWithSymbols($groupName, $symbolIds)
    {
                $symbolGroup = SymbolGroup::create([
                    'title' => $groupName,
                    'platform_type' => 'mt5'
                ]);
                $symbolGroup->symbols()->attach($symbolIds);

    }
    public function updateSymbolGroupWithSymbols($id, $name, $symbols)
    {
        try {
            $symbolGroup = SymbolGroup::findOrFail($id);
            $symbolGroup->title = $name;
            $symbolGroup->save();
            $symbolGroup->symbols()->sync($symbols);
        } catch (Exception $e) {
            throw new Exception("Failed to update symbol group with symbols: " . $e->getMessage());
        }
    }
}
