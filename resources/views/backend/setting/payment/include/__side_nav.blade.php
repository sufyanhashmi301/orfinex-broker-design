@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li>
            <a href="{{ route('admin.deposit.method.list','auto') }}" class="navItem {{ isActive('admin.deposit.method.list*') }}">
                {{ __('Deposit Methods') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.withdraw.method.list','auto') }}" class="navItem {{ isActive('admin.withdraw.method.list*') }}">
                {{ __('Withdraw Methods') }}
            </a>
        </li>
        @can('bonus-list')
        <li>
            <a href="{{ route('admin.bonus.index') }}" class="navItem {{ isActive('admin.bonus*') }}">
                {{ __('Bonuses') }}
            </a>
        </li>
        @endcan
        <li>
            <a href="{{ route('admin.settings.currency') }}" class="navItem {{ isActive('admin.settings.currency') }}">
                {{ __('Currency')}}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.transfers', ['type' => 'internal']) }}" class="navItem {{ isActive('admin.settings.transfers*') }}">
                {{ __('Transfers')}}
            </a>
        </li>
    </ul>
@endsection
