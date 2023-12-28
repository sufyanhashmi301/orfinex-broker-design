<?php

$base_url = 'http://8.208.79.163:6653/api/';
return [
    'createUserUrl' => $base_url.'user/create',
    'updateUserUrl' => $base_url.'update_user',
//    'getUserUrl' => $base_url.'user/get',
    'getUserUrl' => $base_url.'useraccount/get',
    'depositUrl' => $base_url.'account_deposit',
    'withdrawUrl' => $base_url.'account_withdraw',
    'totalHistoryUrl' => $base_url.'total_history',
    'pageHistoryUrl' => $base_url.'page_history',
    'pageDealUrl' => $base_url.'page_deal',
    'mainPasswordChangeUrl' => $base_url.'main_password_change',
    'disableAccountUrl' => $base_url.'disable_account',
    'enableAccountUrl' => $base_url.'enable_account',
    'auth' => '49239111601',
    'group' => 'IB\1',
    'server' => 'OrfinexPrime-MT5',
    'tradingPlatform' => 'MT5',
];
