<?php

namespace App\Enums;

enum ReferralType: string
{
    case Investment = 'investment';
    case MultiIB = 'multi_ib';
    case Deposit = 'deposit';
    case Profit = 'profit';
}
