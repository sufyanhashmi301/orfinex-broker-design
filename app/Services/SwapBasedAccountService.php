<?php

namespace App\Services;

use App\Http\Requests\StoreSwapBasedAccountRequest;
use App\Models\MultiLevel;
use Illuminate\Support\Facades\DB;

class SwapBasedAccountService
{
    public function create(StoreSwapBasedAccountRequest $request)
    {

        return MultiLevel::create($request->all());
    }

    public function update( MultiLevel $swapBasedAccount, array $data)
    {
        $swapBasedAccount->update($data);
        return $swapBasedAccount;
    }

    public function delete(MultiLevel $swapBasedAccount)
    {
        return  $swapBasedAccount->delete();
    }
}
