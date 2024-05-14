<?php


namespace App\Enums;


interface AccountBalanceType
{
    const MAIN = 'main_wallet';
    const INVEST = 'invest_wallet';
    const FUNDED = 'funded';
    const REFERRAL = 'referral_account';

    const TOTAL_OLD_PROFITS = 'total_old_profits';

    const FOREX_TRADING = 'forex_trading';
    const AFFILIATE_WALLET = 'affiliate_wallet';
    const MASTER_AFFILIATE = 'master_affiliate';
    const STRIPE = 'stripe';


    const MAIN_HOLD = 'main_wallet_hold';
    const INVEST_HOLD = 'invest_wallet_hold';
    const SCHEME_PARTNER_BONUS = 'scheme_partner_bonus_wallet';
    const TOKEN_SCHEME_PARTNER_BONUS = 'token_scheme_partner_bonus_wallet';
    const SCHEME_PROFIT_BONUS = 'scheme_profit_bonus_wallet';
    const TOKEN_SCHEME_PROFIT_BONUS = 'token_scheme_profit_bonus_wallet';
    const P2P_WALLET = 'p2p_wallet';
    const TOTAL_OLD_AVAILABLE_BALANCE = 'total_old_available_balance';
    const TOTAL_OLD_INVESTS = 'total_old_invests';
    const TOTAL_OLD_DEPOSITS = 'total_old_deposits';
    const TOTAL_OLD_WITHDRAWALS = 'total_old_withdrawals';
    const TOTAL_OLD_REFERRALS = 'total_old_referrals';
    const REWARD_SALARY = 'reward_salary';

    const ORFIN_MAIN = 'orfin_main_wallet';
    const ORFIN_INVEST = 'orfin_invest_wallet';
    const MASTER_IB = 'master_ib';

}
