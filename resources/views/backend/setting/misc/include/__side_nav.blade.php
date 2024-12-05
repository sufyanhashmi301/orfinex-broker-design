@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        @canany(['all-sections-settings', 'blocklist-ip-settings', 'single-session-settings', 'blocklist-email-settings', 'login-expiry-settings'])
        <li>
            <a href="{{ route('admin.security.all-sections') }}" class="navItem {{ isActive('admin.security*') }}">
                {{ __('Security')}}
            </a>
        </li>
        @endcanany
        @can('language-list')
            <li>
                <a href="{{ route('admin.language.index') }}" class="navItem {{ isActive('admin.language*') }}">
                    {{ __('Language') }}
                </a>
            </li>
        @endcan
        @canany(['ticket-priority-list', 'ticket-status-list'])
        <li>
            <a href="{{ route('admin.ticket.statuses.index') }}" class="navItem" class="navItem {{ isActive('admin.ticket*') }}">
                {{ __('Support Center')}}
            </a>
        </li>
        @endcanany
        <li>
            <a href="" class="navItem" class="navItem">
                {{ __('Multi-Factor Auth')}}
            </a>
        </li>
    </ul>
@endsection
