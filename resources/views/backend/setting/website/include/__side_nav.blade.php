@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        @can('theme-settings')
        <li>
            <a href="{{ route('admin.theme.site') }}" class="navItem {{ isActive('admin.theme.site') }}">
                {{ __('Theme')}}
            </a>
        </li>
        @endcan

        @can('branding-settings')
        <li>
            <a href="{{ route('admin.theme.global') }}" class="navItem {{ isActive('admin.theme.global') }}">
                {{ __('Branding')}}
            </a>
        </li>
        @endcan

        @canany('site-settings','customer-registration-settings')
        <li>
            <a href="{{ route('admin.settings.site') }}" class="navItem {{ isActive('admin.settings.site') }}">
                {{ __('Site Settings') }}
            </a>
        </li>
        @endcanany

        @can('banner-settings')
        <li>
            <a href="{{ route('admin.banners') }}" class="navItem {{ isActive('admin.banners') }}">
                {{ __('Banner') }}
            </a>
        </li>
        @endcan

        @can('gdpr-compliance-settings')
        <li>
            <a href="{{ route('admin.grpdCompliance') }}" class="navItem {{ isActive('admin.grpdCompliance') }}">
                {{ __('GDPR Compliance')}}
            </a>
        </li>
        @endcan

        @can('maintainance-settings')
        <li>
            <a href="{{ route('admin.settings.site-maintenance') }}" class="navItem {{ isActive('admin.settings.site-maintenance') }}">
                {{ __('Maintenance')}}
            </a>
        </li>
        @endcan
    </ul>
@endsection
