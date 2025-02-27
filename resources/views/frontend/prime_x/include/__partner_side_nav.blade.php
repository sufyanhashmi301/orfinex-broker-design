<div id="bodyOverlay" class="w-screen h-screen fixed top-0 bg-slate-900 bg-opacity-50 backdrop-blur-sm z-10 hidden"></div>
<div class="logo-segment flex-wrap">
    <a href="{{ route('home') }}" class="loaderBtn items-center md:flex hidden">
        <span class="black_logo">
            @if (isDarkColor(setting('header_bg', 'light_colors')))
                <img src="{{ asset(setting('site_logo_light', 'global')) }}" class="max-w-[160px]" alt="{{ __('Light Logo') }}"/>
            @else
                <img src="{{ asset(setting('site_logo', 'global')) }}" class="max-w-[160px]" alt="{{ __('Dark Logo') }}"/>
            @endif
        </span>
        <span class="white_logo">
            @if (isDarkColor(setting('header_bg_dark', 'light_colors')))
                <img src="{{ asset(setting('site_logo_light', 'global')) }}" class="max-w-[160px]" alt="{{ __('Light Logo') }}"/>
            @else
                <img src="{{ asset(setting('site_logo', 'global')) }}" class="max-w-[160px]" alt="{{ __('Dark Logo') }}"/>
            @endif
        </span>
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
        <li class="sidebar-menu-title">{{ __('MENU') }}</li>

        @if(auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED || isset(auth()->user()->ref_id))
        <li>
            <a href="{{ route('user.multi-level.ib.dashboard') }}" class="navItem {{ isActive('user.multi-level.ib.dashboard') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="heroicons-outline:home"></iconify-icon>
                    <span>{{ __('Dashboard') }}</span>
                </span>
            </a>
        </li>
        @endif
        <li>
            <a href="{{ route('user.referral.network') }}" class="navItem {{ isActive('user.referral.network') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:network"></iconify-icon>
                    <span>{{ __('Network') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.referral.advertisement.material') }}" class="navItem {{ isActive('user.referral.advertisement.material') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="grommet-icons:resources"></iconify-icon>
                    <span>{{ __('Resources') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('user.referral.reports') }}" class="navItem {{ isActive('user.referral.reports') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:clipboard-list"></iconify-icon>
                    <span>{{ __('Reports') }}</span>
                </span>
            </a>
        </li>
        <li>
            <a href="{{ route('user.referral.members') }}" class="navItem {{ isActive('user.referral.members') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:user-round"></iconify-icon>
                    <span>{{ __('Referrals') }}</span>
                </span>
            </a>
        </li>
        @if(auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED)

        <li>
            <a href="{{ route('user.multi-level.ib.rules') }}" class="navItem {{ isActive('user.multi-level.ib.rules') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:user-round"></iconify-icon>
                    <span>{{ __('Sub Ib Rules') }}</span>
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
    </ul>
</div>
<div class="stickySetting_menu sticky bottom-0 px-6 py-3">
    <a href="{{ route('user.dashboard') }}" class="navItem loaderBtn">
        <span class="flex items-center">
            <iconify-icon class="nav-icon" icon="lucide:arrow-left"></iconify-icon>
            <span>{{ __('Client Area') }}</span>
        </span>
    </a>
</div>
