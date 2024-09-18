@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li>
            <a href="{{ route('admin.risk-profile-tag.index') }}" class="navItem {{ request()->routeIs('admin.risk-profile-tag*') || request()->routeIs('admin.customer-groups*') ? 'active' : '' }}">
                {{ __('Customer') }}
            </a>
        </li>
        @canany(['role-list','role-create','role-edit'])
            <li class="nav-item">
                <a href="{{route('admin.roles.index')}}" class="navItem {{ isActive('admin.roles*') }}">
                    {{__('Roles & Permissions') }}
                </a>
            </li>
        @endcanany
        <li>
            <a href="{{ route('admin.kyclevels.index') }}" class="navItem {{ isActive('admin.kyclevels.index') }}">
                {{ __('KYC & Compliance') }}
            </a>
        </li>
        @canany(['ranking-list','ranking-create','ranking-edit'])
            <li>
                <a href="{{ route('admin.ranking.index') }}" class="navItem {{ isActive('admin.ranking*') }}">
                    <span>{{ __('User Rankings') }}</span>
                </a>
            </li>
        @endcan
    </ul>
@endsection
