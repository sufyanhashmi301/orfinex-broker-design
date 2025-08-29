<div class="innerMenu card p-4 mb-5">
    <ul class="nav nav-pills flex items-center overflow-x-auto list-none gap-4 pl-0 pb-1 sm:pb-0 menu-open">
        @canany(['payment-deposit-form-manage'])
            <li class="nav-item">
                <a href="{{ route('admin.payment-deposit-form.index') }}"
                    class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.payment-deposit-form*') }}">
                    {{ __('Manage Forms') }}
                </a>
            </li>
        @endcanany
        @canany(['payment-deposit-list', 'payment-deposit-action'])
            <li class="nav-item">
                <a href="{{ route('admin.payment-deposit.pending.list') }}"
                    class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.payment-deposit.pending.list') }}">
                    {{ __('Pending Requests') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.payment-deposit.approved.list') }}"
                    class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.payment-deposit.approved.list') }}">
                    {{ __('Approved Requests') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.payment-deposit.rejected.list') }}"
                    class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.payment-deposit.rejected.list') }}">
                    {{ __('Rejected Requests') }}
                </a>
            </li>
        @endcanany

        <li class="nav-item !ml-auto">
            <a href="javascript:;"
                class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 filter-toggle-btn">
                <span class="flex items-center">
                    <span>{{ __('More') }}</span>
                    <iconify-icon icon="lucide:chevron-down"
                        class="text-base ltr:ml-2 rtl:mr-2 font-light"></iconify-icon>
                </span>
            </a>
        </li>
    </ul>

    <div class="hidden mt-5" id="filters_div">
        @yield('filters')
    </div>
</div>
