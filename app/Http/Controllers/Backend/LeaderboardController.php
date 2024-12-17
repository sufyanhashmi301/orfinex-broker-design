<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\LeaderboardBadge;
use App\Models\LeaderboardRanking;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Models\LeaderboardRankingsCategory;

class LeaderboardController extends Controller
{
    public function runSeeder() {
        Artisan::call('db:seed', [
            '--class' => 'LeaderboardBadgesSeeder' 
        ]);
    }

    public function index(Request $request)
    {
        $badges = LeaderboardBadge::all();

        // Automatically run Seeder if table is empty
        if(count($badges) == 0) {
            $this->runSeeder();
            $badges = LeaderboardBadge::all();
        }

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

        // Automatically run Seeder if table is empty
        if(count($badges) == 0) {
            $this->runSeeder();
            $badges = LeaderboardBadge::all();
        }

        $rankings_categories = LeaderboardRankingsCategory::all();

        if(!isset($request->category)) {
            $rankings = LeaderboardRanking::all();
        }else{
            $rankings = LeaderboardRanking::where('leaderboard_rankings_category_id', $request->category)->get();
        }
      

        return view('frontend::leaderboard.index', compact('badges', 'rankings_categories', 'rankings'));
    }
}
