<p class="text-gray-400 dark:text-gray-50 text-sm mb-1">{{ __('Actions') }}</p>
<div class="grid grid-cols-3 gap-2 mob-shortcut-btn mb-3">
    <a href="{{ route('user.deposit.methods') }}" class="flex flex-col items-center justify-center gap-3 rounded-xl border border-gray-200 bg-white p-4 px-2 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
        <i data-lucide="download" class="w-6 h-6"></i>
        <span class="text-sm">{{ __('Deposit') }}</span>
    </a>
    <a href="{{ route('user.withdraw.view') }}" class="flex flex-col items-center justify-center gap-3 rounded-xl border border-gray-200 bg-white p-4 px-2 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
        <i data-lucide="upload" class="w-6 h-6"></i>
        <span class="text-sm">{{ __('Withdraw') }}</span>
    </a>
    <a href="{{ route('user.transfer') }}" class="flex flex-col items-center justify-center gap-3 rounded-xl border border-gray-200 bg-white p-4 px-2 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
        <i data-lucide="arrow-right-left" class="w-6 h-6"></i>
        <span class="text-sm">{{ __('Transfer') }}</span>
    </a>
    <a href="{{ route('user.forex-account-logs') }}" class="flex flex-col items-center justify-center gap-3 rounded-xl border border-gray-200 bg-white p-4 px-2 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
        <i data-lucide="activity" class="w-6 h-6"></i>
        <span class="text-sm">{{ __('Accounts') }}</span>
    </a>
    <a href="{{ route('user.kyc') }}" class="flex flex-col items-center justify-center gap-3 rounded-xl border border-gray-200 bg-white p-4 px-2 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
        <i data-lucide="user-check" class="w-6 h-6"></i>
        <span class="text-sm">{{ __('Verification') }}</span>
    </a>
    @if(setting('user_tickets_feature', 'customer_permission'))
        <a href="{{ route('user.ticket.index') }}" class="flex flex-col items-center justify-center gap-3 rounded-xl border border-gray-200 bg-white p-4 px-2 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
            <i data-lucide="life-buoy" class="w-6 h-6"></i>
            <span class="text-sm">{{ __('Support') }}</span>
        </a>
    @endif
</div>

<!-- Recent Transactions -->
@include('frontend::user.mobile_screen_include.dashboard.__transactions')

{{--<div class="space-y-3">

    <!-- all navigation -->
    @include('frontend::user.mobile_screen_include.dashboard.__navigations')

    <!-- all Statistic -->
    @include('frontend::user.mobile_screen_include.dashboard.__statistic')

</div>--}}
