<?php

namespace App\Services;

use App\Http\Requests\StorePlatformGroupRequest;
use App\Models\PlatformGroup;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Request;

class PlatformGroupService
{

    public function createGroupFromMt5($groupId)
    {
        $data = DB::connection('mt5_db')
            ->table('mt5_groups')
            ->select('Group_ID', 'Group', 'Currency', 'CurrencyDigits')
            ->where('Group_ID', $groupId)
            ->first();

        if (!$data) {
            return ['success' => false, 'message' => 'Groups not found in MT5'];
        }

        $group = new PlatformGroup();
        $group->group_id = $data->Group_ID;
        $group->group = $data->Group;
        $group->currency = $data->Currency;
        $group->currencyDigits = $data->CurrencyDigits;
        $group->status = 1;
        $group->save();
        notify()->success(__('Group enabled successfully'));
        return ['success' => true];
    }
}
