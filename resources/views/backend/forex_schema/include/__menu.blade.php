<div class="innerMenu card p-4 mb-5">
    <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
        @can('schema-edit')
            <li class="nav-item">
                <a href="{{route('admin.accountType.index')}}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.accountType*') }}">
                    {{ __('Account Type') }}
                </a>
            </li>
        @endcan
        @can('schema-edit')
            <li class="nav-item">
                <a href="{{route('admin.ibAccountType.index')}}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.ibAccountType*') }}">
                    {{ __('IB Account Type') }}
                </a>
            </li>
        @endcan
    </ul>
</div>
