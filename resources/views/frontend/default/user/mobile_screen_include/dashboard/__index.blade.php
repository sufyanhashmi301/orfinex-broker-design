<div class="user-ranking-mobile flex justify-between items-center p-3 mb-3 rounded-lg bg-[#ff0000]">
    <div class="flex items-center">
        <div class="flex-none">
            <div class="lg:h-10 lg:w-10 h-9 w-9 rounded-full ltr:mr-[10px] rtl:ml-[10px]">
                <img src="{{ asset($user->avatar ?? 'global/materials/user.png') }}" class="block w-full h-full object-cover rounded-full" alt=""/>
            </div>
        </div>
        <div class="flex-1 text-start">
            <h4 class="text-sm font-medium text-slate-50 whitespace-nowrap">
                {{ __('Hi') }}, {{ $user->full_name }}
            </h4>
            <div class="text-xs font-normal text-slate-50 dark:text-slate-50">
                {{ $user->rank->ranking_name }} - <span>{{ $user->rank->ranking }}</span>
            </div>
        </div>
    </div>
    <div class="lg:h-6 lg:w-6 h-6 w-6 rounded-full ltr:mr-[10px] rtl:ml-[10px]">
        <img src="{{ asset( $user->rank->icon) }}" class="block w-full h-full object-cover rounded-full" alt=""/>
    </div>
</div>
<div class="user-wallets-mobile">
    <img src="{{ asset('frontend/materials/wallet-shadow.png') }}" alt="" class="wallet-shadow">
    <div class="head">{{ __('All Wallets in') }} {{ $currency }}</div>
    <div class="one">
        <div class="balance">
            <span class="symbol">{{ $currencySymbol }}</span>
            {{$dataCount['total_forex_balance']}}
        </div>
        <div class="wallet">{{ __('Balance') }}</div>
    </div>
    <div class="one p-wal">
        <div class="balance">
            <span class="symbol">{{ $currencySymbol }}</span>
            {{$dataCount['total_forex_equity']}}
        </div>
        <div class="wallet">{{ __('Equity') }}</div>
    </div>
    <div class="one p-wal">
        <div class="balance">
            <span class="symbol">{{ $currencySymbol }}</span>
            0
        </div>
        <div class="wallet">{{ __('Success Points') }}</div>
    </div>
    <div class="info">
        <i icon-name="info"></i>{{ __('You Earned') }} {{ $dataCount['profit_last_7_days'] }} {{ $currency }} {{ __('This Week') }}
    </div>
</div>

<div class="grid grid-cols-3 gap-2 mob-shortcut-btn mb-3">
    <a href="{{ route('user.deposit.amount') }}" class="bg-info-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-50 text-center">
        <div class="mx-auto text-2xl">
            <iconify-icon icon="lucide:download"></iconify-icon> 
        </div>
        <span class="text-sm">{{ __('Deposit') }}</span>
    </a>
    <a href="{{ route('user.schema') }}" class=" bg-warning-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-50 text-center">
        <div class="mx-auto text-2xl">
            <iconify-icon icon="lucide:box"></iconify-icon>  
        </div>
        <span class="text-sm">{{ __('Investment') }}</span>
    </a>
    <a href="{{ route('user.withdraw.view') }}" class=" bg-success-500 rounded-md p-4 bg-opacity-[0.15] dark:bg-opacity-50 text-center">
        <div class="mx-auto text-2xl">
            <iconify-icon icon="lucide:send"></iconify-icon>  
        </div>
        <span class="text-sm">{{ __('Withdraw') }}</span>
    </a>
</div>

<div class="space-y-3">

    <!-- all navigation -->
    @include('frontend::user.mobile_screen_include.dashboard.__navigations')

    <!-- all Statistic -->
    {{-- @include('frontend::user.mobile_screen_include.dashboard.__statistic') --}}

    <!-- Recent Transactions -->
    @include('frontend::user.mobile_screen_include.dashboard.__transactions')

    <div class="card mobile-ref-url mb-4">
        <div class="card-header">
            <h4 class="card-title">{{ __('Referral URL') }}</h4>
        </div>
        <div class="card-body p-2 py-4 all-feature-mobile">
            <div class="relative mobile-referral-link-form">
                <input type="text" class="form-control" value="{{ $referral->link }}" id="refLink" style="padding-right:55px"/>
                <button type="submit" class="absolute right-0 top-1/2 -translate-y-1/2 h-full bg-dark flex items-center justify-center" onclick="copyRef()">
                    <span id="copy" class="px-2 dark:text-white">{{ __('Copy') }}</span>
                </button>
            </div>
            <p class="referral-joined text-sm dark:text-white">
                {{ $referral->relationships()->count() }} {{ __('peoples are joined by using this URL') }}
            </p>
        </div>
    </div>
</div>
