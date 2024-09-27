@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li>
            <a href="{{ route('admin.settings.mail') }}" class="navItem {{ isActive('admin.settings.mail') }}">
                {{ __('Email') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.plugin','system') }}" class="navItem {{ isActive('admin.settings.plugin') }}">
                {{ __('Notification') }}
            </a>
        </li>
        <li>
            <a href="{{route('admin.settings.slack')}}" class="navItem {{isActive('admin.settings.slack')}}">
                {{ __('Collab Tools')}}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.email-template') }}" class="navItem {{ isActive('admin.email-template*') }}">
                {{ __('Email Templates')}}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.template.sms.index') }}" class="navItem {{ isActive('admin.template.sms*') }}">
                {{ __('SMS Templates')}}
            </a>
        </li>
    </ul>
@endsection
