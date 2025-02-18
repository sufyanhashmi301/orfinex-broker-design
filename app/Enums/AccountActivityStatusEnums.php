<?php


namespace App\Enums;


interface AccountActivityStatusEnums
{
  const DAILY_DRAWDOWN_VIOLATION = 'daily_drawdown_violated';
  const MAX_DRAWDOWN_VIOLATION = 'max_drawdown_violated';
  const PASSED = 'passed';
  const ACTIVE = 'active';
  const REJECT = 'reject';
  const VIOLATED = 'violated';
  const ADMIN_APPROVE = 'admin_approve';
  const AUTO_APPROVE = 'auto_approve';
  const PAYMENT_APPROVE = 'payment_approve';
    
}
