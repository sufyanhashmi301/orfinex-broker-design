<?php

namespace App\Http\Controllers;

use App\Models\LeaderboardRanking;
use Illuminate\Http\Request;

class LeaderboardRankingController extends Controller
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
        LeaderboardRanking::create([
            'leaderboard_rankings_category_id' => 0,
            'ranking' => LeaderboardRanking::max('ranking') + 1,
            'user_name' => 'New User',
            'profit' => '0',
            'equity' => '0',
            'account_size' => '0',
            'gain' => '0%',
        ]);
        notify('Leaderboard ranking created successfully!', 'Success');
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        LeaderboardRanking::where('id', $request->id)->update($request->except('_token'));
        notify('Leaderboard ranking updated successfully!', 'Success');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeaderboardRanking  $leaderboardRanking
     * @return \Illuminate\Http\Response
     */
    public function show(LeaderboardRanking $leaderboardRanking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeaderboardRanking  $leaderboardRanking
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaderboardRanking $leaderboardRanking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeaderboardRanking  $leaderboardRanking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaderboardRanking $leaderboardRanking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeaderboardRanking  $leaderboardRanking
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaderboardRanking $leaderboardRanking)
    {
        $leaderboardRanking->delete();
        notify('Leaderboard ranking deleted successfully!', 'Success');
        return redirect()->back();
    }
}
