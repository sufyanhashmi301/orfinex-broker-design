<div class="card p-4 mb-5">
    <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
        @can('branding-settings')
        <li class="nav-item">
            <a href="{{ route('admin.theme.global') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.theme.global') }}">
                {{ __('global settings') }}
            </a>
        </li>
         @endcan
        @can('company-logo-settings')
        <li class="nav-item">
            <a href="{{ route('admin.theme.company-logo') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.theme.company-logo') }}">
                {{ __('provider logo') }}
            </a>
        </li>
         @endcan
    </ul>
</div>
