@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li class="">
            <a href="{{ route('admin.settings.clearCache') }}" class="navItem {{ isActive('admin.settings.clearCache') }}">
                {{ __('Clear Cache') }}
            </a>
        </li>
        <li class="">
            <a href="{{ route('admin.application-info') }}" class="navItem {{ isActive('admin.application-info') }}">
                {{ __('Application Details') }}
            </a>
        </li>
        <li class="">
            <a href="{{ route('admin.settings.devMode') }}" class="navItem {{ isActive('admin.settings.devMode') }}">
                {{ __('Dev Mode') }}
            </a>
        </li>
        <li class="">
            <a href="javascript:;" class="navItem">
                {{ __('Changelog') }}
            </a>
        </li>
        {{-- <li class="">
            <a href="{{ route('admin.reportIssues') }}" class="navItem {{ isActive('admin.reportIssues') }}">
                {{ __('Report Issue') }}
            </a>
        </li> --}}
    </ul>
@endsection
