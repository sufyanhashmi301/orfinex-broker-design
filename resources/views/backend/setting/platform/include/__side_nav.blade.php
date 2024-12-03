@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li>
            <a href="{{ route('admin.settings.platform-api') }}" class="navItem {{ isActive('admin.settings.platform-api*') }}">
                {{ __('Platform API') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.platform_api.db-synchronization') }}" class="navItem {{ in_array(Route::currentRouteName(), ['admin.platform_api.db-synchronization', 'admin.platform_api.dbX9trader']) ? 'active' : '' }}">
                {{ __('DB Synchronization') }}
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
