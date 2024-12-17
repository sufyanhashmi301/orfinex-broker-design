@php
    $user = \App\Models\User::find($id);

    $total_funded_accounts = \App\Models\AccountTypeInvestment::where('user_id', $user->id)->whereHas('accountTypePhaseRule.accountTypePhase.accountType', function ($query) {
        $query->where('type', \App\Enums\AccountType::FUNDED);
    })->count();

    echo $total_funded_accounts;
@endphp
