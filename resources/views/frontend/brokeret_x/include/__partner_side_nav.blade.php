<div x-show="sidebarOpen"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="sidebarOpen = false"
    class="fixed inset-0 bg-white bg-opacity-75 backdrop-blur-sm z-10 lg:hidden"></div>
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed bg-white top-(--header-height) start-0 h-full transition-all duration-300 z-20 flex flex-col items-stretch flex-shrink-0 w-(--sidebar-width) lg:w-16 xl:w-(--sidebar-width) in-data-[sidebar-open=false]:-start-full border-e border-border">
    <div class="overflow-y-auto grow shrink-0 flex flex-col px-2.5 py-2.5 me-0.5 pe-2 lg:px-1 xl:px-2.5 h-[calc(100vh-12rem)] lg:h-[calc(100vh-12rem)]">
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
            <ul class="flex flex-col flex-1 gap-2 py-2">
                @foreach($menuItems as $item)
                    @if($item['show'])
                        <li class="{{ $item['class'] ?? '' }}">
                            <a href="{{ route($item['route']) }}" 
                               @click="selected = (selected === '{{ $item['label'] }}' ? '':'{{ $item['label'] }}')"
                               class="flex items-center px-3 py-2 rounded-md transition-colors text-theme-sm gap-3 border border-transparent lg:px-2 lg:justify-center xl:px-3 xl:justify-start"
                               :class="currentRoute === '{{ $item['route'] }}' ? 'text-gray-900 bg-gray-100' : 'text-gray-700 hover:bg-gray-100 hover:border-gray-500 dark:hover:border-gray-200'">
                                <i data-lucide="{{ $item['icon'] }}" class="shrink-0 size-5"></i>
                                <span class="menu-item-text lg:hidden xl:block">
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