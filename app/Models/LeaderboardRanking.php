<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaderboardRanking extends Model
{
    use HasFactory;

    public function leaderboardRankingsCategory() {
        return $this->belongsTo(leaderboardRankingsCategory::class);
    }
}
