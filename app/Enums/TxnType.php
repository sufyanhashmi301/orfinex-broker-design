<?php

namespace App\Enums;

use App\Console\Commands\MultiIbBonus;

enum TxnType: string
{
    case Deposit = 'deposit';
    case ForexDeposit = 'forex_deposit';
    case DemoDeposit = 'demo_deposit';
    case Subtract = 'subtract';
    case ManualDeposit = 'manual_deposit';
    case SendMoney = 'send_money';
    case SendMoneyInternal = 'send_money_internal';
    case ReceiveMoneyInternal = 'receive_money_internal';
    case Exchange = 'exchange';
    case Referral = 'referral';
    case SignupBonus = 'signup_bonus';
    case Bonus = 'bonus';
    case BonusSubtract = 'bonus_subtract'; 
    case Withdraw = 'withdraw';
    case WithdrawAuto = 'withdraw_auto';
    case ReceiveMoney = 'receive_money';
    case Investment = 'investment';
    case Interest = 'interest';
    case Refund = 'refund';
    case MultiIB = 'multi_ib';
    case IB = 'ib';
    case MultiLevelBonus = 'multi_level_bonus';

    public function label(): string
    {
        return match($this) {
            self::Deposit => 'Deposit',
            self::ForexDeposit => 'Forex Deposit',
            self::DemoDeposit => 'Demo Deposit',
            self::Subtract => 'Subtract',
            self::ManualDeposit => 'Manual Deposit',
            self::SendMoney => 'Send Money',
            self::SendMoneyInternal => 'Send Money Internal',
            self::ReceiveMoneyInternal => 'Receive Money Internal',
            self::Exchange => 'Exchange',
            self::Referral => 'Referral',
            self::SignupBonus => 'Signup Bonus',
            self::Bonus => 'Bonus',
            self::BonusSubtract => 'bonus_subtract',
            self::Withdraw => 'Withdraw',
            self::WithdrawAuto => 'Withdraw Auto',
            self::ReceiveMoney => 'Receive Money',
            self::Investment => 'Investment',
            self::Interest => 'Interest',
            self::Refund => 'Refund',
            self::MultiIB => 'Multi IB',
            self::IB => 'IB',
            self::MultiLevelBonus => 'MultiLevel Bonus',
        };
    }
}
