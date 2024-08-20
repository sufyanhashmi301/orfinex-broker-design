<?php

namespace App\Services;

use App\Http\Requests\StoreSwapBasedAccountRequest;
use App\Models\MultiLevel;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\AssignOp\Mul;

class SwapBasedAccountService
{
    public function create(StoreSwapBasedAccountRequest $request)
    {

        return MultiLevel::create($request->all());
    }

    public function update( MultiLevel $swapBasedAccount, array $data,$id)
    {
        $swapBasedAccount = MultiLevel::findOrFail($id);
        $swapBasedAccount->update($data);
        return $swapBasedAccount;
    }

    public function delete($id)
    {
        $swapBasedAccount = MultiLevel::findOrFail($id);
          $swapBasedAccount->delete();
        return $swapBasedAccount;
    }
}
