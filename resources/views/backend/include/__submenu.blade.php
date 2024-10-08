<div class="submenu-sidebar hidden xl:block dark:shadow-slate-700">
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
    <div class="sidebar-subMenus py-2 px-4 h-[calc(100%-80px)] overflow-y-auto z-50" id="sidebar_subMenus">
        @yield('submenu')
    </div>
</div>
