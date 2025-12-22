<?php

namespace App\Http\Controllers;

use App\Enums\MultiLevelType;
use App\Models\Level;
use App\Models\UserIbRule;
use App\Models\UserIbRuleLevel;
use App\Models\UserIbRuleLevelShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ActivityLogService;

class UserIbRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:user_ib_rules,id',
            'sub_ib_share' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
        $newShareAmount = $request->input('sub_ib_share');
        $ruleId = $request->input('id');

        // Fetch the UserIbRule with its associated RebateRule
        $userIbRule = UserIbRule::with('rebateRule')->findOrFail($ruleId);
        $rebateAmount = $userIbRule->rebateRule->rebate_amount;

        // Validate that sub_ib_share does not exceed rebate_amount
        if ($newShareAmount > $rebateAmount) {
            return response()->json([
                'success' => false,
                'message' => "The Sub IB Share amount cannot exceed the rebate amount of $rebateAmount."
            ]);
        }

        // Update the sub_ib_share amount
        $userIbRule->update([
            'sub_ib_share' => $newShareAmount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sub IB Share amount updated successfully.',
        ]);
    }

    public function updateLevels(Request $request)
    {
        $userIbRuleId = $request->input('user_ib_rule_id');
        $shares = $request->input('shares', []);
        $totalShare = UserIbRule::findOrFail($userIbRuleId)->rebateRule->rebate_amount;
        $errors = [];

        // Validate each level’s total independently
        foreach ($shares as $levelId => $levelShares) {
            $sumShares = collect($levelShares)->sum();
            if ($sumShares > $totalShare) {
                $errors[$levelId] = "Total share for Level $levelId cannot exceed $totalShare.";
            }
        }

        if (!empty($errors)) {
            return response()->json(['success' => false, 'errors' => $errors]);
        }

        // Store shares ensuring no empty values are saved
        foreach ($shares as $levelOrder => $levelShares) {
            $userIbRuleLevel = UserIbRuleLevel::firstOrCreate([
                'user_ib_rule_id' => $userIbRuleId,
                'level_id' => $levelOrder,
            ]);

            foreach ($levelShares as $subLevelOrder => $shareValue) {
                if ($shareValue === null || $shareValue === '') {
                    continue; // Skip empty values
                }

                UserIbRuleLevelShare::updateOrCreate(
                    [
                        'user_ib_rule_level_id' => $userIbRuleLevel->id,
                        'level_id' => $subLevelOrder,
                    ],
                    [
                        'share' => $shareValue,
                    ]
                );
            }
        }

        ActivityLogService::log('ib_rule_update', "Updated IB level shares", [
            'User Ib Rule ID' => $userIbRuleId,
            'Shares' => $shares,
        ]);

        return response()->json(['success' => true, 'message' => 'Shares updated successfully!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showLevels($id)
    {
        $userIbRule = UserIbRule::with('rebateRule')->findOrFail($id);
//        dd($userIbRule->rebateRule->rebate_amount);

        // Fetch all levels dynamically
        $levels = Level::orderBy('level_order', 'asc')->get();

        // Fetch UserIbRuleLevels related to this UserIbRule
        $userIbRuleLevels = UserIbRuleLevel::where('user_ib_rule_id', $id)->with('userIbRuleLevelShares')->get();

        // Pass the data to the view
        return view('frontend::partner.level_listing', compact('userIbRule', 'levels', 'userIbRuleLevels'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
