<div class="card p-4 mb-5">
    <ul class="nav nav-pills flex items-center overflow-x-auto list-none pl-0 pb-1 sm:pb-0 gap-4 menu-open">
        <li class="nav-item">
            <a href="{{ route('admin.platform_api.match_trader') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize text-nowrap rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.platform_api.match_trader') }}">
                {{ __('Match Trader') }}
            </a>
        </li>
        @if(Route::is(['admin.settings.webterminal.mt5', 'admin.settings.webterminal.x9']))
            <li class="nav-item">
                <a href="{{ route('admin.settings.webterminal.mt5') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize text-nowrap rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.webterminal.mt5') }}">
                    {{ __('Metatrader 5') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.webterminal.x9') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize text-nowrap rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.webterminal.x9') }}">
                    {{ __('X9 Trader') }}
                </a>
            </li>
        @else
            <li class="nav-item">
                <a href="{{ route('admin.platform_api.ctrader') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize text-nowrap rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.platform_api.ctrader') }}">
                    {{ __('CTrader') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.platform-api') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize text-nowrap rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.platform-api') }}">
                    {{ __('MetaTrader') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.platform_api.x9trader') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize text-nowrap rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.platform_api.x9trader') }}">
                    {{ __('X9 Trader') }}
                </a>
            </li>
        @endif
    </ul>
</div>
