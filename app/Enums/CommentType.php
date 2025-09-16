<?php

namespace App\Enums;

enum CommentType: string
{
    case Deposit = 'deposit';
    case WithdrawAmount = 'withdraw_amount';
    case WithdrawAccount = 'withdraw_account';
    case KYC = 'kyc';

    public function label(): string
    {
        return match($this) {
            self::Deposit => 'Deposit',
            self::WithdrawAmount => 'Withdraw Funds',
            self::WithdrawAccount => 'Withdraw Account',
            self::KYC => 'KYC',
        };
    }
}


