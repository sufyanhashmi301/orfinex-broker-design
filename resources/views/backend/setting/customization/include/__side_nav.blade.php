@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        @can('custom-colors-settings')
        <li>
            <a href="{{ route('admin.theme.colors', ['type' => 'light_colors']) }}" class="navItem {{ isActive('admin.theme.colors') }}">
                {{ __('Custom Colors') }}
            </a>
        </li>
        @endcan
        @can('custom-fonts-settings')
        <li>
            <a href="{{ route('admin.theme.fonts') }}" class="navItem {{ isActive('admin.theme.fonts') }}">
                {{ __('Custom Fonts') }}
            </a>
        </li>
        @endcan
        @can('routes-settings')
        <li>
            <a href="javascript:;" class="navItem">
                {{ __('Routes')}}
            </a>
        </li>
        @endcan
        @can('dynamic-content-settings')
        <li>
            <a href="{{ route('admin.settings.dynamic-content.success-page') }}" class="navItem {{ isActive('admin.settings.dynamic-content*') }}">
                {{ __('Dynamic Content')}}
            </a>
        </li>
        @endcan
    </ul>
@endsection
