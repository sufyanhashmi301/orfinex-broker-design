<?php

//$base_url = 'http://8.217.97.85:7762/api/';
$base_url = 'http://8.208.79.163:6653/api/';

return [
    'createUserUrl' => $base_url.'user/create',
    'updateUserUrl' => $base_url.'user/update',
    'getUserInfoUrl' => $base_url.'user/get',
    'getUserUrl' => $base_url.'useraccount/get',
    'getRoiUrl' => $base_url.'stastistic/roi',
    'getMT5GroupList' => $base_url.'mt5group/list',
    'getPositionList' => $base_url.'position/list/user',
    'getPositionListGroup' => $base_url.'position/list/group',
    'getOrderOpenUser' => $base_url.'order/open/user',
    'getDealListUser' => $base_url.'deal/list/user',
    'getUserAccountBalance' => $base_url.'useraccount/balance',
    'getOrderList' => $base_url.'/order/list/user',
    'depositUrl' => $base_url.'dealer/balance',
    'withdrawUrl' => $base_url.'dealer/balance',
    'dealerCreditUrl' => $base_url.'dealer/credit',
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
