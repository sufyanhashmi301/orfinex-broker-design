<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaderboardRanking extends Model
{
    use HasFactory;

    protected $fillable = [
        'leaderboard_rankings_category_id',
        'ranking',
        'user_name',
        'profit',
        'equity',
        'account_size',
        'gain',
    ];

    public function leaderboardRankingsCategory() {
        return $this->belongsTo(leaderboardRankingsCategory::class);
    }
}
