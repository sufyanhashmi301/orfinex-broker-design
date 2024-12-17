@php
    $user = \App\Models\User::find($id);

    $total_trial_accounts = \App\Models\AccountTypeInvestment::where('user_id', $user->id)->whereHas('accountTypePhaseRule.accountTypePhase.accountType', function ($query) {
        $query->where('type', \App\Enums\AccountType::AUTO_EXPIRE);
    })->count();

    echo $total_trial_accounts;
@endphp
