<?php

namespace App\Enums;

use App\Console\Commands\MultiIbBonus;

enum TxnType: string
{
    case Deposit = 'deposit';
    case ForexDeposit = 'forex_deposit';
    case Subtract = 'subtract';
    case ManualDeposit = 'manual_deposit';
    case SendMoney = 'send_money';
    case SendMoneyInternal = 'send_money_internal';
    case Exchange = 'exchange';
    case Referral = 'referral';
    case SignupBonus = 'signup_bonus';
    case Bonus = 'bonus';
    case Withdraw = 'withdraw';
    case WithdrawAuto = 'withdraw_auto';
    case ReceiveMoney = 'receive_money';
    case Investment = 'investment';
    case Interest = 'interest';
    case Refund = 'refund';
    case MultiIB = 'multi_ib';
    case IB = 'ib';

}
