<?php


namespace App\Enums;


interface PricingInvestmentStatus
{
    const PENDING = 'pending';
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';
    const VIOLATED = 'violated';
    const MERGED = 'merged';
    const CONVERT_TO_ORFIN = 'convert_to_orfin';
}
