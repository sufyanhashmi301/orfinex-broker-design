@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li>
            <a href="{{ route('admin.theme.site') }}" class="navItem {{ isActive('admin.theme*') }}">
                {{ __('Theme')}}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.site') }}" class="navItem {{ isActive('admin.settings.site') }}">
                {{ __('Site Settings') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.gdpr') }}" class="navItem {{ isActive('admin.settings.gdpr') }}">
                {{ __('GDPR')}}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.site-maintenance') }}" class="navItem {{ isActive('admin.settings.site-maintenance') }}">
                {{ __('Maintenance')}}
            </a>
        </li>
    </ul>
@endsection
