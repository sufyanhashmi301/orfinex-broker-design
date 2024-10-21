<div id="bodyOverlay" class="w-screen h-screen fixed top-0 bg-slate-900 bg-opacity-50 backdrop-blur-sm z-10 hidden"></div>
<div class="logo-segment flex-wrap">
    <a href="{{ route('home') }}" class="loaderBtn items-center md:flex hidden">
        <img src="{{ asset(setting('site_logo', 'global')) }}" class="black_logo max-w-[160px]" alt="{{ __('Logo') }}"/>
        <img src="{{ asset(setting('site_logo_light', 'global')) }}" class="white_logo max-w-[160px]" alt="{{ __('Logo') }}"/>
    </a>
    <div class="md:hidden" style="width: 80%; overflow-x: hidden;">
        <div class="flex items-center">
            <div class="flex-none">
                <div class="w-8 h-8 rounded-[100%] ltr:mr-2 rtl:ml-2">
                    <img src="{{ asset('frontend/images/all-img/user.png') }}" alt="{{ __('User Profile') }}" class="w-full h-full rounded-[100%] object-cover">
                </div>
            </div>
            <div class="flex-1 text-start mobileUserInfo">
                <h4 class="text-sm font-medium whitespace-nowrap">
                    {{ auth()->user()->full_name }}
                </h4>
                <div class="text-xs font-normal">
                    {{ auth()->user()->email }}
                </div>
            </div>
        </div>
    </div>
    <!-- Sidebar Type Button -->
<!--<div id="sidebar_type" class="cursor-pointer text-slate-900 dark:text-white text-lg">
        <span class="sidebarDotIcon extend-icon cursor-pointer text-slate-900 dark:text-white text-2xl">
            <div class="h-4 w-4 border-[1.5px] border-slate-900 dark:border-slate-700 rounded-full transition-all duration-150 ring-2 ring-inset ring-offset-4 ring-black-900 dark:ring-slate-400 bg-slate-900 dark:bg-slate-400 dark:ring-offset-slate-700"></div>
        </span>
        <span class="sidebarDotIcon collapsed-icon cursor-pointer text-slate-900 dark:text-white text-2xl">
            <div class="h-4 w-4 border-[1.5px] border-slate-900 dark:border-slate-700 rounded-full transition-all duration-150"></div>
        </span>
    </div>-->
    <button class="sidebarCloseIcon text-2xl">
        <iconify-icon icon="clarity:window-close-line"></iconify-icon>
    </button>

    <div class="flex items-center justify-center w-full md:hidden mt-3 space-x-2">
        <a href="{{ route('user.ranking-badge') }}" class="loaderBtn inline-flex btn btn-primary btn-sm">
            {{ __('Ranking Badge') }}
        </a>
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <a href="{{ url('logout') }}"
               onclick="event.preventDefault(); localStorage.clear();  $('#logout-form').submit();"
               class="inline-flex btn btn-base btn-sm">
                {{ __('Logout') }}
            </a>
        </form>
    </div>
</div>
<div id="nav_shadow" class="nav_shadow h-[60px] absolute top-[80px] nav-shadow z-[1] w-full transition-all duration-200 pointer-events-none opacity-0"></div>
<div class="sidebar-menus bg-white dark:bg-body py-2 px-4 h-[calc(100%-80px)] overflow-y-auto z-50" id="sidebar_menus">
    <ul class="sidebar-menu">
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
            <a href="{{ route('user.deposit.amount') }}" class="navItem loaderBtn @if(Route::currentRouteName() != 'user.deposit.log') {{ isActive('user.deposit*') }} @endif">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="heroicons-outline:download"></iconify-icon>
                    <span>{{ __('Deposit') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.transfer') }}" class="navItem loaderBtn {{ isActive('user.transfer') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="heroicons-outline:switch-horizontal"></iconify-icon>
                    <span>{{ __('Transfer') }}</span>
                </span>
            </a>
        </li>

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
        <li class="">
            <a href="javascript:void(0);" class="navItem">
              <span class="flex items-center">
                <iconify-icon class="nav-icon" icon="material-symbols:history"></iconify-icon>
                <span>{{ __('History') }}</span>
              </span>
                <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
            </a>
            <ul class="sidebar-submenu">
                <li>
                    <a href="{{ route('user.transactions') }}" class="loaderBtn {{ isActive('user.transactions') }}">
                        {{ __('All History') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.deposit.log') }}" class="loaderBtn {{ isActive('user.deposit.log') }}">
                        {{ __('Deposits') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.withdraw.log') }}" class="loaderBtn {{ isActive('user.withdraw.log') }}">
                        {{ __('Withdrawals') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.send-money.log') }}" class="loaderBtn {{ isActive('user.send-money.log') }}">
                        {{ __('Transfer Log') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.forex.transactions') }}" class="loaderBtn {{ isActive('user.forex.transactions') }}">
                        {{ __('Accounts History') }}
                    </a>
                </li>
            </ul>
        </li>

        @if(setting('sign_up_referral', 'permission'))
            <li>
                <a href="{{ route('user.multi-level.ib.dashboard') }}" class="navItem loaderBtn {{ isActive('user.referral') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="heroicons-outline:share"></iconify-icon>
                        <span>{{ __('Partner Area') }}</span>
                    </span>
                </a>
            </li>
        @endif

        <li>
            <a href="{{ route('user.setting.profile') }}" class="navItem loaderBtn {{ isActive('user.setting*') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="heroicons-outline:cog"></iconify-icon>
                    <span>{{ __('Settings') }}</span>
                </span>
            </a>
        </li>
        <li>
            <a href="{{ route('user.ticket.index') }}" class="navItem loaderBtn {{ isActive('user.ticket*') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="heroicons-outline:support"></iconify-icon>
                    <span>{{ __('Support Tickets') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="javascript:;" id="themeMood" class="navItem loaderBtn">
                <span class="dark:flex items-center hidden">
                    <iconify-icon class="nav-icon" id="sunIcon" icon="line-md:moon-filled-to-sunny-filled-loop-transition"></iconify-icon>
                    <span>{{ __('Light Mode') }}</span>
                </span>
                <span class="dark:hidden flex items-center">
                    <iconify-icon class="nav-icon" id="moonIcon" icon="line-md:sunny-outline-to-moon-alt-loop-transition"></iconify-icon>
                    <span>{{ __('Dark Mode') }}</span>
                </span>
            </a>
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
