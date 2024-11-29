<?php

namespace App\Http\Controllers;

use App\Enums\MultiLevelType;
use App\Models\UserIbRule;
use Illuminate\Http\Request;

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
    public function edit($id)
    {
        //
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
