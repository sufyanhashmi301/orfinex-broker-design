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
            'level_id' => 'required|exists:multi_levels,id',
            'share_percentage' => 'required|numeric|min:0|max:100',
            'context' => 'required|in:swap,swapFree'
        ]);

        $user = auth()->user();
        $newShare = $request->input('share_percentage');
        $levelId = $request->input('level_id');
        $context = $request->input('context');

        // Determine the type of table to update (swap or swapFree)
        $type = ($context === 'swap') ? MultiLevelType::SWAP : MultiLevelType::SWAP_FREE;

        // Calculate the current total share for the given context, excluding the level being updated
        $currentTotalShare = UserIbRule::where('user_id', $user->id)
            ->whereHas('multiLevel', function($query) use ($type) {
                $query->where('type', $type);
            })
            ->where('multi_level_id', '!=', $levelId)
            ->sum('share');

        // Check if the new total share would exceed 100%
        if (($currentTotalShare + $newShare) > 100) {
            return response()->json(['success' => false, 'message' => 'Total share percentage across all levels cannot exceed 100%.']);
        }

        // Update or create the specific level share
        $userIbRule = UserIbRule::firstOrCreate(
            [
                'multi_level_id' => $levelId,
                'user_id' => $user->id,
            ],
            [
                'share' => $newShare
            ]
        );

            // Return new total share for the specific context
            return response()->json(['success' => true, 'message' => 'Share percentage updated successfully', 'newTotalShare' => $currentTotalShare + $newShare]);
//        }
//
//        return response()->json(['success' => false, 'message' => 'Unable to update share percentage']);
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
