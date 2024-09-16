@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li>
            <a href="{{ route('admin.security.all-sections') }}" class="navItem {{ isActive('admin.security*') }}">
                {{ __('Security')}}
            </a>
        </li>
        @can('language-setting')
            <li>
                <a href="{{ route('admin.language.index') }}" class="navItem {{ isActive('admin.language*') }}">
                    {{ __('Language') }}
                </a>
            </li>
        @endcan
        <li>
            <a href="" class="navItem" class="navItem">
                {{ __('Multi-Factor Auth')}}
            </a>
        </li>
    </ul>
@endsection
