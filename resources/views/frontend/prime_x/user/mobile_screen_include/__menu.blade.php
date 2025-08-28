<a href="{{ route('user.dashboard') }}" class="{{ isActive('user.dashboard') }}">
    <iconify-icon class="text-xl dark:text-white" icon="lucide:layout-dashboard"></iconify-icon>
</a>
<a href="{{ route('user.deposit.methods') }}" class="{{ isActive('user.deposit*') }}">
    <iconify-icon class="text-xl dark:text-white" icon="lucide:download"></iconify-icon>
</a>
@if(setting('deposit_account_mode', 'features') === 'request_deposit_accounts')
<a href="{{ route('user.payment-deposit') }}" class="{{ isActive('user.payment-deposit*') }}">
    <iconify-icon class="text-xl dark:text-white" icon="lucide:credit-card"></iconify-icon>
</a>
@endif
<a href="{{ route('user.forex-account-logs') }}" class="{{ isActive('user.forex*') }}">
    <iconify-icon class="text-xl dark:text-white" icon="lucide:gift"></iconify-icon>
</a>
<a href="{{ route('user.schema') }}" class="{{ isActive('user.schema*') }}">
    <iconify-icon class="text-xl dark:text-white" icon="lucide:box"></iconify-icon>
</a>
<a href="{{ route('user.setting.profile') }}" class="{{ isActive('user.setting*') }}">
    <iconify-icon class="text-xl dark:text-white" icon="lucide:settings"></iconify-icon>
</a>
