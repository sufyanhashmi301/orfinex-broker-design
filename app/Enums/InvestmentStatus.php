<?php


namespace App\Enums;


interface InvestmentStatus
{
    const PENDING = 'pending';
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';
    const MERGED = 'merged';
    const CONVERT_TO_ORFIN = 'convert_to_orfin';
}
