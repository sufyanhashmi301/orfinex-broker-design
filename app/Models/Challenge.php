<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;
    protected $fillable = [
        'challenge_name',
        'challenge_code',
        'schema_badge',
        'type_of_phases',
        'next_stage_process',
        'max_purchase_limit',
        'refundable_by',
        'daily_risk_track',
        'main_risk_track',
        'random_risk_track',
        'priority_level',
        'affiliate_partner_commission',
        'vacations',
    ];
}