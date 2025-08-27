<aside :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0">
    <!-- SIDEBAR HEADER -->
    <div :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="flex items-center gap-2 py-7 sidebar-header">
        <a href="{{ route('home') }}">  
            <span class="logo" :class="sidebarToggle ? 'hidden' : ''">
                <img class="h-10 dark:hidden" src="{{ getFilteredPath(setting('site_logo', 'global'), 'fallback/branding/desktop-logo.png') }}" alt="Logo" />
                <img class="h-10 hidden dark:block" src="{{ getFilteredPath(setting('site_logo_light', 'global'), 'fallback/branding/desktop-logo.png') }}" alt="Logo" />
            </span>
            <span :class="sidebarToggle ? 'lg:block' : 'hidden'">
                <img class="logo-icon dark:hidden" src="{{ getFilteredPath(setting('site_mobile_logo', 'global'), 'fallback/branding/desktop-logo.png') }}" alt="Logo" />
                <img class="logo-icon dark:block" src="{{ getFilteredPath(setting('site_mobile_logo_light', 'global'), 'fallback/branding/desktop-logo.png') }}" alt="Logo" />
            </span>
        </a>
    </div>
    <!-- SIDEBAR HEADER -->

    <div class="flex flex-col grow pb-4 overflow-y-auto duration-300 ease-linear no-scrollbar">
        <!-- Sidebar Menu -->
        @php
            // Simple menu items array for partner sidebar
            $menuItems = [
                [
                    'route' => 'user.multi-level.ib.dashboard',
                    'icon' => 'layout-grid',
                    'label' => 'Dashboard',
                    'show' => auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED || isset(auth()->user()->ref_id)
                ],
                [
                    'route' => 'user.referral.network',
                    'icon' => 'network',
                    'label' => 'Network',
                    'show' => true
                ],
                [
                    'route' => 'user.referral.advertisement.material',
                    'icon' => 'layers',
                    'label' => 'Resources',
                    'show' => true
                ],
                [
                    'route' => 'user.referral.history',
                    'icon' => 'history',
                    'label' => 'History',
                    'show' => true
                ],
                [
                    'route' => 'user.referral.members',
                    'icon' => 'users-round',
                    'label' => 'Referrals',
                    'show' => true
                ],
                [
                    'route' => 'user.multi-level.ib.rules',
                    'icon' => 'list-checks',
                    'label' => 'Sub Ib Rules',
                    'show' => auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED
                ],
                [
                    'route' => 'user.dashboard',
                    'icon' => 'layout-grid',
                    'label' => 'Client Area',
                    'show' => true,
                    'class' => 'mt-auto'
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