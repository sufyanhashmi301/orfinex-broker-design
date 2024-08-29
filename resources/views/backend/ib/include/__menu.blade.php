<div class="innerMenu card p-4 mb-5">
    <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
        @canany(['ib-list','ib-action'])
            <li class="nav-item">
                <a href="{{ route('admin.ib.pending.list') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.ib.pending.list') }}">
                    {{ __('Pending IB') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.ib.approved.list') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.ib.approved.list') }}">
                    {{ __('Approved IB') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.ib.rejected.list') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.ib.rejected.list') }}">
                    {{ __('Rejected IB') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.ib.all.list') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.ib.all.list') }}">
                    {{ __('All IB Logs') }}
                </a>
            </li>
        @endcanany
        @can('ib-form-manage')
            <li class="nav-item">
                <a href="{{ route('admin.ib-form.index') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.ib-form*') }}">
                    {{ __('IB Form') }}
                </a>
            </li>
        @endcan
        @can('advertisement-material-edit')
            <li class="nav-item">
                <a href="{{route('admin.advertisement_material.index')}}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.advertisement_material*') }}">
                    {{ __('IB Resources') }}
                </a>
            </li>
        @endcan
    </ul>
</div>
