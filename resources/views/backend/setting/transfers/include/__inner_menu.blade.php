<div class="flex justify-between flex-wrap items-center mb-6">
    <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
        @yield('title')
    </h4>
</div>
<div class="card p-4 mb-5">
    <ul class="nav nav-pills flex items-center overflow-x-auto list-none pl-0 pb-1 md:pb-0 gap-4 menu-open">
        @can('internal-transfer-display')
            <li class="nav-item">
                <a href="{{ route('admin.settings.transfers', ['type' => 'transfer_internal']) }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ $type == 'transfer_internal' ? 'active' : '' }}">
                    {{ __('Internal Transfers') }}
                </a>
            </li>
        @endcan
        @can('external-transfer-display')
            <li class="nav-item">
                <a href="{{ route('admin.settings.transfers', ['type' => 'transfer_external']) }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ $type == 'transfer_external' ? 'active' : '' }}">
                    {{ __('External Transfers') }}
                </a>
            </li>
        @endcan
        @can('transfer-notification')
            <li class="nav-item">
                <a href="{{ route('admin.settings.transfers.notification-tune') }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.transfers.notification-tune') }}">
                    {{ __('Notification Tune') }}
                </a>
            </li>
        @endcan
        @can('internal-transfer-display')
            <li class="nav-item">
                <a href="{{ route('admin.settings.transfers', ['type' => 'transfer_misc']) }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ $type == 'transfer_misc' ? 'active' : '' }}">
                    {{ __('Misc') }}
                </a>
            </li>
        @endcan
    </ul>
</div>
