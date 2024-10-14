@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li class="">
            <a href="{{ route('admin.clear-cache') }}" class="navItem {{ isActive('admin.clear-cache') }}">
                {{ __('Clear Cache') }}
            </a>
        </li>
        <li class="">
            <a href="{{ route('admin.application-info') }}" class="navItem {{ isActive('admin.application-info') }}">
                {{ __('Application Details') }}
            </a>
        </li>
        <li class="">
            <a href="{{ route('admin.changelog') }}" class="navItem {{ isActive('admin.changelog') }}">
                {{ __('Changelog') }}
            </a>
        </li>
        <li class="">
            <a href="{{ route('admin.reportIssues') }}" class="navItem {{ isActive('admin.reportIssues') }}">
                {{ __('Report Issue') }}
            </a>
        </li>
    </ul>
@endsection
