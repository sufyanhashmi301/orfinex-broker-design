@php
    $user = \App\Models\User::find($id);

    $total_challenge_accounts = \App\Models\AccountTypeInvestment::where('user_id', $user->id)->whereHas('accountTypePhaseRule.accountTypePhase.accountType', function ($query) {
        $query->where('type', \App\Enums\AccountType::CHALLENGE);
    })->count();

    echo $total_challenge_accounts;
@endphp
