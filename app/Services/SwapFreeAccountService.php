<?php

namespace App\Services;

use App\Http\Requests\StoreSwapFreeAccountRequest;
use App\Models\SwapFreeAccount;
use App\Models\SwapFreeAccounts;
use Illuminate\Support\Facades\DB;

class SwapFreeAccountService
{
    public function create(StoreSwapFreeAccountRequest $request)
    {
        return SwapFreeAccount::create($request->validated());
    }

    public function update(StoreSwapFreeAccountRequest $request, SwapFreeAccount $swapFreeAccount)
    {
        return DB::transaction(function () use ($request, $swapFreeAccount) {
            $newLevelOrder = $request->input('level_order');
            if ($swapFreeAccount->level_order != $newLevelOrder) {
                SwapFreeAccount::where('level_order', $newLevelOrder)
                    ->update(['level_order' => DB::raw('level_order + 1')]);
            }
            $swapFreeAccount->update($request->validated());
            return $swapFreeAccount;
        });
       
    }

    public function delete(SwapFreeAccount $swapFreeAccount)
    {
        return DB::transaction(function () use ($swapFreeAccount) {
            $deletedOrder = $swapFreeAccount->level_order;
            $swapFreeAccount->delete();
            SwapFreeAccount::where('level_order', '>', $deletedOrder)
                ->decrement('level_order');
        });
      
    }
}