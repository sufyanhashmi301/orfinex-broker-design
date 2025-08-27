<aside :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0">
    <!-- SIDEBAR HEADER -->
    <div :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="flex items-center gap-2 py-3 lg:py-7 mb-3 lg:mb-0 sidebar-header">
        <a href="{{ route('home') }}">  
            <span class="logo" :class="sidebarToggle ? 'hidden' : ''">
                <img class="h-10 dark:hidden" src="{{ getFilteredPath(setting('site_logo', 'global'), 'fallback/branding/desktop-logo.png') }}" alt="Logo" />
                <img class="h-10 hidden dark:block" src="{{ getFilteredPath(setting('site_logo_light', 'global'), 'fallback/branding/desktop-logo.png') }}" alt="Logo" />
            </span>
            <span :class="sidebarToggle ? 'lg:block' : 'hidden'">
                <img class="logo-icon h-10 dark:hidden" src="{{ getFilteredPath(setting('site_mobile_logo', 'global'), 'fallback/branding/desktop-logo.png') }}" alt="Logo" />
                <img class="logo-icon h-10 hidden dark:block" src="{{ getFilteredPath(setting('site_mobile_logo_light', 'global'), 'fallback/branding/desktop-logo.png') }}" alt="Logo" />
            </span>
        </a>
    </div>
    <!-- SIDEBAR HEADER -->

    <div class="flex flex-col grow pb-4 overflow-y-auto duration-300 ease-linear no-scrollbar">
        <!-- Sidebar Menu -->
        @php
            // Simple menu items array - fallback to ensure navigation works
            $menuItems = [
                [
                    'route' => 'user.dashboard',
                    'icon' => 'layout-grid',
                    'label' => 'Dashboard',
                    'show' => true
                ],
                [
                    'route' => 'user.wallet.index',
                    'icon' => 'wallet',
                    'label' => 'Wallets',
                    'show' => true
                ],
                [
                    'route' => 'user.forex-account-logs',
                    'icon' => 'chart-line',
                    'label' => 'My Accounts',
                    'show' => true
                ],
                [
                    'route' => 'user.schema',
                    'icon' => 'plus-circle',
                    'label' => 'New Account',
                    'show' => true
                ],
                [
                    'route' => 'user.deposit.methods',
                    'icon' => 'download',
                    'label' => 'Deposit',
                    'show' => true
                ],
                [
                    'route' => 'user.transfer',
                    'icon' => 'arrow-right-left',
                    'label' => 'Transfer',
                    'show' => setting('is_internal_transfer', 'transfer_internal') || setting('is_external_transfer', 'transfer_external')
                ],
                [
                    'route' => 'user.withdraw.view',
                    'icon' => 'upload',
                    'label' => 'Withdraw',
                    'show' => true
                ],
                [
                    'route' => 'user.follower_access',
                    'icon' => 'copy',
                    'label' => 'Copy Trading',
                    'show' => setting('is_copy_trading', 'copy_trading')
                ],
                [
                    'route' => 'user.history.transactions',
                    'icon' => 'clock',
                    'label' => 'History',
                    'show' => true
                ],
                [
                    'route' => 'user.multi-level.ib.dashboard',
                    'icon' => 'users',
                    'label' => 'Partner Area',
                    'show' => auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED || isset(auth()->user()->ref_id)
                ],
                [
                    'route' => 'user.ib.request',
                    'icon' => 'user-plus',
                    'label' => 'Request Master IB',
                    'show' => auth()->user()->ib_status != \App\Enums\IBStatus::APPROVED && !isset(auth()->user()->ref_id)
                ],
                [
                    'route' => 'user.ticket.index',
                    'icon' => 'life-buoy',
                    'label' => 'Tickets',
                    'show' => setting('user_tickets_feature', 'customer_permission')
                ],
                [
                    'route' => 'user.setting.profile',
                    'icon' => 'settings',
                    'label' => 'Settings',
                    'show' => true,
                    'class' => 'mt-auto',
                    'active_check' => fn() => str_contains(request()->route()->getName(), 'user.setting')
                ],
            ];

            // Get current active item for persistence
            $currentRoute = request()->route()->getName();
            $activeLabel = '';
            foreach ($menuItems as $item) {
                if (isset($item['active_check']) && is_callable($item['active_check']) && $item['active_check']()) {
                    $activeLabel = $item['label'];
                    break;
                } elseif (request()->routeIs($item['route'])) {
                    $activeLabel = $item['label'];
                    break;
                }
            }
        @endphp
        
        <nav x-data="{ 
            selected: $persist('{{ $activeLabel }}'),
            currentRoute: '{{ $currentRoute }}'
        }"
        class="flex flex-col flex-1">
            <!-- Menu Group -->
            <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
                    MENU
                </span>

                <i data-lucide="more-horizontal" :class="sidebarToggle ? 'lg:block hidden' : 'hidden'" class="mx-auto menu-group-icon"></i>
            </h3>

            <ul class="flex flex-col flex-1 gap-4">
                @foreach($menuItems as $item)
                    @if($item['show'])
                        <li class="{{ $item['class'] ?? '' }}">
                            <a href="{{ route($item['route']) }}" 
                               @click="selected = (selected === '{{ $item['label'] }}' ? '':'{{ $item['label'] }}')"
                               class="menu-item group"
                               :class="currentRoute === '{{ $item['route'] }}' ? 'menu-item-active' : 'menu-item-inactive'">
                                <i data-lucide="{{ $item['icon'] }}"></i>
                                <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                    {{ __($item['label']) }}
                                </span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
    </div>
</aside>