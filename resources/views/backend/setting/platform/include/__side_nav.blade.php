@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        @canany(['c-trader-display', 'meta-trader-display', 'x9-trader-display'])
        <li>
            <a href="{{ route('admin.settings.platform-api') }}" class="navItem {{ isActive('admin.settings.platform-api*') }}">
                {{ __('Platform API') }}
            </a>
        </li>
        @endcanany
        @can('db-synchronization-setting')
        <li>
            <a href="{{ route('admin.platform_api.db-synchronization') }}" class="navItem {{ in_array(Route::currentRouteName(), ['admin.platform_api.db-synchronization', 'admin.platform_api.dbX9trader']) ? 'active' : '' }}">
                {{ __('DB Synchronization') }}
            </a>
        </li>
        @endcan
        @canany(['mt5-group-list', 'manual-group-list'])
        <li>
            <a href="{{ route('admin.platformGroups') }}" class="navItem {{ isActive('admin.platformGroups') }}">
                {{ __('Platform Groups') }}
            </a>
        </li>
        @endcanany
        @can('risk-book-list')
        <li>
            <a href="{{ route('admin.platform.riskBook') }}" class="navItem {{ isActive('admin.platform.riskBook') }}">
                {{ __('Risk Book') }}
            </a>
        </li>
        @endcan
        @can('copy-trading-setting')
        <li>
            <a href="{{ route('admin.settings.copyTrading') }}" class="navItem {{ isActive('admin.settings.copyTrading') }}">
                {{ __('Copy Trading')}}
            </a>
        </li>
        @endcan
        @canany(['mt5-webterminal-display', 'x9-webterminal-display'])
        <li>
            <a href="{{ route('admin.settings.webterminal.mt5') }}" class="navItem {{ isActive('admin.settings.webterminal.mt5') }}">
                {{ __('Web Terminal')}}
            </a>
        </li>
        @endcanany
    </ul>
@endsection
