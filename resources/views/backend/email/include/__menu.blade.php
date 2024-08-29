<div class="innerMenu card p-4 mb-5">
    <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
        <li class="nav-item">
            <a href="{{ route('admin.email-template') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.email-template') }}">
                {{ __('admin') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{route('admin.email-template.user')}}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.email-template.user') }}">
                {{ __('User') }}
            </a>
        </li>
    </ul>
</div>
