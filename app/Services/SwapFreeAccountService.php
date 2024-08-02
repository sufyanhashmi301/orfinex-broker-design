<?php

namespace App\Services;

use App\Http\Requests\StoreSwapFreeAccountRequest;
use App\Models\SwapFreeAccount;
use App\Models\SwapFreeAccounts;

class SwapFreeAccountService
{
    public function create(StoreSwapFreeAccountRequest $request)
    {
        return SwapFreeAccount::create($request->validated());
    }

    public function update(StoreSwapFreeAccountRequest $request, SwapFreeAccount $swapFreeAccount)
    {
        $swapFreeAccount->update($request->validated());
        return $swapFreeAccount;
    }

    public function delete(SwapFreeAccount $swapFreeAccount)
    {
        $swapFreeAccount->delete();
    }
}