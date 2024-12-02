<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateRule extends Model
{
    use HasFactory;
    
    public function affiliateRuleConfiguration() {
        return $this->hasMany(AffiliateRuleConfiguration::class);
    }

    public function affiliateRuleLevel() {
        return $this->hasMany(AffiliateRuleLevel::class);
    }
}
