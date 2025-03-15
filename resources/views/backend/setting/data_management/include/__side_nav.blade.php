@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        @can('import-settings')
        <li>
            <a href="javascript:;" class="navItem">
                {{ __('Data Import') }}
            </a>
        </li>
        @endcan
        @can('export-settings')
        <li>
            <a href="javascript:;" class="navItem">
                {{ __('Data Export') }}
            </a>
        </li>
        @endcan
        @can('data-encryption-settings')
        <li>
            <a href="{{ route('admin.settings.endToEndEncryption') }}" class="navItem {{ isActive('admin.settings.endToEndEncryption') }}">
                {{ __('Data Encryption') }}
            </a>
        </li>
        @endcan
    </ul>
@endsection
