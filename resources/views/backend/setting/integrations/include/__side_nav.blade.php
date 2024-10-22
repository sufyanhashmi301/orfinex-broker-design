@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li>
            <a href="{{ route('admin.settings.plugin','system') }}" class="navItem {{ isActive('admin.settings.plugin*') }}">
                {{ __('Plugins')}}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.apiAccess') }}" class="navItem {{ isActive('admin.settings.apiAccess') }}">
                {{ __('API Access')}}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.webHook') }}" class="navItem {{ isActive('admin.settings.webHook') }}">
                {{ __('Web Hooks')}}
            </a>
        </li>
    </ul>
@endsection
