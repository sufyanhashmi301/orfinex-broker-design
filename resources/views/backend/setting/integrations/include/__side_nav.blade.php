@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        @can('payment-gateways-list')
            <li>
                <a href="{{ route('admin.gateway.automatic') }}" class="navItem {{ isActive('admin.gateway.automatic') }}">
                    {{ __('Payment Gateways') }}
                </a>
            </li>
        @endcan
        @can('plugins-list')
        <li>
            <a href="{{ route('admin.settings.plugin','system') }}" class="navItem {{ isActive('admin.settings.plugin','system') }}">
                {{ __('Plugins')}}
            </a>
        </li>
        @endcan
        @can('sms-list')
        <li>
            <a href="{{ route('admin.settings.plugin','sms') }}" class="navItem {{ isActive('admin.settings.plugin','sms') }}">
                {{__('SMS Settings') }}
            </a>
        </li>
        @endcan
        @can('notification-list')
        <li>
            <a href="{{ route('admin.settings.plugin','notification') }}" class="navItem {{ isActive('admin.settings.plugin','notification') }}">
                {{__('Notification Settings') }}
            </a>
        </li>
        @endcan
        @can('api-access-setting')
        <li>
            <a href="{{ route('admin.settings.apiAccess') }}" class="navItem {{ isActive('admin.settings.apiAccess') }}">
                {{ __('API Access')}}
            </a>
        </li>
        @endcan
        @can('web-hooks-setting')
        <li>
            <a href="{{ route('admin.settings.webHook') }}" class="navItem {{ isActive('admin.settings.webHook') }}">
                {{ __('Web Hooks')}}
            </a>
        </li>
        @endcan
    </ul>
@endsection
