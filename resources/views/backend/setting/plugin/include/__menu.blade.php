<div class="card p-4 mb-5">
    <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
        <li class="nav-item">
            <a href="{{ route('admin.settings.mail') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.mail') }}">
                {{__('Email Settings') }}
            </a>
        </li>
        @can('plugin-setting')
            <li class="nav-item">
                <a href="{{ route('admin.settings.plugin','system') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.plugin','system') }}">
                    {{__('Plugin Settings') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.plugin','sms') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.plugin','sms') }}">
                    {{__('SMS Settings') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.plugin','notification') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.plugin','notification') ?? isActive('admin.settings.notification.tune') }}">
                    {{__('Notification Settings') }}
                </a>
            </li>
        @endcan
    </ul>
</div>