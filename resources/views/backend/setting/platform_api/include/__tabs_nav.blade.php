@section('tabs-nav')
    <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open mb-6">
        @if(Route::is(['admin.settings.webterminal.mt5', 'admin.settings.webterminal.x9']))
        @can('mt5-webterminal-display')
            <li class="nav-item">
                <a href="{{ route('admin.settings.webterminal.mt5') }}" class="block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 bg-white dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.webterminal.mt5') }}">
                    {{ __('Metatrader 5') }}
                </a>
            </li>
            @endcan
            @can('x9-webterminal-display')
            <li class="nav-item">
                <a href="{{ route('admin.settings.webterminal.x9') }}" class="block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 bg-white dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.webterminal.x9') }}">
                    {{ __('X9 Trader') }}
                </a>
            </li>
            @endcan
        @else
        @can('c-trader-display')
            <li class="nav-item">
                <a href="{{ route('admin.platform_api.ctrader') }}" class="block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 bg-white dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.platform_api.ctrader') }}">
                    {{ __('CTrader') }}
                </a>
            </li>
            @endcan
            @can('meta-trader-display')
            <li class="nav-item">
                <a href="{{ route('admin.settings.platform-api') }}" class="block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 bg-white dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.platform-api') }}">
                    {{ __('MetaTrader') }}
                </a>
            </li>
            @endcan
            @can('x9-trader-display')
            <li class="nav-item">
                <a href="{{ route('admin.platform_api.x9trader') }}" class="block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 bg-white dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.platform_api.x9trader') }}">
                    {{ __('X9 Trader') }}
                </a>
            </li>
            @endcan
        @endif
    </ul>
@endsection
