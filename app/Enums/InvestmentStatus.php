<?php


namespace App\Enums;


interface InvestmentStatus
{
    const NONE = 'none';
    const PENDING_NOT_PAID = 'pending_not_paid';
    const PENDING = 'pending';
    const ACTIVE = 'active';
    const PASSED = 'passed';
    const VIOLATED = 'violated';
    const EXPIRED = 'expired';

    const PROMOTION_REQUEST = 'promotion_request';
}
