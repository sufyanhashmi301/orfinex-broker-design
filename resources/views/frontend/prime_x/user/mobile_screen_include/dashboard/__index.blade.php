<div class="card user-ranking-mobile flex justify-between items-center p-3 mb-3 rounded-lg">
    <div class="flex items-center">
        <div class="flex-none">
            <div class="h-10 w-10 rounded-full flex-1 border-2" style="border-color: #0ebe3b;">
                <img src="@if(auth()->user()->avatar && file_exists('assets/'.auth()->user()->avatar)) {{asset($user->avatar)}} @else {{ asset('frontend/images/all-img/user.png') }}@endif" alt="user" class="block w-full h-full object-cover rounded-full">
            </div>
        </div>
        <div class="flex-1 text-start ml-2">
            <h4 class="text-sm font-medium dark:text-white whitespace-nowrap">
                {{ $user->full_name }}
            </h4>
            <div class="flex items-center text-slate-400 dark:text-slate-50 text-xs font-normal">
                {{ $user->rank->ranking }}
                <iconify-icon class="text-base text-primary ml-1" icon="bxs:badge-check"></iconify-icon>
            </div>
        </div>
    </div>
    <div class="ltr:mr-[10px] rtl:ml-[10px]">
        @auth
            @php
                $userId = auth()->id();
                $notifications = App\Models\Notification::where('for','user')->where('user_id', $userId)->latest()->take(4)->get();
                $totalUnread = App\Models\Notification::where('for','user')->where('user_id', $userId)->where('read', 0)->count();
                $totalCount = App\Models\Notification::where('for','user')->where('user_id', $userId)->get()->count();
            @endphp
            <a href="{{ route($notifications->first()->for.'.notification.all') }}" class="h-[32px] w-[32px] bg-slate-100 dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center">
                <iconify-icon class="animate-tada text-slate-800 dark:text-white text-xl" icon="heroicons-outline:bell"></iconify-icon>
            </a>
        @endauth
    </div>
</div>
<div class="mb-3">
    <a href="{{ route('user.schema') }}" class="btn inline-flex justify-center btn-primary w-full">
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
    <a href="{{ route('user.withdraw.view') }}" class="card rounded-md p-4 px-2 text-center">
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
    <a href="{{ route('user.forex-account-logs') }}" class="card rounded-md p-4 px-2 text-center">
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
    @include('frontend::user.mobile_screen_include.dashboard.__transactions')
</div>
