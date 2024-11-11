<?php


namespace App\Enums;


interface InvestmentStatus
{
    const NONE = 'none';
    const PENDING = 'pending';
    const ACTIVE = 'active';
    const PASSED = 'passed';
    const VIOLATED = 'violated';

    const PROMOTION_REQUEST = 'promotion_request';
}
