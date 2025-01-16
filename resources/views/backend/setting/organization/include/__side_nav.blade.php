@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li>
            <a href="{{ route('admin.settings.company') }}" class="navItem {{ isActive('admin.settings.company') }}">
                {{ __('Company') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.country.all') }}" class="navItem {{ isActive('admin.country.all') }}">
                {{ __('Country') }}
            </a>
        </li>
        <li>
            <a href="javascript:;" class="navItem">
                {{ __('Social Logins')}}
            </a>
        </li>
        <li>
            <a href="{{route('admin.links.legal-links')}}" class="navItem {{ isActive('admin.links*') }}">
                {{ __('Doc & Links')}}
            </a>
        </li>
    </ul>
@endsection
