@php
    $equity=0;
        $account = DB::connection('mt5_db2')
                    ->table('mt5_accounts')
                    ->where('Login', $login)
                    ->first();
    if($account){
        $equity = $account->Equity;
    }

@endphp
<strong
    class="green-color">{{ $equity }}</strong>

