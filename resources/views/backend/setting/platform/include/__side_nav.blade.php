@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li>
            <a href="{{ route('admin.settings.platform-api') }}" class="navItem {{ isActive('admin.settings.platform-api*') }}">
                {{ __('Platform API') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.platform_api.db-synchronization') }}" class="navItem {{ isActive(['admin.platform_api.db-synchronization', 'admin.platform_api.x9trader']) }}">
                {{ __('DB Synchronization') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.platformGroups') }}" class="navItem {{ isActive('admin.platformGroups') }}">
                {{ __('Platform Groups') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.platform.riskBook') }}" class="navItem {{ isActive('admin.platform.riskBook') }}">
                {{ __('Risk Book') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.copyTrading') }}" class="navItem {{ isActive('admin.settings.copyTrading') }}">
                {{ __('Copy Trading')}}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.webterminal.mt5') }}" class="navItem {{ isActive('admin.settings.webterminal.mt5') }}">
                {{ __('Web Terminal')}}
            </a>
        </li>
    </ul>
@endsection
