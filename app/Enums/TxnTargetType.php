<?php

namespace App\Enums;

enum TxnTargetType: string
{

    case ForexDeposit = 'forex_deposit';
    case Wallet = 'wallet';


    public function label(): string
    {
        return match($this) {
            self::ForexDeposit => 'Forex Deposit',
            self::Wallet => 'Wallet',

        };
    }
}
