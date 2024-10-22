<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
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
        $group->risk_book_id = 0;
        $group->group_id = $data->Group_ID;
        $group->group = $data->Group;
        $group->currency = $data->Currency;
        $group->currencyDigits = $data->CurrencyDigits;
        $group->status = 1;
        $group->save();
        return ['success' => true, 'message' => 'Group enabled successfully'];
    }

    public function updateGroupStatus($groupId, $status)
    {
        $group = PlatformGroup::where('group_id', $groupId)->first();
        if (!$group) {
            return ['success' => false, 'message' => 'Group not found'];
        }

        $group->status = $status;
        $group->save();

        $message = $status == 1 ? __('Group enabled successfully') : __('Group disabled successfully');
        return ['success' => true, $message];
    }

}
