<?php

namespace App\Enums;

interface LedgerTnxType
{
    const PROFIT = 'profit';
    const INVEST = 'invest';
    const TOKEN_INVEST = 'token_invest';
    const TRANSFER = 'transfer';
    const CAPITAL = 'capital';
}
