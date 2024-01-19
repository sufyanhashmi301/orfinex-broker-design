<?php

$base_url = 'http://8.208.79.163:6653/api/';
return [
    'createUserUrl' => $base_url.'user/create',
    'updateUserUrl' => $base_url.'user/update',
    'getUserInfoUrl' => $base_url.'user/get',
    'getUserUrl' => $base_url.'useraccount/get',
    'getRoiUrl' => $base_url.'stastistic/roi',
    'depositUrl' => $base_url.'dealer/balance',
    'withdrawUrl' => $base_url.'dealer/balance',
//    'totalHistoryUrl' => $base_url.'total_history',
//    'pageHistoryUrl' => $base_url.'page_history',
//    'pageDealUrl' => $base_url.'page_deal',
    'updateLeverageUrl' => $base_url.'user/updateleverage',
    'updateAgentAccount' => $base_url.'user/updateagentaccount',
    'resetMasterPasswordUrl' => $base_url.'user/resetmasterpassword',
    'resetInvestorPasswordUrl' => $base_url.'user/resetinvestorpassword',
    'disableAccountUrl' => $base_url.'disable_account',
    'enableAccountUrl' => $base_url.'enable_account',
//    'auth' => '49239111601',
    'group' => 'IB\1',
    'server' => 'OrfinexPrime-MT5',
    'tradingPlatform' => 'MT5',
];
