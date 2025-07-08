<?php

namespace App\Helpers;

use App\Enums\TxnType;

class TxnTypeGroup
{
    public static function incoming(): array
    {
        return [
            TxnType::Deposit->value,
            TxnType::ManualDeposit->value,
            TxnType::VoucherDeposit->value,
            TxnType::IbBonus->value,
            TxnType::DemoDeposit->value,
            TxnType::ReceiveMoney->value,
            TxnType::ReceiveMoneyInternal->value,
            TxnType::Referral->value,
            TxnType::SignupBonus->value,
            TxnType::Bonus->value,
            TxnType::BonusRefund->value,
            TxnType::Refund->value,
        ];
    }

    public static function outgoing(): array
    {
        return [
            TxnType::Withdraw->value,
            TxnType::WithdrawAuto->value,
            TxnType::SendMoney->value,
            TxnType::SendMoneyInternal->value,
            TxnType::Subtract->value,
            TxnType::BonusSubtract->value,
        ];
    }

    public static function listed(): array
    {
        return [
            TxnType::Deposit->value,
            TxnType::Withdraw->value,
            TxnType::IbBonus->value,
            TxnType::VoucherDeposit->value,
        ];
    }
}
