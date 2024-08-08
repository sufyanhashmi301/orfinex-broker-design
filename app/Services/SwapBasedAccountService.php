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

    public function update( SwapBasedAccount $swapBasedAccount, array $data)
    {
        $swapBasedAccount->update($data);
        return $swapBasedAccount;
    }

    public function delete(SwapBasedAccount $swapBasedAccount)
    {
        return  $swapBasedAccount->delete();
    }
}