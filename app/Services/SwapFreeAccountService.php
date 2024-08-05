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

    public function update(SwapFreeAccount $swapFreeAccount, array $data)
    {
        $swapFreeAccount->update($data);
        return $swapFreeAccount;
    }

    public function delete(SwapFreeAccount $swapFreeAccount)
    {
       return $swapFreeAccount->delete();
      
    }
}