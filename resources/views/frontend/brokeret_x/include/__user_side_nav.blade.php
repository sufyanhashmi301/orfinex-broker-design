<!-- Mobile backdrop -->
<div x-show="sidebarOpen"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="sidebarOpen = false"
    class="fixed inset-0 bg-white bg-opacity-75 backdrop-blur-sm z-10 lg:hidden dark:bg-gray-900 dark:bg-opacity-75"></div>
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed bg-white dark:bg-gray-900 top-(--header-height) start-0 h-full transition-all duration-300 z-20 flex flex-col items-stretch flex-shrink-0 w-(--sidebar-width) lg:w-16 xl:w-(--sidebar-width) in-data-[sidebar-open=false]:-start-full border-e border-border dark:border-gray-800">
    <div class="overflow-y-auto grow shrink-0 flex flex-col px-2.5 py-2.5 me-0.5 pe-2 lg:px-1 xl:px-2.5 h-[calc(100vh-12rem)] lg:h-[calc(100vh-12rem)]">
        <!-- Sidebar Menu -->
        @php
            // Simple menu items array - fallback to ensure navigation works
            $menuItems = [
                [
                    'route' => 'user.forex-account-logs',
                    'icon' => 'chart-line',
                    'label' => 'My Accounts',
                    'show' => true
                ],
                [
                    'route' => 'user.wallet.index',
                    'icon' => 'wallet',
                    'label' => 'Wallets',
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
                    'route' => 'user.withdraw.methods',
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
                    'route' => 'user.kyc',
                    'icon' => 'settings',
                    'label' => 'Settings',
                    'show' => true,
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
        <div class="lg:hidden">
            <div class="relative border-b border-gray-200 dark:border-gray-800 py-2" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                <div class="w-full flex items-center p-3 gap-2 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors"
                    @click.prevent="dropdownOpen = !dropdownOpen">
                    <div class="flex items-center gap-3">
                        <div class="text-gray-600 dark:text-gray-400">
                            <i data-lucide="circle-user" class="shrink-0 size-5"></i>
                        </div>
                        <div>
                            <p class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">
                                {{ auth()->user()->full_name }}
                            </p>
                            <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                                {{ auth()->user()->email }}
                            </span>
                        </div>
                    </div>
                    <div class="ms-auto text-gray-500 dark:text-gray-400">
                        <i data-lucide="chevron-down" class="shrink-0 size-5" x-show="!dropdownOpen"></i>
                        <i data-lucide="chevron-up" class="shrink-0 size-5" x-show="dropdownOpen"></i>
                    </div>
                </div>
                <div x-show="dropdownOpen" class="flex flex-col pt-3">
                    <ul class="flex flex-col gap-1">
                        <li>
                            <a href="{{ route('user.setting.profile') }}" class="text-theme-sm flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                {{ __('Edit profile') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.change.password') }}" class="text-theme-sm flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                {{ __('Change Password') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.ticket.index') }}" class="text-theme-sm flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                {{ __('Support') }}
                            </a>
                        </li>
                    </ul>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a href="{{ url('logout') }}" onclick="event.preventDefault(); localStorage.clear(); document.getElementById('logout-form').submit();" 
                        class="group text-theme-sm mt-3 flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 border-t border-gray-200 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                            <svg class="fill-gray-500 group-hover:fill-gray-700 dark:group-hover:fill-gray-300" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M15.1007 19.247C14.6865 19.247 14.3507 18.9112 14.3507 18.497L14.3507 14.245H12.8507V18.497C12.8507 19.7396 13.8581 20.747 15.1007 20.747H18.5007C19.7434 20.747 20.7507 19.7396 20.7507 18.497L20.7507 5.49609C20.7507 4.25345 19.7433 3.24609 18.5007 3.24609H15.1007C13.8581 3.24609 12.8507 4.25345 12.8507 5.49609V9.74501L14.3507 9.74501V5.49609C14.3507 5.08188 14.6865 4.74609 15.1007 4.74609L18.5007 4.74609C18.9149 4.74609 19.2507 5.08188 19.2507 5.49609L19.2507 18.497C19.2507 18.9112 18.9149 19.247 18.5007 19.247H15.1007ZM3.25073 11.9984C3.25073 12.2144 3.34204 12.4091 3.48817 12.546L8.09483 17.1556C8.38763 17.4485 8.86251 17.4487 9.15549 17.1559C9.44848 16.8631 9.44863 16.3882 9.15583 16.0952L5.81116 12.7484L16.0007 12.7484C16.4149 12.7484 16.7507 12.4127 16.7507 11.9984C16.7507 11.5842 16.4149 11.2484 16.0007 11.2484L5.81528 11.2484L9.15585 7.90554C9.44864 7.61255 9.44847 7.13767 9.15547 6.84488C8.86248 6.55209 8.3876 6.55226 8.09481 6.84525L3.52309 11.4202C3.35673 11.5577 3.25073 11.7657 3.25073 11.9984Z"
                                    fill="" />
                            </svg>
                            {{ __('Sign out') }}
                        </a>
                    </form>
                </div>
            </div>
            <div class="relative border-b border-gray-200 dark:border-gray-800 py-2" x-data="{ dropdownOpen: false, switcherToggle: false }" @click.outside="dropdownOpen = false">
                <button class="w-full flex items-center px-3 py-2 text-base rounded-md transition-colors text-theme-sm gap-2"
                    :class="dropdownOpen ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-gray-800' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800'"
                    @click.prevent="dropdownOpen = !dropdownOpen">
                    <i data-lucide="wallet" class="shrink-0 size-5"></i>
                    <div :class="switcherToggle ? 'blur-sm select-none' : ''">
                        <span class="font-medium">{{ data_get($mainWallet,'amount') + data_get($ibWallet,'amount') }}</span>
                        <span class="font-normal">{{ $currency }}</span>
                    </div>
                    <div class="ms-auto text-gray-500 dark:text-gray-400">
                        <i data-lucide="chevron-down" class="shrink-0 size-5" x-show="!dropdownOpen"></i>
                        <i data-lucide="chevron-up" class="shrink-0 size-5" x-show="dropdownOpen"></i>
                    </div>
                </button>

                <div x-show="dropdownOpen" class="flex flex-col p-3">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-800">
                        <span class="text-theme-sm text-gray-700 dark:text-gray-400">{{ __('Hide balance') }}</span>
                        
                        <label for="toggle2" class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                            <div class="relative">
                                <input type="checkbox" id="toggle2" class="sr-only" @change="switcherToggle = !switcherToggle" @click="$event.target.blur()">
                                <div class="block h-5 w-9 rounded-full" :class="switcherToggle ? 'bg-brand-500 dark:bg-brand-500' : 'bg-gray-200 dark:bg-white/10'"></div>
                                <div :class="switcherToggle ? 'translate-x-full': 'translate-x-0'" class="shadow-theme-sm absolute top-0.5 left-0.5 h-4 w-4 rounded-full bg-white duration-300 ease-linear"></div>
                            </div>
                        </label>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-800">
                        <div class="flex flex-col dark:text-white/90 gap-1 py-3">
                            <div class="flex items-center gap-1" :class="switcherToggle ? 'blur-sm select-none' : ''">
                                <span class="text-base font-semibold">{{ data_get($mainWallet,'amount') }}</span>
                                <span class="text-base font-normal">{{ $currency }}</span>
                            </div>
                            <div class="text-theme-xs text-gray-500 dark:text-gray-400">{{ __('Main Wallet') }}</div>
                            <span class="text-theme-xs text-gray-500 dark:text-gray-400">{{ __('E-') }}{{ data_get($mainWallet,'wallet_id') }}</span>
                            <div class="flex gap-2 mt-3">
                                <x-frontend::link-button href="{{ route('user.deposit.methods') }}" variant="secondary" size="sm">
                                    {{ __('Deposit') }}
                                </x-frontend::link-button>
                                <x-frontend::link-button href="{{ route('user.transfer') }}" variant="secondary" size="sm">
                                    {{ __('Transfer') }}
                                </x-frontend::link-button>
                            </div>
                        </div>
                        <div class="flex flex-col dark:text-white/90 gap-1 py-3">
                            <div class="flex items-center gap-1" :class="switcherToggle ? 'blur-sm select-none' : ''">
                                <span class="text-base font-semibold">{{ data_get($ibWallet,'amount') }}</span>
                                <span class="text-base font-normal">{{ $currency }}</span>
                            </div>
                            <div class="text-theme-xs text-gray-500 dark:text-gray-400">{{ __('IB Wallet') }}</div>
                            <span class="text-theme-xs text-gray-500 dark:text-gray-400">{{ __('IB-') }}{{ data_get($ibWallet,'wallet_id') }}</span>
                            <div class="flex gap-2 mt-3">
                                <x-frontend::link-button href="{{ route('user.deposit.methods') }}" variant="secondary" size="sm">
                                    {{ __('Deposit') }}
                                </x-frontend::link-button>
                                <x-frontend::link-button href="{{ route('user.transfer') }}" variant="secondary" size="sm">
                                    {{ __('Transfer') }}
                                </x-frontend::link-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                               :class="currentRoute === '{{ $item['route'] }}' ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-gray-800' : 'text-gray-700 hover:bg-gray-100 hover:border-gray-500 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:border-gray-600'">
                                <i data-lucide="{{ $item['icon'] }}" class="text-[#6c8595] shrink-0 size-5"></i>
                                <span class="menu-item-text tracking-wide lg:hidden xl:block">
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