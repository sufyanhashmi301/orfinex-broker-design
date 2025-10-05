@php
    $balance = 0;
    try {
        // Use helper function which utilizes the resilient MT5DatabaseService
        $balance = get_mt5_account_balance($account->login ?? 0);
    } catch (\Exception $e) {
        // Log error but don't break the view
    \Log::error('Failed to get MT5 balance in blade template', [
        'login' => $account->login ?? 'unknown',
        'error' => $e->getMessage(),
    ]);
    $balance = 0;
}

// Determine currency display based on cent account
$currencyDisplay = $account->schema->is_cent_account
    ? $account->currency . ' (Cents)'
    : $account->currency ?? 'USD';
@endphp
<strong class="green-color">{{ number_format($balance, 2) . ' ' . $currencyDisplay }}</strong>

{{-- <a class="link-btn link-underline link-underline-opacity-0 text-dark" href="{{ route('admin.user.edit',$user->id) }}"> --}}
{{--    <div class="d-flex align-items-center"> --}}
{{--        <span class="avatar-text">NA</span> --}}
{{--        <div class="ms-2"> --}}
{{--            <span class="d-block lh-1 mb-1 fw-bold">{{ safe($user->username) }}</span> --}}
{{--            <span class="d-block lh-1 small">{{$user->email}}</span> --}}
{{--        </div> --}}
{{--    </div> --}}
{{-- </a> --}}
