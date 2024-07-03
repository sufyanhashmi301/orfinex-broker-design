<div class="submenu-sidebar hidden xl:block">
    <div class="border-b border-slate-100 dark:border-slate-700 py-3 px-2">
        <div class="input-area">
            <div class="relative group">
                <input type="text" class="form-control !pl-9" placeholder="Search">
                <button class="absolute left-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center">
                    <iconify-icon icon="heroicons-solid:search"></iconify-icon>
                </button>
            </div>
        </div>
    </div>
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li>
            <a href="" class="navItem {{ isActive(['admin.user*','admin.notification*']) }}">
                {{ __('Customers') }}
            </a>
        </li>
        <li>
            <a href="" class="navItem">
                {{ __('Compliance & KYC')}}
            </a>
        </li>
    </ul>
</div>