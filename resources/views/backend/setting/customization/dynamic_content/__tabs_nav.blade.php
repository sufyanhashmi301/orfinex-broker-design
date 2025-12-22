<div class="flex justify-between flex-wrap items-center mb-6">
    <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
        @yield('title')
    </h4>
</div>
<div class="card p-4 mb-5">
    <ul class="nav nav-pills flex items-center overflow-x-auto list-none pl-0 pb-1 md:pb-0 gap-4 menu-open w-full">
        <li class="nav-item">
            <a href="{{ route('admin.settings.dynamic-content.success-page') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->routeIs('admin.settings.dynamic-content.success-page') ? 'active' : '' }}">
                {{ __('Success Page') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.settings.dynamic-content.error-page') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->routeIs('admin.settings.dynamic-content.error-page') ? 'active' : '' }}">
                {{ __('Error Page') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:;" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300">
                {{ __('404 Page') }}
            </a>
        </li>
    </ul>
</div>