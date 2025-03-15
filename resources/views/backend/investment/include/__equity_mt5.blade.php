@php
    $equity=0;
        $account = DB::connection('mt5_db')
                    ->table('mt5_accounts')
                    ->where('Login', $login)
                    ->first();
    if($account){
        $equity = $account->Equity;
    }

@endphp
<strong class="text-success">{{ $equity }}</strong>

