<?php

namespace App\Services;

use App\Http\Requests\StoreSwapBasedAccountRequest;
use App\Models\SwapBasedAccount;

class SwapBasedAccountService
{
    public function create(StoreSwapBasedAccountRequest $request)
    {
        return SwapBasedAccount::create($request->validated());
    }

    public function update(StoreSwapBasedAccountRequest $request, SwapBasedAccount $swapBasedAccount)
    {
        $swapBasedAccount->update($request->validated());
        return $swapBasedAccount;
    }

    public function delete(SwapBasedAccount $swapBasedAccount)
    {
        $swapBasedAccount->delete();
    }
}