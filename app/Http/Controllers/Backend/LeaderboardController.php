<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LeaderboardBadge;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        $badges = LeaderboardBadge::all();

        return view('backend.leaderboard.index', compact('badges'));
    }
}
