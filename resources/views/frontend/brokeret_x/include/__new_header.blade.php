<header class="flex items-stretch fixed z-20 top-0 start-0 end-0 shrink-0 bg-white dark:bg-gray-900 border-b border-border dark:border-gray-800 h-(--header-height-mobile) lg:h-(--header-height)">
    <div class="@container grow px-4 flex items-center justify-between gap-2.5">
        <!-- Sidebar Toggle Button -->
        <button 
            @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden size-9.5 relative inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded border border-transparent text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-800 dark:focus:bg-gray-800"
        >
            <i data-lucide="menu" class="shrink-0 size-5" x-show="!sidebarOpen"></i>
            <i data-lucide="x" class="shrink-0 size-5" x-show="sidebarOpen"></i>
        </button>

        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex-none rounded-md text-xl inline-block font-semibold focus:outline-hidden focus:opacity-80">  
            <img class="h-10 dark:hidden" src="{{ getFilteredPath(setting('site_logo', 'global'), 'fallback/branding/desktop-logo.png') }}" alt="Logo" />
            <img class="h-10 hidden dark:block" src="{{ getFilteredPath(setting('site_logo_light', 'global'), 'fallback/branding/desktop-logo.png') }}" alt="Logo" />
        </a>
        <!-- End Logo -->

        <div class="flex items-center gap-2.5">
            <div class="flex items-center gap-2.5">
                <div class="relative hidden lg:block" x-data="{ dropdownOpen: false, switcherToggle: false }" @click.outside="dropdownOpen = false">
                    <button class="h-9.5 px-2.5 relative inline-flex justify-center items-center gap-x-1 text-sm font-semibold rounded border border-transparent text-gray-800 hover:bg-gray-100 hover:border-gray-600 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                        @click.prevent="dropdownOpen = !dropdownOpen">
                        <i data-lucide="wallet" class="shrink-0 size-5"></i>
                        <div :class="switcherToggle ? 'blur-sm select-none' : ''">
                            <span class="font-medium">{{ data_get($mainWallet,'amount') + data_get($ibWallet,'amount') }}</span>
                            <span class="font-normal">{{ $currency }}</span>
                        </div>
                    </button>

                    <div x-show="dropdownOpen"
                        class="shadow-theme-lg dark:bg-gray-dark absolute right-0 mt-[17px] flex w-[260px] flex-col rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-800">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-800">
                            <span class="text-theme-sm text-gray-700 dark:text-gray-400">{{ __('Hide balance') }}</span>
                            
                            <label for="toggle1" class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                                <div class="relative">
                                    <input type="checkbox" id="toggle1" class="sr-only" @change="switcherToggle = !switcherToggle" @click="$event.target.blur()">
                                    <div class="block h-5 w-9 rounded-full" :class="switcherToggle ? 'bg-brand-500 dark:bg-brand-500' : 'bg-gray-200 dark:bg-white/10'"></div>
                                    <div :class="switcherToggle ? 'translate-x-full': 'translate-x-0'" class="shadow-theme-sm absolute top-0.5 left-0.5 h-4 w-4 rounded-full bg-white duration-300 ease-linear"></div>
                                </div>
                            </label>
                        </div>
                        <div class="divide-y divide-gray-200 dark:divide-gray-800">
                            <div class="flex flex-col gap-1 py-3">
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
                            <div class="flex flex-col gap-1 py-3">
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

                <!-- Dark Mode Toggle -->
                 <div class="relative hidden lg:block">
                    <button 
                        @click="darkMode = !darkMode"
                        class="size-9.5 relative inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded border border-transparent text-gray-800 hover:bg-gray-100 hover:border-gray-600 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-800 dark:focus:bg-gray-800"
                    >
                        <i data-lucide="sun" class="shrink-0 size-5 dark:hidden"></i>
                        <i data-lucide="moon" class="shrink-0 size-5 hidden dark:block"></i>
                    </button>
                </div>
                
                <!-- Notification Menu Area -->
                @auth
                    @php
                        $userId = auth()->id();
                        $notifications = App\Models\Notification::where('for','user')->where('user_id', $userId)->latest()->take(4)->get();
                        $totalUnread = App\Models\Notification::where('for','user')->where('user_id', $userId)->where('read', 0)->count();
                        $totalCount = App\Models\Notification::where('for','user')->where('user_id', $userId)->get()->count();
                    @endphp
                    <div class="relative user-notifications{{$userId}}" x-data="{ dropdownOpen: false, notifying: true }" @click.outside="dropdownOpen = false">
                        @include('frontend::include.__notification_data',['notifications'=>$notifications,'totalUnread'=>$totalUnread,'totalCount'=>$totalCount])
                    </div>
                @endauth
                <!-- Notification Menu Area -->

                <div class="relative hidden lg:block" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                    <button type="button" class="size-9.5 relative inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded border border-transparent text-gray-800 hover:bg-gray-100 hover:border-gray-600 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" @click.prevent="dropdownOpen = ! dropdownOpen">
                        <i data-lucide="grip" class="shrink-0 size-5"></i>
                        <span class="sr-only">{{ __('Notifications') }}</span>
                    </button>

                    <!-- Dropdown Start -->
                    <div x-show="dropdownOpen"
                        class="shadow-theme-lg dark:bg-gray-dark absolute right-0 mt-[17px] flex w-[220px] flex-col rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-800">
                        <ul class="flex flex-col gap-1">
                            <li>
                                <a href="{{ route('user.forex-account-logs') }}" class="group text-theme-sm flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                    <i data-lucide="layout-dashboard" class="shrink-0 size-4"></i>
                                    {{ __('Personal Area') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.platform') }}" class="group text-theme-sm flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                    <i data-lucide="chart-candlestick" class="shrink-0 size-4"></i>
                                    {{ __('Web Terminal') }}
                                </a>
                            </li>
                            @if (auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED || isset(auth()->user()->ref_id))
                                <li>
                                    <a href="{{ route('user.multi-level.ib.dashboard') }}" class="group text-theme-sm flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                        <i data-lucide="users" class="shrink-0 size-4"></i>
                                        {{ __('Partnership') }}
                                    </a>
                                </li>
                            @elseif(auth()->user()->ib_status != \App\Enums\IBStatus::APPROVED && !isset(auth()->user()->ref_id))
                                <li>
                                    <a href="{{ route('user.ib.request') }}" class="group text-theme-sm flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                        <i data-lucide="users" class="shrink-0 size-4"></i>
                                        {{ __('Request Master IB') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <!-- Dropdown End -->
                </div>
            </div>

            <!-- User Area -->
            <div class="relative hidden lg:block" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                <button type="button" class="size-9.5 relative inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded border border-transparent text-gray-800 hover:bg-gray-100 hover:border-gray-600 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" @click.prevent="dropdownOpen = ! dropdownOpen">
                    <i data-lucide="circle-user" class="shrink-0 size-5"></i>
                    <span class="sr-only">{{ __('User Settings') }}</span>
                </button>

                <!-- Dropdown Start -->
                <div x-show="dropdownOpen" class="shadow-theme-lg dark:bg-gray-dark absolute right-0 mt-[17px] flex w-[260px] flex-col rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-800">
                    <div class="border-b border-gray-200 pb-3 dark:border-gray-800">
                        <div class="flex items-center gap-3">
                            <div class="w-full max-w-8 overflow-hidden rounded-full">
                                <img src="{{ getFilteredPath(auth()->user()->avatar, 'fallback/user.png') }}" alt="User" />
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
                    </div>

                    <ul class="flex flex-col gap-1 border-b border-gray-200 pt-4 pb-3 dark:border-gray-800">
                        <li>
                            <a href="{{ route('user.setting.profile') }}"
                                class="group text-theme-sm flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                <svg class="fill-gray-500 group-hover:fill-gray-700 dark:fill-gray-400 dark:group-hover:fill-gray-300"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z"
                                        fill="" />
                                </svg>
                                {{ __('Edit profile') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.change.password') }}" class="group text-theme-sm flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                <svg class="fill-gray-500 group-hover:fill-gray-700 dark:fill-gray-400 dark:group-hover:fill-gray-300" 
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" 
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 17a2 2 0 0 1-2-2c0-1.11.89-2 2-2a2 2 0 0 1 2 2a2 2 0 0 1-2 2m6 3V10H6v10zm0-12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10c0-1.11.89-2 2-2h1V6a5 5 0 0 1 5-5a5 5 0 0 1 5 5v2zm-6-5a3 3 0 0 0-3 3v2h6V6a3 3 0 0 0-3-3"
                                    fill=""/>
                                </svg>
                                {{ __('Change Password') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.ticket.index') }}"
                                class="group text-theme-sm flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                <svg class="fill-gray-500 group-hover:fill-gray-700 dark:fill-gray-400 dark:group-hover:fill-gray-300"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3.5 12C3.5 7.30558 7.30558 3.5 12 3.5C16.6944 3.5 20.5 7.30558 20.5 12C20.5 16.6944 16.6944 20.5 12 20.5C7.30558 20.5 3.5 16.6944 3.5 12ZM12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2ZM11.0991 7.52507C11.0991 8.02213 11.5021 8.42507 11.9991 8.42507H12.0001C12.4972 8.42507 12.9001 8.02213 12.9001 7.52507C12.9001 7.02802 12.4972 6.62507 12.0001 6.62507H11.9991C11.5021 6.62507 11.0991 7.02802 11.0991 7.52507ZM12.0001 17.3714C11.5859 17.3714 11.2501 17.0356 11.2501 16.6214V10.9449C11.2501 10.5307 11.5859 10.1949 12.0001 10.1949C12.4143 10.1949 12.7501 10.5307 12.7501 10.9449V16.6214C12.7501 17.0356 12.4143 17.3714 12.0001 17.3714Z"
                                        fill="" />
                                </svg>
                                {{ __('Support') }}
                            </a>
                        </li>
                    </ul>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a href="{{ url('logout') }}" onclick="event.preventDefault(); localStorage.clear(); document.getElementById('logout-form').submit();" 
                        class="group text-theme-sm mt-3 flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
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
                <!-- Dropdown End -->
            </div>
            <!-- User Area -->
        </div>
    </div>
</header>