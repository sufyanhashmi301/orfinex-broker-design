<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralRelationship extends Model
{
    use HasFactory;

    protected $fillable = ['referral_link_id', 'user_id', 'multi_level_id'];

    public function referralLink()
    {
        return $this->belongsTo(ReferralLink::class, 'referral_link_id');
    }
    public function multiLevel()
    {
        return $this->belongsTo(MultiLevel::class, 'multi_level_id');
    }
}
