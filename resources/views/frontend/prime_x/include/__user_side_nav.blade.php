@php
    $isUser = isset(auth()->user()->first_name) ? true : false;
@endphp

<div id="bodyOverlay" class="w-screen h-screen fixed top-0 bg-slate-900 bg-opacity-50 backdrop-blur-sm z-10 hidden"></div>
<div class="logo-segment flex-wrap">
    <a href="{{ route('home') }}" class="loaderBtn items-center md:flex hidden">
        <img src="{{ asset(setting('site_logo', 'global')) }}" class="black_logo max-w-[160px]" alt="{{ __('Dark Logo') }}"/>
        <img src="{{ asset(setting('site_logo_light', 'global')) }}" class="white_logo max-w-[160px]" alt="{{ __('Light Logo') }}"/>
    </a>

    <div class="flex itemx-center justify-between md:hidden" style="width: 80%; overflow-x: hidden;">
        <div class="flex items-center">
            <div class="flex-none">
                <div class="w-8 h-8 rounded-[100%] ltr:mr-2 rtl:ml-2">
                    <img src="{{ asset('frontend/images/all-img/user.png') }}" alt="{{ __('User Profile') }}" class="w-full h-full rounded-[100%] object-cover">
                </div>
            </div>
            <div class="flex-1 text-start mobileUserInfo">
                <h4 class="text-sm font-medium whitespace-nowrap header-text-color">
                    {{ auth()->user()->full_name }}
                </h4>
                <span class="flex items-center text-slate-400 text-xs font-normal">    
                    @if(isset($user->kyc) && $user->kyc->status == \App\Enums\KycStatusEnums::VERIFIED)
                        {{ __('Verified') }}
                        <img src="https://cdn.brokeret.com/web/icons/yes-tick.svg" class="ml-1" alt="" style="height: 14px;">
                    @else
                        {{ __('Unverified') }}
                        <img src="https://cdn.brokeret.com/web/icons/no-tick.svg" class="ml-1" alt="" style="height: 14px;">
                    @endif
                </span>
            </div>
        </div>

    </div>
    <button class="sidebarCloseIcon text-2xl header-text-color">
        <iconify-icon icon="clarity:window-close-line"></iconify-icon>
    </button>
</div>
<div id="nav_shadow" class="nav_shadow h-[60px] absolute top-[80px] nav-shadow z-[1] w-full transition-all duration-200 pointer-events-none opacity-0"></div>
<div class="sidebar-menus bg-white dark:bg-body py-2 px-4 h-[calc(100%-96px)] overflow-y-auto z-50" id="sidebar_menus">
    <ul class="sidebar-menu mt-3">
        <li>
            <a href="{{route('user.dashboard')}}" class="navItem {{ isActive('user.dashboard') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:layout-dashboard"></iconify-icon>
                    <span>{{ __('Dashboard') }}</span>
                </span>
            </a>
        </li>

        
        


        {{-- <li>

            <a href="{{ route('user.wallet.index') }}" class="navItem loaderBtn {{ isActive('user.wallet*') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="solar:wallet-linear"></iconify-icon>
                    <span>{{ __('Wallets') }}</span>
                </span>
            </a>
        </li> --}}

        <li>
            <a href="{{ route('user.investments.index', ['status' => 'active']) }}" class="navItem {{ isActive('user.investments.index*') }} {{ isActive('user.investment.trading-stats') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:chart-candlestick"></iconify-icon>
                    <span>{{ __('Accounts') }}</span>
                </span>
            </a>
        </li>

        {{-- @if(setting('is_webterminal','global'))
            <li>
                <a href="{{ route('webterminal') }}" class="navItem {{ isActive('webterminal') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="tabler:chart-candle"></iconify-icon>
                    <span>{{ __('Trading Platform') }}</span>
                </span>
                </a>
            </li>
        @endif --}}

        {{-- <li>
            <a href="{{route('user.account.buy')}}" class="navItem {{ isActive('user.account*') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:square-plus"></iconify-icon>
                    <span>{{ __('New Account') }}</span>
                </span>
            </a>
        </li> --}}

        @if (show_kyc_notice()['show'])
            <li>
                <a href="{{ route('user.verification.index') }}" class="navItem {{ isActive('user.verification.index') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:lock-open"></iconify-icon>
                    <span>{{ __('Verification') }}</span>
                </span>
                </a>
            </li>    
        @endif
        
 

        {{--<li>
            <a href="{{ route('user.deposit.amount') }}"
               class="navItem @if( Route::currentRouteName() != 'user.deposit.log') {{ isActive('user.deposit*') }} @endif">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="heroicons-outline:download"></iconify-icon>
                    <span>{{ __('Payments') }}</span>
                </span>
            </a>
        </li>--}}

        <li>
            <a href="{{ route('user.withdraw.step1') }}" class="navItem @if( Route::currentRouteName() != 'user.withdraw.log') {{ isActive('user.withdraw*') }} @endif">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:hand-coins"></iconify-icon>
                    <span>{{ __('Payout') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.billing.index') }}" class="navItem {{ isActive('user.billing.index') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:receipt-text"></iconify-icon>
                    <span>{{ __('Billing') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.certificates.index') }}" class="navItem {{ isActive('user.certificates.index') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:file-badge"></iconify-icon>
                    <span>{{ __('Certificates') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.contracts') }}" class="navItem {{ isActive('user.contracts') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:file-text"></iconify-icon>
                    <span>{{ __('Contracts') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.leaderboard') }}" class="navItem {{ isActive('user.leaderboard') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:trophy"></iconify-icon>
                    <span>{{ __('Leaderboard') }}</span>
                </span>
            </a>
        </li>

        {{-- <li>
            <a href="{{ route('user.utilities') }}" class="navItem {{ isActive('user.utilities') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="solar:share-circle-linear"></iconify-icon>
                    <span>{{ __('Utilities') }}</span>
                </span>
            </a>
        </li> --}}

{{--        <li class="">--}}
{{--            <a href="javascript:void(0);" class="navItem">--}}
{{--              <span class="flex items-center">--}}
{{--                <iconify-icon class="nav-icon" icon="material-symbols:history"></iconify-icon>--}}
{{--                <span>{{ __('History') }}</span>--}}
{{--              </span>--}}
{{--                <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>--}}
{{--            </a>--}}
{{--            <ul class="sidebar-submenu">--}}
{{--                <li>--}}
{{--                    <a href="{{ route('user.transactions') }}" class="{{ isActive('user.transactions') }}">--}}
{{--                        {{ __('All History') }}--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                --}}{{--<li>--}}
{{--                    <a href="{{ route('user.deposit.log') }}" class="{{ isActive('user.deposit.log') }}">--}}
{{--                        {{ __('Deposits') }}--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <a href="{{ route('user.withdraw.log') }}" class="{{ isActive('user.withdraw.log') }}">--}}
{{--                        {{ __('Withdrawals') }}--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <a href="{{ route('user.send-money.log') }}" class="{{ isActive('user.send-money.log') }}">--}}
{{--                        {{ __('Transfer Log') }}--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                --}}{{--<li>--}}
{{--                    <a href="{{ route('user.forex.transactions') }}" class="{{ isActive('user.forex.transactions') }}">--}}
{{--                        {{ __('Accounts History') }}--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </li>--}}

        <li>
            <a href="{{ route('user.affiliate-area.index') }}" class="navItem loaderBtn {{ isActive('user.affiliate-area.index') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:share-2"></iconify-icon>
                    <span>{{ __('Refer & Earn') }}</span>
                </span>
            </a>
        </li>

        <li id="jiggleText">
            <a href="{{route('user.offers')}}" class="navItem jiggle {{ isActive('user.offers') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:tag"></iconify-icon>
                    <span>{{ __('Offers') }}</span>
                </span>
                @php
                    $user_offers_count = App\Models\UserOffer::where('user_id', Auth::id())->where('status', 'available')->count();
                @endphp
                @if ($user_offers_count > 0 && !isset($userOffers))
                    <span class="badge badge-success" style="text-transform: none">
                        {{ $user_offers_count }} Coupon(s)
                    </span>
                @endif
            </a>
        </li>

        {{-- Coupon shake effect --}}
        @if (!isset($userOffers))
            @if ($user_offers_count > 0)
                <style>
                    @keyframes jiggle {
                        0%, 100% { transform: translateX(0); }
                        20% { transform: translateX(-5px); }
                        40% { transform: translateX(5px); }
                        60% { transform: translateX(-5px); }
                        80% { transform: translateX(5px); }
                    }

                    .jiggle {
                        animation: jiggle 0.6s ease;
                    }
                </style>
                <script>
                    const text = document.getElementById('jiggleText');

                    setInterval(() => {
                        text.classList.add('jiggle');

                        // Remove the class after animation ends so it can retrigger
                        setTimeout(() => {
                        text.classList.remove('jiggle');
                        }, 600); // Match duration of CSS animation
                    }, 4000);
                </script>
            @endif
        @endif

        <li>
            <a href="{{ route('user.ticket.index') }}" class="navItem {{ isActive('user.ticket*') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:headset"></iconify-icon>
                    <span>{{ __('Tickets') }}</span>
                </span>
            </a>
        </li>

        <li class="block md:hidden">
            <form method="POST" action="{{ route('logout') }}" id="logout-form" class="mt-5 mb-3">
                @csrf
                <a href="{{ url('logout') }}" onclick="event.preventDefault(); localStorage.clear();  $('#logout-form').submit();" class="btn btn-base btn-sm block">
                    <span class="flex items-center justify-center">
                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2 font-light" icon="lucide:power"></iconify-icon>
                        {{ __('Logout') }}
                    </span>
                </a>
            </form>
        </li>

        {{-- <li class="">
            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger block-btn">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ep:switch-button"></iconify-icon>
                        <span>{{ __('Logout') }}</span>
                    </span>
                </button>
            </form>
        </li> --}}
    </ul>
</div>
<div class="sidebar-menu mt-3 menu-open sticky px-3 py-3" style="position: relative; bottom: 30px; z-index: 99; position: relative; ">
   <li style="">
     
    <a href="{{ route('user.account.buy') }}" class="navItem active" style="position: relative; height: 45px" >
        <div style="left: 50%; position:absolute; transform: translateX(-50%);">
            Start Challenge
        </div>
    </a>
     
   </li>
</div>