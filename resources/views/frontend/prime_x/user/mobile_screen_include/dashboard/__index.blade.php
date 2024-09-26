<p class="text-slate-400 dark:text-slate-50 text-sm mb-1">{{ __('Actions') }}</p>
<div class="grid grid-cols-3 gap-2 mob-shortcut-btn mb-3">
    <a href="{{ route('user.deposit.amount') }}" class="card rounded-md p-4 px-2 text-center">
        <div class="mx-auto text-2xl">
            <iconify-icon icon="heroicons-outline:download"></iconify-icon>
        </div>
        <span class="text-sm">{{ __('Deposit') }}</span>
    </a>
    <a href="{{ route('user.withdraw.view') }}" class=" card rounded-md p-4 px-2 text-center">
        <div class="mx-auto text-2xl">
            <iconify-icon icon="heroicons-outline:upload"></iconify-icon>
        </div>
        <span class="text-sm">{{ __('Withdraw') }}</span>
    </a>
    <a href="{{ route('user.transfer') }}" class=" card rounded-md p-4 px-2 text-center">
        <div class="mx-auto text-2xl">
            <iconify-icon icon="akar-icons:arrow-repeat"></iconify-icon>
        </div>
        <span class="text-sm">{{ __('Transfer') }}</span>
    </a>
    <a href="{{ route('user.forex-account-logs') }}" class="card rounded-md p-4 px-2 text-center">
        <div class="mx-auto text-2xl">
            <iconify-icon icon="uil:chart-line"></iconify-icon>
        </div>
        <span class="text-sm">{{ __('Accounts') }}</span>
    </a>
    <a href="{{ route('user.kyc') }}" class=" card rounded-md p-4 px-2 text-center">
        <div class="mx-auto text-2xl">
            <iconify-icon icon="mdi:user-check-outline"></iconify-icon>
        </div>
        <span class="text-sm">{{ __('Verification') }}</span>
    </a>
    <a href="{{ route('user.ticket.index') }}" class=" card rounded-md p-4 px-2 text-center">
        <div class="mx-auto text-2xl">
            <iconify-icon icon="heroicons-outline:support"></iconify-icon>
        </div>
        <span class="text-sm">{{ __('Support') }}</span>
    </a>
</div>

<!-- Recent Transactions -->
@include('frontend::user.mobile_screen_include.dashboard.__transactions')

{{--<div class="space-y-3">

    <!-- all navigation -->
    @include('frontend::user.mobile_screen_include.dashboard.__navigations')

    <!-- all Statistic -->
    @include('frontend::user.mobile_screen_include.dashboard.__statistic')

</div>--}}
