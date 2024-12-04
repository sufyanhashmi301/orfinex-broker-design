@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        @canany(['company-setting', 'misc-setting', 'company-permissions-setting', 'departments-list', 'designations-list'])
        <li>
            <a href="{{ route('admin.settings.company') }}" class="navItem {{ isActive('admin.settings.company') }}">
                {{ __('Company') }}
            </a>
        </li>
        @endcanany
        <li>
            <a href="{{ route('admin.country.all') }}" class="navItem {{ isActive('admin.country.all') }}">
                {{ __('Country') }}
            </a>
        </li>
        <li>
            <a href="" class="navItem">
                {{ __('Social Logins')}}
            </a>
        </li>
        @canany(['document-link-list','platform-link-list'])
        <li>
            <a href="{{route('admin.links.document.index')}}" class="navItem {{ isActive('admin.links*') }}">
                {{ __('Doc & Links')}}
            </a>
        </li>
        @endcanany
    </ul>
@endsection
