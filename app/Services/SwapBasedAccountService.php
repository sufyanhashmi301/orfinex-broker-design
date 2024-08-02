<?php

namespace App\Services;

use App\Http\Requests\StoreSwapBasedAccountRequest;
use App\Models\SwapBasedAccount;
use Illuminate\Support\Facades\DB;

class SwapBasedAccountService
{
    public function create(StoreSwapBasedAccountRequest $request)
    {
        return SwapBasedAccount::create($request->validated());
    }

    public function update(StoreSwapBasedAccountRequest $request, SwapBasedAccount $swapBasedAccount)
    {
        return DB::transaction(function () use ($request, $swapBasedAccount) {
            $newLevelOrder = $request->input('level_order');
            if ($swapBasedAccount->level_order != $newLevelOrder) {
                SwapBasedAccount::where('level_order', $newLevelOrder)
                    ->update(['level_order' => DB::raw('level_order + 1')]);
            }
            $swapBasedAccount->update($request->validated());
            return $swapBasedAccount;
        });
    }

    public function delete(SwapBasedAccount $swapBasedAccount)
    {
        return DB::transaction(function () use ($swapBasedAccount) {
            $deletedOrder = $swapBasedAccount->level_order;
            $swapBasedAccount->delete();
            SwapBasedAccount::where('level_order', '>', $deletedOrder)
                ->decrement('level_order');
        });
       
    }
}