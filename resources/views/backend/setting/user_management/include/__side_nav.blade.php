@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        @canany(['risk-profile-list','system-tag-list','customer-group-list','ib-group-list','customer-permissions'])
        <li>
            <a href="{{ route('admin.risk-profile-tag.index') }}" class="navItem {{ request()->routeIs('admin.risk-profile-tag*') || request()->routeIs('admin.customer-groups*') || request()->routeIs('admin.settings.customer.misc') ? 'active' : '' }}">
                {{ __('Customer') }}
            </a>
        </li>
        @endcanany
        @canany(['role-list','role-create','role-edit'])
            <li class="nav-item">
                <a href="{{route('admin.roles.index')}}" class="navItem {{ isActive('admin.roles*') }}">
                    {{__('Roles & Permissions') }}
                </a>
            </li>
        @endcanany
        @canany(['lead-source-list','lead-pipeline-list'])
        <li class="nav-item">
            <a href="{{ route('admin.lead.source.index') }}" class="navItem {{ isActive('admin.lead*') }}">
                {{ __('Lead Settings') }}
            </a>
        </li>
        @endcanany
        @can('kyc-levels-list')
        <li>
            <a href="{{ route('admin.kyclevels.index') }}" class="navItem {{ Route::is('admin.kyclevels*') || Route::is('admin.settings.kyclevels.permissions') ? 'active' : '' }}
">
                {{ __('KYC & Compliance') }}
            </a>
        </li>
        @endcan
        @canany(['ranking-list','ranking-create','ranking-edit'])
            <li>
                <a href="{{ route('admin.ranking.index') }}" class="navItem {{ isActive('admin.ranking*') }}">
                    <span>{{ __('User Rankings') }}</span>
                </a>
            </li>
        @endcan
    </ul>
@endsection
