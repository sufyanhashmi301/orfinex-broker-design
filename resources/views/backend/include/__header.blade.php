<!-- BEGIN: Header -->
<div class="z-[9] sticky top-0" id="app_header">
    <div class="app-header z-[999] ltr:ml-[248px] rtl:mr-[248px] dark:shadow-slate-700">
        <div class="flex justify-between items-center h-full">
            <div class="flex items-center md:space-x-4 space-x-2 xl:space-x-0 rtl:space-x-reverse vertical-box">
                <a href="{{route('home')}}" class="mobile-logo xl:hidden flex items-center">
                    @php
                        $logoSrc = setting('site_favicon','global')
                            ? asset(setting('site_favicon','global'))
                            : asset('backend/images/example_favicon.png');
                    @endphp
                    <img src="{{ $logoSrc }}" class="black_logo h-8" alt="logo">
                    <img src="{{ $logoSrc }}" class="white_logo h-8" alt="logo">
                    <span class="logo-title ltr:ml-3 rtl:mr-3 text-lg font-Inter font-medium text-white">
                        {{ __('Backoffice') }}
                    </span>
                </a>
                <button class="smallDeviceMenuController lg:h-[32px] lg:w-[32px] header-text-color rounded-lg hidden md:inline-block xl:hidden flex items-center justify-center">
                    <iconify-icon class="leading-none bg-transparent relative text-xl top-[2px] header-text-color" icon="heroicons-outline:menu-alt-3"></iconify-icon>
                </button>
                <p class="xl:block hidden text-white font-light">
                    {{ setting('site_title','common_settings') }}
                </p>
            </div>

            <div class="nav-tools flex items-center md:space-x-4 space-x-3 rtl:space-x-reverse leading-0">
                <div>
                    <button class="h-[28px] w-[28px] lg:h-[32px] lg:w-[32px] bg-slate-50 bg-opacity-10 text-white cursor-pointer rounded-lg text-[20px] flex flex-col items-center justify-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <iconify-icon class="text-white text-xl" icon="lucide:plus"></iconify-icon>
                    </button>
                    <div class="dropdown-menu z-10 hidden bg-white min-w-max dark:bg-slate-800 border dark:border-slate-700 !top-[10px] rounded-md overflow-hidden p-5">
                        <div class="flex gap-6">
                            <div class="space-y-5">
                                <p class="flex items-center text-sm font-medium uppercase dark:text-white">
                                    <iconify-icon class="text-base mr-2" icon="lucide:layout-dashboard"></iconify-icon>
                                    {{ __('General') }}
                                </p>
                                <ul class="space-y-3">
                                    <li class="pb-2">
                                        <a href="{{ route('admin.user.create') }}">
                                            <div class="flex space-x-2 items-start text-sm rtl:space-x-reverse">
                                                <iconify-icon icon="lucide:circle-plus" class="leading-[1]"> </iconify-icon>
                                                <span class="leading-[1]">
                                                    {{ __('Add New Customer') }}
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="space-y-5">
                                <p class="flex items-center text-sm font-medium uppercase dark:text-white">
                                    <iconify-icon class="text-base mr-2" icon="mdi:finance"></iconify-icon>
                                    {{ __('Finance') }}
                                </p>
                                <ul class="space-y-3">
                                    <li class="pb-2">
                                        <a href="{{ route('admin.deposit.add') }}">
                                            <div class="flex space-x-2 items-start text-sm rtl:space-x-reverse">
                                                <iconify-icon icon="lucide:circle-plus" class="leading-[1]"> </iconify-icon>
                                                <span class="leading-[1]">
                                                    {{ __('Add Deposit') }}
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="pb-2">
                                        <a href="{{ route('admin.withdraw.add') }}">
                                            <div class="flex space-x-2 items-start text-sm rtl:space-x-reverse">
                                                <iconify-icon icon="lucide:circle-plus" class="leading-[1]"> </iconify-icon>
                                                <span class="leading-[1]">
                                                    {{ __('Add Withdrawal') }}
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="space-y-5">
                                <p class="flex items-center text-sm font-medium uppercase dark:text-white">
                                    <iconify-icon class="text-base mr-2" icon="mdi:file-cog-outline"></iconify-icon>
                                    {{ __('Manage') }}
                                </p>
                                <ul class="space-y-3">
                                    @canany(['role-list','role-create','role-edit','staff-list','staff-create','staff-edit'])
                                        @canany(['staff-list','staff-create','staff-edit'])
                                            <li class="pb-2">
                                                <a href="{{route('admin.staff.index')}}">
                                                    <div class="flex space-x-2 items-start text-sm rtl:space-x-reverse">
                                                        <iconify-icon icon="lucide:circle-plus" class="leading-[1]"> </iconify-icon>
                                                        <span class="leading-[1]">
                                                            {{ __('Add New Staff') }}
                                                        </span>
                                                    </div>
                                                </a>
                                            </li>
                                        @endcanany
                                    @endcanany

                                    @can('schema-create')
                                        <li class="pb-2">
                                            <a href="{{ route('admin.accountType.create') }}">
                                                <div class="flex space-x-2 items-start text-sm rtl:space-x-reverse">
                                                    <iconify-icon icon="lucide:circle-plus" class="leading-[1]"> </iconify-icon>
                                                    <span class="leading-[1]">
                                                        {{ __('Account Type') }}
                                                    </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endcan

                                    @canany(['deposit-list','deposit-action',
                                            'withdraw-list','withdraw-action','target-manage','referral-create',
                                            'referral-list','referral-edit','referral-delete','ranking-list','ranking-create','ranking-edit'])
                                        @canany(['deposit-list','deposit-action'])
                                            <li class="pb-2">
                                                <a href="{{ route('admin.deposit.method.list','auto') }}">
                                                    <div class="flex space-x-2 items-start text-sm rtl:space-x-reverse">
                                                        <iconify-icon icon="lucide:circle-plus" class="leading-[1]"> </iconify-icon>
                                                        <span class="leading-[1]">
                                                            {{ __('Deposit Method') }}
                                                        </span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="pb-2">
                                                <a href="{{ route('admin.withdraw.method.list','auto') }}">
                                                    <div class="flex space-x-2 items-start text-sm rtl:space-x-reverse">
                                                        <iconify-icon icon="lucide:circle-plus" class="leading-[1]"> </iconify-icon>
                                                        <span class="leading-[1]">
                                                            {{ __('Withdrawal Method') }}
                                                        </span>
                                                    </div>
                                                </a>
                                            </li>
                                        @endcanany
                                    @endcanany
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Toggle Theme -->
                <div>
                    <button id="themeMood" class="h-[28px] w-[28px] lg:h-[32px] lg:w-[32px] hover:bg-slate-50 hover:bg-opacity-10 text-white cursor-pointer rounded-lg text-[20px] flex flex-col items-center justify-center">
                        <iconify-icon class="text-white text-xl dark:block hidden" id="moonIcon" icon="line-md:sunny-outline-to-moon-alt-loop-transition"></iconify-icon>
                        <iconify-icon class="text-white text-xl dark:hidden block" id="sunIcon" icon="line-md:moon-filled-to-sunny-filled-loop-transition"></iconify-icon>
                    </button>
                </div>
                <div class="relative md:block hidden">
                    <button class="h-[28px] w-[28px] lg:h-[32px] lg:w-[32px] hover:bg-slate-50 hover:bg-opacity-10 text-white cursor-pointer rounded-lg text-[20px] flex flex-col items-center justify-center" type="button" data-toggle="fullscreen">
                        <iconify-icon class="text-white text-xl" icon="mdi:fullscreen"></iconify-icon>
                    </button>
                </div>
                <!-- END: TOggle Theme -->
                <div class="relative md:block hidden admin-notifications">
                    @php
                        $loggedInAdmin = auth()->user(); // Get the logged-in admin

                        if ($loggedInAdmin->hasRole('Super-Admin')) {
                            // Super-Admin: No user filter
                            $notifications = App\Models\Notification::where('for', 'admin')->latest()->take(4)->get();
                            $totalUnread = App\Models\Notification::where('for', 'admin')->where('read', 0)->count();
                            $totalCount = App\Models\Notification::where('for', 'admin')->count();
                        } else {
                            // Non Super-Admin: Apply attached user filter
                            $attachedUserIds = $loggedInAdmin->users->pluck('id');
                            $notifications = App\Models\Notification::where('for', 'admin')
                                ->whereIn('user_id', $attachedUserIds) // Filter by attached users
                                ->latest()
                                ->take(4)
                                ->get();
                            $totalUnread = App\Models\Notification::where('for', 'admin')
                                ->whereIn('user_id', $attachedUserIds)
                                ->where('read', 0)
                                ->count();
                            $totalCount = App\Models\Notification::where('for', 'admin')
                                ->whereIn('user_id', $attachedUserIds)
                                ->count();
                        }
                    @endphp

                    @include('global.__notification_data', ['notifications' => $notifications, 'totalUnread' => $totalUnread, 'totalCount' => $totalCount])

                </div>
                <div class="relative md:block hidden">
                    <a href="{{ route('admin.settings.index') }}" class="h-[28px] w-[28px] lg:h-[32px] lg:w-[32px] hover:bg-slate-50 hover:bg-opacity-10 text-white cursor-pointer rounded-lg text-[20px] flex flex-col items-center justify-center">
                        <iconify-icon class="text-white text-xl" icon="lucide:settings"></iconify-icon>
                    </a>
                </div>
                <div class="relative">
                    <button class="text-slate-800 dark:text-white focus:ring-0 focus:outline-none font-medium text-sm text-center inline-flex items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="lg:h-8 lg:w-8 h-7 w-7 rounded-full">
                            <img src="{{ asset('frontend/images/all-img/user.png') }}" alt="user" class="block w-full h-full object-cover rounded-full">
                        </div>
                    </button>
                    <div class="dropdown-menu z-10 hidden bg-white divide-y divide-slate-100 shadow min-w-max dark:bg-slate-800 border dark:border-slate-700 !top-[10px] rounded-md overflow-hidden">
                        <ul class="py-1 text-sm text-slate-800 dark:text-slate-200">
                            <li>
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                    <iconify-icon icon="lucide:user" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Profile') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.password-change') }}" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                    <iconify-icon icon="lucide:lock" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Change Password') }}</span>
                                </a>
                            </li>
                            <li class="logout">
                                <a href="{{ url('admin/logout') }}" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal" type="button"
                                onclick="event.preventDefault(); localStorage.clear();  $('#logout-form').submit();">
                                    <iconify-icon icon="lucide:log-out" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Logout') }}</span>
                                </a>
                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="relative md:block hidden">
                    <a href="{{ route('admin.activePositions') }}" class="h-[28px] w-[28px] lg:h-[32px] lg:w-[32px] text-white cursor-pointer rounded-lg flex flex-col items-center justify-center">
                        <iconify-icon class="text-white text-2xl" icon="mdi:dots-grid"></iconify-icon>
                    </a>
                </div>
                <button class="smallDeviceMenuController md:hidden block leading-0">
                    <iconify-icon class="cursor-pointer header-text-color text-2xl" icon="heroicons-outline:menu-alt-3"></iconify-icon>
                </button>
            </div>
        </div>
    </div>
</div>
