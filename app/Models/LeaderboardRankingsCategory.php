<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaderboardRankingsCategory extends Model
{
    use HasFactory;

    public function leaderboardRankings(){
        return $this->hasMany(LeaderboardRanking::class);
    }
}
