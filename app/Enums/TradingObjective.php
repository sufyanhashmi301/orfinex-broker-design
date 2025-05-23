<?php


namespace App\Enums;


interface TradingObjective
{
    const ONGOING = 'ongoing';
    const PASSED = 'passed';
    const VIOLATED = 'violated';
    const DD_VIOLATED = 'daily_drawdown_violated';
    const MD_VIOLATED = 'max_drawdown_violated';
}
