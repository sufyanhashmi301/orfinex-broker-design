<?php

namespace App\Enums;

enum TxnType: string
{
    case Deposit = 'deposit';
    case DemoDeposit = 'demo_deposit';
    case Subtract = 'subtract';
    case ManualDeposit = 'manual_deposit';
    case SendMoney = 'send_money';
    case ReceiveMoney = 'receive_money';
    case SendMoneyInternal = 'send_money_internal';
    case ReceiveMoneyInternal = 'receive_money_internal';
    case Exchange = 'exchange';
    case Referral = 'referral';
    case SignupBonus = 'signup_bonus';
    case Bonus = 'bonus';
    case BonusSubtract = 'bonus_subtract';
    case BonusRefund = 'bonus_refund';
    case Withdraw = 'withdraw';
    case WithdrawAuto = 'withdraw_auto';
    case Interest = 'interest';
    case Refund = 'refund';
    case IbBonus = 'ib_bonus';
    case MultiIB = 'multi_ib';
    case IB = 'ib';
    case VoucherDeposit = 'voucher_deposit';

    public function label(): string
    {
        return match($this) {
            self::Deposit => 'Deposit',
            self::DemoDeposit => 'Demo Deposit',
            self::Subtract => 'Subtract',
            self::ManualDeposit => 'Manual Deposit',
            self::SendMoney => 'External Send Money',
            self::ReceiveMoney => 'External Receive Money',
            self::SendMoneyInternal => 'Internal Send Money',
            self::ReceiveMoneyInternal => 'Internal Receive Money',
            self::Exchange => 'Exchange',
            self::Referral => 'Referral',
            self::SignupBonus => 'Signup Bonus',
            self::Bonus => 'Bonus',
            self::BonusSubtract => 'Bonus Subtract',
            self::BonusRefund => 'Bonus Refund',
            self::Withdraw => 'Withdraw',
            self::WithdrawAuto => 'Withdraw Auto',
            self::Interest => 'Interest',
            self::Refund => 'Refund',
            self::MultiIB => 'Multi IB',
            self::IB => 'IB',
            self::IbBonus => 'IB Bonus',
            self::VoucherDeposit => 'Voucher Deposit',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::Deposit => 'Funds deposited into the user\'s account.',
            self::DemoDeposit => 'Virtual funds added in a demo account.',
            self::Subtract => 'Manual subtraction of funds by admin.',
            self::ManualDeposit => 'Manual deposit approved by admin or operator.',
            self::SendMoney => 'Money sent to an external user or account.',
            self::ReceiveMoney => 'Money received from an external user or account.',
            self::SendMoneyInternal => 'Transfer sent to another user within the platform.',
            self::ReceiveMoneyInternal => 'Transfer received from another user within the platform.',
            self::Exchange => 'Currency exchanged between wallets or accounts.',
            self::Referral => 'Commission earned from a referral action.',
            self::SignupBonus => 'Bonus given on new account registration.',
            self::Bonus => 'General-purpose bonus added to account.',
            self::BonusSubtract => 'Reversal or deduction of a previously granted bonus.',
            self::BonusRefund => 'Refund issued from a bonus reversal or adjustment.',
            self::Withdraw => 'User-initiated withdrawal request.',
            self::WithdrawAuto => 'Automatically processed withdrawal.',
            self::Interest => 'Interest credited from an investment or deposit.',
            self::Refund => 'Return of funds to the user due to cancellation or error.',
            self::IbBonus => 'Bonus for introducing broker (IB) activity.',
            self::MultiIB => 'Commission payout from multiple IB levels.',
            self::IB => 'Commission earned by an introducing broker.',
            self::VoucherDeposit => 'Funds added using a deposit voucher or code.',
        };
    }
}

