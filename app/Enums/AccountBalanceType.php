<?php


namespace App\Enums;


interface AccountBalanceType
{
    const MAIN = 'main_wallet';
    const INVEST = 'invest_wallet';
    const PRICING_INVEST = 'pricing_invest_wallet';
    const REFERRAL = 'referral_account';

    const TOTAL_OLD_PROFITS = 'total_old_profits';

    const FOREX_TRADING = 'forex_trading';
    const AFFILIATE_WALLET = 'affiliate_wallet';
    const MASTER_AFFILIATE = 'master_affiliate';
    const STRIPE = 'stripe';
}
