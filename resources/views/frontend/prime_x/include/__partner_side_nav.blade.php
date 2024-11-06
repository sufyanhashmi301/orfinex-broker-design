<div id="bodyOverlay" class="w-screen h-screen fixed top-0 bg-slate-900 bg-opacity-50 backdrop-blur-sm z-10 hidden"></div>
<div class="logo-segment flex-wrap">
    <a href="{{ route('home') }}" class="items-center md:flex hidden">
        <img src="{{ asset(setting('site_logo','global')) }}" class="black_logo max-w-[160px]" alt="{{ __('Logo') }}"/>
        <img src="{{ asset(setting('site_logo_light','global')) }}" class="white_logo max-w-[160px]" alt="{{ __('Logo') }}"/>
    </a>
    <div class="md:hidden" style="width: 80%; overflow-x: hidden;">
        <div class="flex items-center">
            <div class="flex-none">
                <div class="w-8 h-8 rounded-[100%] ltr:mr-2 rtl:ml-2">
                    <img src="{{ asset('frontend/images/all-img/user.png') }}" alt="{{ __('User Image') }}"
                         class="w-full h-full rounded-[100%] object-cover">
                </div>
            </div>
            <div class="flex-1 text-start">
                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                    {{ auth()->user()->full_name }}
                </h4>
                <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                    {{ auth()->user()->email }}
                </div>
            </div>
        </div>
    </div>
    <!-- Sidebar Type Button -->
    <div id="sidebar_type" class="cursor-pointer text-slate-900 dark:text-white text-lg">
        <span class="sidebarDotIcon extend-icon cursor-pointer text-slate-900 dark:text-white text-2xl">
            <div
                class="h-4 w-4 border-[1.5px] border-slate-900 dark:border-slate-700 rounded-full transition-all duration-150 ring-2 ring-inset ring-offset-4 ring-black-900 dark:ring-slate-400 bg-slate-900 dark:bg-slate-400 dark:ring-offset-slate-700"></div>
        </span>
        <span class="sidebarDotIcon collapsed-icon cursor-pointer text-slate-900 dark:text-white text-2xl">
            <div
                class="h-4 w-4 border-[1.5px] border-slate-900 dark:border-slate-700 rounded-full transition-all duration-150"></div>
        </span>
    </div>
    <button class="sidebarCloseIcon text-2xl">
        <iconify-icon class="text-slate-900 dark:text-slate-200" icon="clarity:window-close-line"></iconify-icon>
    </button>

    <div class="flex items-center justify-center w-full md:hidden mt-3 space-x-2">
        <a href="{{ route('user.ranking-badge') }}" class="inline-flex btn btn-outline-dark btn-sm">
            {{ __('Ranking Badge') }}
        </a>
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <a href="{{ url('logout') }}"
               onclick="event.preventDefault(); localStorage.clear();  $('#logout-form').submit();"
               class="inline-flex btn btn-dark btn-sm">
                {{ __('Logout') }}
            </a>
        </form>
    </div>
</div>
<div id="nav_shadow" class="nav_shadow h-[60px] absolute top-[80px] nav-shadow z-[1] w-full transition-all duration-200 pointer-events-none opacity-0"></div>
<div class="sidebar-menus bg-white dark:bg-body py-2 px-4 h-[calc(100%-80px)] overflow-y-auto z-50" id="sidebar_menus">
    <ul class="sidebar-menu">
        <li class="sidebar-menu-title">{{ __('MENU') }}</li>
        <li>
            <a href="{{ route('user.referral') }}" class="navItem {{ isActive('user.referral') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="heroicons-outline:home"></iconify-icon>
                    <span>{{ __('Dashboard') }}</span>
                </span>
            </a>
        </li>
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

        <li>
            <a href="{{ route('user.dashboard') }}" class="navItem {{ isActive('user.dashboard') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:user-round"></iconify-icon>
                    <span>{{ __('Client Portal') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="javascript:;" id="themeMood" class="navItem">
                <span class="dark:flex items-center hidden">
                    <iconify-icon class="nav-icon" id="sunIcon"
                                  icon="line-md:moon-filled-to-sunny-filled-loop-transition"></iconify-icon>
                    <span>{{ __('Light Mode') }}</span>
                </span>
                <span class="dark:hidden flex items-center">
                    <iconify-icon class="nav-icon" id="moonIcon"
                                  icon="line-md:sunny-outline-to-moon-alt-loop-transition"></iconify-icon>
                    <span>{{ __('Dark Mode') }}</span>
                </span>
            </a>
        </li>

        <li class="">
            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="navItem">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="ep:switch-button"></iconify-icon>
                        <span>{{ __('Logout') }}</span>
                    </span>
                </button>
            </form>
        </li>
    </ul>
</div>
