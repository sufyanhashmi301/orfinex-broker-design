<div id="bodyOverlay" class="w-screen h-screen fixed top-0 bg-slate-900 bg-opacity-50 backdrop-blur-sm z-10 hidden"></div>
<div class="logo-segment flex-wrap">
    <a href="{{ route('home') }}" class="loaderBtn items-center md:flex hidden">
        <span class="black_logo">
            @if (isDarkColor(setting('header_bg', 'light_colors')))
                <img src="{{ getFilteredPath(setting('site_logo_light', 'global'), 'fallback/branding/desktop-logo.png') }}" class="max-w-[160px]" alt="{{ __('Light Logo') }}"/>
            @else
                <img src="{{ getFilteredPath(setting('site_logo', 'global'), 'fallback/branding/desktop-logo.png') }}" class="max-w-[160px]" alt="{{ __('Dark Logo') }}"/>
            @endif
        </span>
        <span class="white_logo">
            @if (isDarkColor(setting('header_bg_dark', 'light_colors')))
                <img src="{{ getFilteredPath(setting('site_logo_light', 'global'), 'fallback/branding/desktop-logo.png') }}" class="max-w-[160px]" alt="{{ __('Light Logo') }}"/>
            @else
                <img src="{{ getFilteredPath(setting('site_logo', 'global'), 'fallback/branding/desktop-logo.png') }}" class="max-w-[160px]" alt="{{ __('Dark Logo') }}"/>
            @endif
        </span>
    </a>

    <div class="flex itemx-center justify-between md:hidden" style="width: 80%; overflow-x: hidden;">
        <div class="flex items-center">
            <div class="flex-none">
                <div class="w-8 h-8 rounded-[100%] ltr:mr-2 rtl:ml-2">
                    <img src="{{ getFilteredPath(auth()->user()->avatar, 'fallback/user.png') }}" alt="{{ __('User Profile') }}" class="w-full h-full rounded-[100%] object-cover">
                </div>
            </div>
            <div class="flex-1 text-start mobileUserInfo">
                <h4 class="text-sm font-medium whitespace-nowrap header-text-color">
                    {{ auth()->user()->full_name }}
                </h4>
                <span class="flex items-center text-slate-400 text-xs font-normal">
                    @if($user->kyc >= \App\Enums\KYCStatus::Level2->value)
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
            <a href="{{ route('user.dashboard') }}" class="navItem loaderBtn {{ isActive('user.dashboard') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="heroicons-outline:home"></iconify-icon>
                    <span>{{ __('Dashboard') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.wallet.index') }}" class="navItem loaderBtn {{ isActive('user.wallet*') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="solar:wallet-linear"></iconify-icon>
                    <span>{{ __('Wallets') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.forex-account-logs') }}" class="navItem loaderBtn {{ isActive('user.forex*') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="heroicons-outline:clipboard-list"></iconify-icon>
                    <span>{{ __('My Accounts') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.schema') }}" class="navItem loaderBtn {{ isActive('user.schema*') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="heroicons-outline:document-add"></iconify-icon>
                    <span>{{ __('New Account') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.deposit.methods') }}" class="navItem loaderBtn @if(Route::currentRouteName() != 'user.deposit.log') {{ isActive('user.deposit*') }} @endif">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="heroicons-outline:download"></iconify-icon>
                    <span>{{ __('Deposit') }}</span>
                </span>
            </a>
        </li>

        @if(setting('is_internal_transfer', 'transfer_internal') || setting('is_external_transfer', 'transfer_external'))
            <li>
                <a href="{{ route('user.transfer') }}" class="navItem loaderBtn {{ isActive('user.transfer') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="heroicons-outline:switch-horizontal"></iconify-icon>
                        <span>{{ __('Transfer') }}</span>
                    </span>
                </a>
            </li>
        @endif

        <li>
            <a href="{{ route('user.withdraw.view') }}" class="navItem loaderBtn @if(Route::currentRouteName() != 'user.withdraw.log') {{ isActive('user.withdraw*') }} @endif">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="heroicons-outline:upload"></iconify-icon>
                    <span>{{ __('Withdraw') }}</span>
                </span>
            </a>
        </li>
        @if(setting('is_copy_trading', 'copy_trading'))
            <li>
                <a href="{{ route('user.follower_access') }}" class="navItem loaderBtn {{ isActive('user.follower_access') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="solar:graph-up-broken"></iconify-icon>
                    <span>{{ __('Copy Trading') }}</span>
                </span>
                </a>
            </li>
        @endif
        <li>
            <a href="{{ route('user.history.transactions') }}" class="navItem loaderBtn {{ isActive('user.history*') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="material-symbols:history"></iconify-icon>
                    <span>{{ __('History') }}</span>
                </span>
            </a>
        </li>
{{--        @if(setting('sign_up_referral', 'permission'))--}}
            @if(auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED || isset(auth()->user()->ref_id))
            <li>
                <a href="{{ route('user.multi-level.ib.dashboard') }}" class="navItem loaderBtn {{ isActive('user.referral') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="heroicons-outline:share"></iconify-icon>
                        <span>{{ __('Partner Area') }}</span>
                    </span>
                </a>
            </li>
        @elseif(auth()->user()->ib_status != \App\Enums\IBStatus::APPROVED && !isset(auth()->user()->ref_id))
            <li>
                <a href="{{ route('user.ib.request') }}" class="navItem {{ isActive('user.ib.request')}}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="grommet-icons:resources"></iconify-icon>
                    <span>{{ __('Request Master IB') }}</span>
                </span>
                </a>
            </li>
        @endif

{{--        @endif--}}
        @if(setting('user_tickets_feature', 'customer_permission'))
            <li>
                <a href="{{ route('user.ticket.index') }}" class="navItem loaderBtn {{ isActive('user.ticket*') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="ix:support"></iconify-icon>
                            <span>{{ __('Tickets') }}</span>
                    </span>
                </a>
            </li>
        @endif
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
<div class="stickySetting_menu sticky hidden md:block z-10 bottom-0 px-6 py-3">
    <a href="{{ route('user.setting.profile') }}" class="navItem loaderBtn">
        <span class="flex items-center">
            <iconify-icon class="nav-icon" icon="heroicons-outline:cog"></iconify-icon>
            <span>{{ __('Settings') }}</span>
        </span>
    </a>
</div>
