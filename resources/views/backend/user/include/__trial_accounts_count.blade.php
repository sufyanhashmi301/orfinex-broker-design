@php
    $user = \App\Models\User::find($id);

    $total_trial_accounts = \App\Models\AccountTypeInvestment::where('user_id', $user->id)->where('is_trial', 1)->count();

    echo $total_trial_accounts;
@endphp
