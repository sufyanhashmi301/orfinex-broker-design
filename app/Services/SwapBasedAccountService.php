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
        $multiLevel = MultiLevel::create($request->all());
        $multiLevel->rebateRule()->attach($request->rebate_rules);
    }

    public function update( MultiLevel $swapBasedAccount, array $data,$id)
    {
        $multiLevel = MultiLevel::findOrFail($id);
        $multiLevel->update($data);
//        dd($data['rebate_rules']);
        $multiLevel->rebateRule()->sync($data['rebate_rules']);
    }

    public function delete($id)
    {
        $multiLevel = MultiLevel::findOrFail($id);
        $multiLevel->rebateRule()->detach();
        $multiLevel->delete();
        return $multiLevel;
    }
}
