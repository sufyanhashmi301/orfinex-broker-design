@php
    $equity = 0;
    try {
        // Use helper function which utilizes the resilient MT5DatabaseService
        $equity = get_mt5_account_equity($login ?? 0);
    } catch (\Exception $e) {
        // Log error but don't break the view
        \Log::error('Failed to get MT5 equity in blade template', [
            'login' => $login ?? 'unknown',
            'error' => $e->getMessage()
        ]);
        $equity = 0;
    }
@endphp
<strong class="text-success">{{ number_format($equity, 2) }}</strong>

