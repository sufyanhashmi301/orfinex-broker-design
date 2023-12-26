<!-- BEGIN: Header -->
<div class="z-[9] sticky top-0" id="app_header">
    <div class="app-header z-[999] ltr:ml-[248px] rtl:mr-[248px] bg-white dark:bg-slate-800 shadow-sm dark:shadow-slate-700">
        <div class="flex justify-between items-center h-full">
            <div class="flex items-center md:space-x-4 space-x-2 xl:space-x-0 rtl:space-x-reverse vertical-box">
                <a href="{{route('home')}}" class="mobile-logo xl:hidden inline-block">
                    <img src="{{ asset(setting('site_logo','global')) }}" class="h-10" alt="Logo"/>
                </a>
                <button class="smallDeviceMenuController hidden md:inline-block xl:hidden">
                    <iconify-icon class="leading-none bg-transparent relative text-xl top-[2px] text-slate-900 dark:text-white" icon="heroicons-outline:menu-alt-3"></iconify-icon>
                </button>
            </div>
            <!-- end vertcial -->

            <div class="nav-tools flex items-center lg:space-x-5 space-x-3 rtl:space-x-reverse leading-0">
                <!-- BEGIN: Language Dropdown  -->
                <div class="relative">
                    <button class="text-slate-800 dark:text-white focus:ring-0 focus:outline-none font-medium rounded-lg text-sm text-center inline-flex items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <iconify-icon icon="circle-flags:uk" class="mr-0 md:mr-2 rtl:ml-2 text-xl"></iconify-icon>
                        <span class="text-sm md:block hidden font-medium text-slate-600 dark:text-slate-300">En</span>
                    </button>
                    <!-- Language Dropdown menu -->
                    <div class="dropdown-menu z-10 hidden bg-white divide-y divide-slate-100 shadow w-44 dark:bg-slate-800 border dark:border-slate-900 !top-[25px] rounded-md overflow-hidden">
                        <ul class="py-1 text-sm text-slate-800 dark:text-slate-200">
                            @foreach(\App\Models\Language::where('status',true)->get() as $lang)
                                <li>
                                    <a href="{{ route('language-update',['name'=> $lang->locale]) }}" class="flex items-center px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                        <iconify-icon icon="circle-flags:uk" class="ltr:mr-2 rtl:ml-2 text-xl"></iconify-icon>
                                        <span class="font-medium">{{$lang->name}}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- Theme Changer -->
                <!-- END: Language Dropdown -->

                <!-- BEGIN: Toggle Theme -->
                <div>
                    <button id="themeMood" class="h-[28px] w-[28px] lg:h-[32px] lg:w-[32px] lg:bg-gray-500-f7 bg-slate-50 dark:bg-slate-900 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center">
                        <iconify-icon class="text-slate-800 dark:text-white text-xl dark:block hidden" id="moonIcon" icon="line-md:sunny-outline-to-moon-alt-loop-transition"></iconify-icon>
                        <iconify-icon class="text-slate-800 dark:text-white text-xl dark:hidden block" id="sunIcon" icon="line-md:moon-filled-to-sunny-filled-loop-transition"></iconify-icon>
                    </button>
                </div>
                <!-- END: TOggle Theme -->

                <!-- BEGIN: gray-scale Dropdown -->
                <div>
                    <button id="grayScale" class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center">
                        <iconify-icon class="text-slate-800 dark:text-white text-xl" icon="mdi:paint-outline"></iconify-icon>
                    </button>
                </div>
                <!-- END: gray-scale Dropdown -->

                <!-- BEGIN: Notification Dropdown -->
                @auth
                    @php
                        $userId = auth()->id();
                        $notifications = App\Models\Notification::where('for','user')->where('user_id', $userId)->latest()->take(4)->get();
                        $totalUnread = App\Models\Notification::where('for','user')->where('user_id', $userId)->where('read', 0)->count();
                        $totalCount = App\Models\Notification::where('for','user')->where('user_id', $userId)->get()->count();
                    @endphp
                    <div class="relative md:block hidden">
                        @include('global.__notification_data',['notifications'=>$notifications,'totalUnread'=>$totalUnread,'totalCount'=>$totalCount])
                    </div>
                @endauth
                <!-- Notifications Dropdown area -->

                <!-- BEGIN: Profile Dropdown -->
                <!-- Profile DropDown Area -->
                <div class="md:block hidden w-full">
                    <button class="text-slate-800 dark:text-white focus:ring-0 focus:outline-none font-medium rounded-lg text-sm text-center inline-flex items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="lg:h-8 lg:w-8 h-7 w-7 rounded-full flex-1 ltr:mr-[10px] rtl:ml-[10px]">
                            <img src="{{ asset('frontend/images/all-img/user.png') }}" alt="user" class="block w-full h-full object-cover rounded-full">
                        </div>
                        <span class="flex-none text-slate-600 dark:text-white text-sm font-normal items-center lg:flex hidden overflow-hidden text-ellipsis whitespace-nowrap">
                            Albert Flores
                        </span>
                        <svg class="w-[16px] h-[16px] dark:text-white hidden lg:inline-block text-base inline-block ml-[10px] rtl:mr-[10px]" aria-hidden="true" fill="none" stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div class="dropdown-menu z-10 hidden bg-white divide-y divide-slate-100 shadow min-w-max dark:bg-slate-800 border dark:border-slate-700 !top-[23px] rounded-md overflow-hidden">
                        <ul class="py-1 text-sm text-slate-800 dark:text-slate-200">
                            <li>
                                <a href="{{ route('user.setting.show') }}" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                    <iconify-icon icon="lucide:settings" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Settings') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.change.password') }}" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                    <iconify-icon icon="lucide:lock-keyhole" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Change Password') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.ranking-badge') }}" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                    <iconify-icon icon="lucide:star" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Ranking Badge') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.ticket.index') }}" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                    <iconify-icon icon="lucide:headphones" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Support Tickets') }}</span>
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <a href="{{ url('logout') }}" onclick="event.preventDefault(); localStorage.clear();  $('#logout-form').submit();" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                        <iconify-icon icon="lucide:power" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                        <span class="font-Inter">{{ __('Logout') }}</span>
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- END: Header -->
                <button class="smallDeviceMenuController md:hidden block leading-0">
                    <iconify-icon class="cursor-pointer text-slate-900 dark:text-white text-2xl" icon="heroicons-outline:menu-alt-3"></iconify-icon>
                </button>
                <!-- end mobile menu -->
            </div>
            <!-- end nav tools -->
        </div>
    </div>
</div>
<!-- END: Header -->
@push('script')
    <script>
        // Color Switcher
        $(".color-switcher").on('click', function () {
            "use strict"
            $("body").toggleClass("dark-theme");
            var url = '{{ route("mode-theme") }}';
            $.get(url)
        });
    </script>
@endpush
