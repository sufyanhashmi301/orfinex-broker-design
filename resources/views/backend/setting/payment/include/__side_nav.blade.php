@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li>
            <a href="{{ route('admin.payment-method.index','auto') }}" class="navItem {{ isActive('admin.payment-method*') }}">
                {{ __('Payment Methods') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.withdraw.method.list','auto') }}" class="navItem {{ isActive('admin.withdraw.method*') }}">
                {{ __('Payout Methods') }}
            </a>
        </li>
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
