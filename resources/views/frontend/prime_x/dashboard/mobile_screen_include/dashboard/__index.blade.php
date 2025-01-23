<div class="mb-3">
    <a href="{{ route('user.account.buy') }}" class="btn inline-flex justify-center btn-primary w-full">
        {{ __('Start Challenge') }}
    </a>
</div>
<p class="text-slate-400 dark:text-slate-50 text-sm mb-1">{{ __('Actions') }}</p>
<div class="grid grid-cols-3 gap-2 mob-shortcut-btn mb-3">
    <a href="javascript:;" class="card rounded-md p-4 text-center">
        <div class="mx-auto text-2xl">
            <iconify-icon icon="heroicons-outline:download"></iconify-icon>
        </div>
        <span class="text-sm">{{ __('Payment') }}</span>
    </a>
    <a href="{{ route('user.withdraw.step1') }}" class="card rounded-md p-4 px-2 text-center">
        <div class="mx-auto text-2xl">
            <iconify-icon icon="heroicons-outline:upload"></iconify-icon>
        </div>
        <span class="text-sm">{{ __('Payout') }}</span>
    </a>
    <a href="{{ route('user.leaderboard') }}" class="card rounded-md p-4 px-2 text-center">
        <div class="mx-auto text-2xl">
            <iconify-icon icon="lucide:trophy"></iconify-icon>
        </div>
        <span class="text-sm">{{ __('Leaderboard') }}</span>
    </a>
    <a href="{{ route('user.investments.index') }}" class="card rounded-md p-4 px-2 text-center">
        <div class="mx-auto text-2xl">
            <iconify-icon icon="uil:chart-line"></iconify-icon>
        </div>
        <span class="text-sm">{{ __('Accounts') }}</span>
    </a>
    <a href="{{ route('user.kyc') }}" class="card rounded-md p-4 px-2 text-center">
        <div class="mx-auto text-2xl">
            <iconify-icon icon="mdi:user-check-outline"></iconify-icon>
        </div>
        <span class="text-sm">{{ __('Verification') }}</span>
    </a>
    <a href="{{ route('user.ticket.index') }}" class="card rounded-md p-4 px-2 text-center">
        <div class="mx-auto text-2xl">
            <iconify-icon icon="heroicons-outline:support"></iconify-icon>
        </div>
        <span class="text-sm">{{ __('Support') }}</span>
    </a>
</div>
{{--<div class="space-y-3">--}}
<div>
    <!-- all navigation -->
    {{-- @include('frontend::user.mobile_screen_include.dashboard.__navigations') --}}

    <!-- all Statistic -->
    {{-- @include('frontend::user.mobile_screen_include.dashboard.__statistic') --}}

    <!-- Recent Transactions -->
    @include('frontend::dashboard.mobile_screen_include.dashboard.__transactions')
</div>
