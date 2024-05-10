<?php


namespace App\Enums;


interface TransactionType
{
    const MAIN_WALLET_TRANSFER = 'main_wallet_transfer';
    const BONUS = 'bonus';
    const CHARGE = 'charge';
    const DEPOSIT = 'deposit';
    const WITHDRAW = 'withdraw';
    const INVESTMENT = 'investment';


    const FOREX_TRADING = 'forex_trading';
    const FOREX_TRADING_WALLET_TRANSFER = 'forex_trading_wallet_transfer';
    const FOREX_TRADING_DEPOSIT = 'forex_trading_deposit';
    const FOREX_TRADING_DEPOSIT_DIRECT = 'forex_trading_deposit_direct';
    const FOREX_TRADING_WITHDRAW = 'forex_trading_withdraw';
    const FOREX_TRADING_WITHDRAW_DIRECT = 'forex_trading_withdraw_direct';

    const REFERRAL = 'referral';
    const MASTER_AFFILIATE = 'master_affiliate';
    const PRICING_INVESTMENT = 'pricing_investment';
    const AFFILIATE_BONUS = 'affiliate_bonus';
    const FUNDED_PAYOUT = 'funded_payout';

}
