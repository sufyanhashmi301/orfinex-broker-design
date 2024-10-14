@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li>
            <a href="{{ route('admin.theme.colors', ['type' => 'light_colors']) }}" class="navItem {{ isActive('admin.theme.colors') }}">
                {{ __('Custom Colors') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.theme.fonts') }}" class="navItem {{ isActive('admin.theme.fonts') }}">
                {{ __('Custom Fonts') }}
            </a>
        </li>
        <li>
            <a href="javascript:;" class="navItem">
                {{ __('Routes')}}
            </a>
        </li>
        <li>
            <a href="javascript:;" class="navItem">
                {{ __('Dynamic Content')}}
            </a>
        </li>
    </ul>
@endsection
