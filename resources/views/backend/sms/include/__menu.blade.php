<div class="innerMenu card p-4 mb-5">
    <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
        <li class="nav-item">
            <a href="{{ route('admin.template.sms.index') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.template.sms.index') }}">
                {{ __('admin') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.template.sms.user-template') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.template.sms.user-template') }}">
                {{ __('User') }}
            </a>
        </li>
    </ul>
</div>
