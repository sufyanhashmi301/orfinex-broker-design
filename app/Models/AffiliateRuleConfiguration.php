<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateRuleConfiguration extends Model
{
    use HasFactory;

    public function affiliateRule() {
        return $this->belongsTo(AffiliateRule::class);
    }
}
