<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LeaderboardBadge;
use App\Models\LeaderboardRanking;
use App\Models\LeaderboardRankingsCategory;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $badges = LeaderboardBadge::all();
        $rankings_categories = LeaderboardRankingsCategory::all();

        if(!isset($request->category)) {
            $rankings = LeaderboardRanking::all();
        }else{
            $rankings = LeaderboardRanking::where('leaderboard_rankings_category_id', $request->category)->get();
        }

        return view('backend.leaderboard.index', compact('badges', 'rankings_categories', 'rankings'));
    }

    public function userIndex(Request $request) {

        $badges = LeaderboardBadge::all();
        $rankings_categories = LeaderboardRankingsCategory::all();

        if(!isset($request->category)) {
            $rankings = LeaderboardRanking::all();
        }else{
            $rankings = LeaderboardRanking::where('leaderboard_rankings_category_id', $request->category)->get();
        }
      

        return view('frontend::leaderboard.index', compact('badges', 'rankings_categories', 'rankings'));
    }
}
