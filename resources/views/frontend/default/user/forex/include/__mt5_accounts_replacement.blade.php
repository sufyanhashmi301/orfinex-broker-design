@php
    $balance = 0;
    $equity = 0;
    $leverage = $account->leverage;

    $mt5Account = DB::connection('mt5_db')
               ->table('mt5_accounts')
               ->where('Login', $account->login)
               ->first();

    if ($mt5Account) {
       $balance = $mt5Account->Balance;
       $equity = $mt5Account->Equity;
       $leverage = $mt5Account->MarginLeverage;
    }

    // Return the values as an array
    return compact('balance', 'equity', 'leverage');
@endphp
