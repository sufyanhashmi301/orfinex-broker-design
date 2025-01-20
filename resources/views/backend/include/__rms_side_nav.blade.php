<div id="bodyOverlay" class="w-screen h-screen fixed top-0 bg-slate-900 bg-opacity-50 backdrop-blur-sm z-10 hidden"></div>
<div class="logo-segment">
    <a class="flex items-center" href="{{route('admin.dashboard')}}">
        @php
            $logoSrc = setting('site_favicon','global')
                ? asset(setting('site_favicon','global'))
                : asset('backend/images/example_favicon.png');
        @endphp
        <img src="{{ $logoSrc }}" class="black_logo h-8" alt="logo">
        <img src="{{ $logoSrc }}" class="white_logo h-8" alt="logo">
        <span class="logo-title ltr:ml-3 rtl:mr-3 text-xl font-Inter font-medium text-white">
            {{ __('Risk Hub') }}
        </span>
    </a>
    <!-- Sidebar Type Button -->
    <button class="sidebarCloseIcon text-2xl">
        <iconify-icon class="text-slate-900 dark:text-slate-200" icon="clarity:window-close-line"></iconify-icon>
    </button>
</div>
<div id="nav_shadow" class="nav_shadow h-[60px] absolute top-[80px] nav-shadow z-[1] w-full transition-all duration-200 pointer-events-none opacity-0"></div>
<div class="sidebar-menus py-2 px-4 h-[calc(100%-100px)] overflow-y-auto z-50" id="sidebar_menus">
    <ul class="sidebar-menu flex flex-column mt-3">

        <li>
            <a href="{{ route('admin.risk-rule.quick_trades') }}" class="navItem {{ isActive('admin.risk-rule.quick_trades') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:fast-forward"></iconify-icon>
                    <span>{{ __('Quick Trades Analysis') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.risk-rule.scalper_detection') }}" class="navItem {{ isActive('admin.risk-rule.scalper_detection') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:chart-scatter"></iconify-icon>
                    <span>{{ __('Scalper Detection') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.risk-rule.most_trades') }}" class="navItem {{ isActive('admin.risk-rule.most_trades') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:circle-fading-arrow-up"></iconify-icon>
                    <span>{{ __('Most Trades Analysis') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.risk-rule.open_positions') }}" class="navItem {{ isActive('admin.risk-rule.open_positions') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:chart-candlestick"></iconify-icon>
                    <span>{{ __('Open Trades Positions') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.risk-rule.trade_age') }}" class="navItem {{ isActive('admin.risk-rule.trade_age') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:history"></iconify-icon>
                    <span>{{ __('Trade Age Analysis') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.risk-rule.ip_address') }}" class="navItem {{ isActive('admin.risk-rule.ip_address') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:map-pinned"></iconify-icon>
                    <span>{{ __('IP Address Analysis') }}</span>
                </span>
            </a>
        </li>

        {{-- <li>
            <a href="{{ route('admin.activePositions') }}" class="navItem {{ isActive('admin.activePositions') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="ic:baseline-candlestick-chart"></iconify-icon>
                    <span>{{ __('Active Positions') }}</span>
                </span>
            </a>
        </li>

        <li>
            <a href="javascript:void(0);" class="navItem">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:settings-2"></iconify-icon>
                    <span>{{ __('Net Positions') }}</span>
                </span>
                <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
            </a>
            <ul class="sidebar-submenu">
                <li>
                    <a href="{{ route('admin.netPositionsAccounts') }}" class="{{ isActive('admin.netPositionsAccounts') }}">
                        {{ __('By Accounts') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.netPositionsGroups') }}" class="{{ isActive('admin.netPositionsGroups') }}">
                        {{ __('By Groups') }}
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('admin.olderPositionsDays') }}" class="navItem {{ isActive('admin.olderPositionsDays') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="ic:baseline-candlestick-chart"></iconify-icon>
                    <span>{{ __('Older Positions - Days') }}</span>
                </span>
            </a>
        </li> --}}
    </ul>
</div>
<div class="stickySetting_menu sticky z-50 bottom-0 px-6 py-4">
    <a href="{{route('admin.dashboard')}}" class="navItem {{ isActive('admin.dashboard') }}">
        <span class="flex items-center">
            <iconify-icon class="nav-icon" icon="lucide:arrow-left"></iconify-icon>
            <span>{{ __('Back to Backoffice') }}</span>
        </span>
    </a>
</div>
