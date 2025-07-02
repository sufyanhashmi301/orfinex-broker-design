@if(setting('is_mobile_dashboard_quick_link', 'user_dashboard'))
    <p class="text-slate-400 dark:text-slate-50 text-sm mb-1">{{ __('Actions') }}</p>
    <div class="flex flex-wrap gap-2 mob-shortcut-btn mb-3">
        <a href="{{ route('user.deposit.methods') }}" class="flex-1 min-w-1/4 card rounded-md p-4 px-2 text-center dark:text-white">
            <div class="mx-auto text-2xl">
                <iconify-icon icon="heroicons-outline:download"></iconify-icon>
            </div>
            <span class="text-sm">{{ __('Deposit') }}</span>
        </a>
        <a href="{{ route('user.withdraw.view') }}" class="flex-1 min-w-1/4 card rounded-md p-4 px-2 text-center dark:text-white">
            <div class="mx-auto text-2xl">
                <iconify-icon icon="heroicons-outline:upload"></iconify-icon>
            </div>
            <span class="text-sm">{{ __('Withdraw') }}</span>
        </a>
        <a href="{{ route('user.transfer') }}" class="flex-1 min-w-1/4 card rounded-md p-4 px-2 text-center dark:text-white">
            <div class="mx-auto text-2xl">
                <iconify-icon icon="akar-icons:arrow-repeat"></iconify-icon>
            </div>
            <span class="text-sm">{{ __('Transfer') }}</span>
        </a>
        <a href="{{ route('user.forex-account-logs') }}" class="flex-1 min-w-1/4 card rounded-md p-4 px-2 text-center dark:text-white">
            <div class="mx-auto text-2xl">
                <iconify-icon icon="uil:chart-line"></iconify-icon>
            </div>
            <span class="text-sm">{{ __('Accounts') }}</span>
        </a>
        <a href="{{ route('user.kyc') }}" class="flex-1 min-w-1/4 card rounded-md p-4 px-2 text-center dark:text-white">
            <div class="mx-auto text-2xl">
                <iconify-icon icon="mdi:user-check-outline"></iconify-icon>
            </div>
            <span class="text-sm">{{ __('Verification') }}</span>
        </a>
        @if(setting('user_tickets_feature', 'customer_permission'))
            <a href="{{ route('user.ticket.index') }}" class="flex-1 min-w-1/4 card rounded-md p-4 px-2 text-center dark:text-white">
                <div class="mx-auto text-2xl">
                    <iconify-icon icon="heroicons-outline:support"></iconify-icon>
                </div>
                <span class="text-sm">{{ __('Support') }}</span>
            </a>
        @endif
    </div>
@endif
<!-- Recent Transactions -->
@include('frontend::user.mobile_screen_include.dashboard.__transactions')

{{--<div class="space-y-3">

    <!-- all navigation -->
    @include('frontend::user.mobile_screen_include.dashboard.__navigations')

    <!-- all Statistic -->
    @include('frontend::user.mobile_screen_include.dashboard.__statistic')

</div>--}}
