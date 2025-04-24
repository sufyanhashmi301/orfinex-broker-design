@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        @canany(['company-setting', 'misc-setting', 'company-permissions-setting', 'departments-list', 'designations-list'])
        <li>
            <a href="{{ route('admin.settings.company') }}" class="navItem {{ isActive('admin.settings.company') }}">
                {{ __('Company') }}
            </a>
        </li>
        @endcanany
        @canany(['all-countries-list','blacklist-countries-list'])
        <li>
            <a href="{{ route('admin.country.all') }}" class="navItem {{ isActive('admin.country.all') }}">
                {{ __('Country') }}
            </a>
        </li>
        @endcanany
        @can('social-logins-list')
        <li>
            <a href="{{ route('admin.social.index') }}" class="navItem {{ isActive('admin.social.index') }}">
                {{ __('Social Logins')}}
            </a>
        </li>
        @endcan
        @canany(['document-link-list','platform-link-list'])
        <li>
            <a href="{{route('admin.links.document.index')}}" class="navItem {{ isActive('admin.links*') }}">
                {{ __('Doc & Links')}}
            </a>
        </li>
        @endcanany
    </ul>
@endsection
