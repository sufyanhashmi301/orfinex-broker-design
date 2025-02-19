@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        @can('email-setting')
        <li>
            <a href="{{ route('admin.settings.mail') }}" class="navItem {{ isActive('admin.settings.mail') }}">
                {{ __('Email') }}
            </a>
        </li>
        @endcan
        @can('collab-tools-setting')
        <li>
            <a href="{{route('admin.settings.slack')}}" class="navItem {{isActive('admin.settings.slack')}}">
                {{ __('Collab Tools')}}
            </a>
        </li>
        @endcan
        @canany(['admin-email-template', 'user-email-template'])
        <li>
            <a href="{{ route('admin.email-template') }}" class="navItem {{ isActive('admin.email-template*') }}">
                {{ __('Email Templates')}}
            </a>
        </li>
        @endcanany
        @canany(['admin-sms-template', 'user-sms-template'])
        <li>
            <a href="{{ route('admin.template.sms.index') }}" class="navItem {{ isActive('admin.template.sms*') }}">
                {{ __('SMS Templates')}}
            </a>
        </li>
        @endcanany
    </ul>
@endsection
