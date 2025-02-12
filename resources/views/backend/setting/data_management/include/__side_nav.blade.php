@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li>
            <a href="javascript:;" class="navItem">
                {{ __('Data Import') }}
            </a>
        </li>
        <li>
            <a href="javascript:;" class="navItem">
                {{ __('Data Export') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.endToEndEncryption') }}" class="navItem {{ isActive('admin.settings.endToEndEncryption') }}">
                {{ __('Data Encryption') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.storage.index') }}" class="navItem {{ isActive('admin.settings.storage.index') }}">
                {{ __('Images/Files Storage') }}
            </a>
        </li>
    </ul>
@endsection
