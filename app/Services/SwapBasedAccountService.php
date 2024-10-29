<?php

namespace App\Services;

use App\Http\Requests\StoreSwapBasedAccountRequest;
use App\Models\MultiLevel;
use App\Models\IbGroup;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\AssignOp\Mul;

class SwapBasedAccountService
{
    public function create(StoreSwapBasedAccountRequest $request)
    {
        $multiLevel = MultiLevel::create($request->all());
        $multiLevel->rebateRule()->attach($request->rebate_rules);
        if (!isset($request->ib_group_id) || empty($request->ib_group_id)) {
            // Fetch the default IB group
            $defaultIbGroup = IbGroup::where('name', 'Default Group')->first();

            // Attach the default IB group if found
            if ($defaultIbGroup) {
                $multiLevel->ibGroups()->attach($defaultIbGroup->id);
            }
        } else {
            // Attach selected IB groups
            $multiLevel->ibGroups()->attach($request->ib_group_id);
        }
    }

    public function update( MultiLevel $swapBasedAccount, array $data,$id)
    {
        $multiLevel = MultiLevel::findOrFail($id);
        $multiLevel->update($data);
//        dd($data['rebate_rules']);
        $multiLevel->rebateRule()->sync($data['rebate_rules']);
        if (empty($data['ib_group_id'])) {
            $defaultIbGroup = IbGroup::where('name', 'Default Group')->first();
            if ($defaultIbGroup) {
                $multiLevel->ibGroups()->sync([$defaultIbGroup->id]); // Assign only the default group
            }
        } else {
            $multiLevel->ibGroups()->sync($data['ib_group_id']); // Sync the selected IB groups
        }
    }

    public function delete($id)
    {
        $multiLevel = MultiLevel::findOrFail($id);
        $multiLevel->rebateRule()->detach();
        $multiLevel->ibGroups()->detach();
        $multiLevel->delete();
        return $multiLevel;
    }
}
