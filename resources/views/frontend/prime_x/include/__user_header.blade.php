<!-- BEGIN: Header -->
<div class="z-[9] sticky top-0" id="app_header">
    <div class="app-header z-[999] ltr:ml-[248px] rtl:mr-[248px] dark:shadow-slate-700">
        <div class="flex justify-between items-center h-full">
            {{--<p class="text-xl font-medium text-slate-900 dark:text-white">--}}
                {{--@yield('title')--}}
            {{--</p>--}}

            {{--<div class="relative md:block hidden">
                <button class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded text-[20px] flex flex-col items-center justify-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <iconify-icon class="text-slate-800 dark:text-white text-xl" icon="mdi:dots-grid"></iconify-icon>
                </button>
                <!-- Mail Dropdown -->
                <div class="dropdown-menu z-10 hidden bg-white divide-y divide-slate-100 shadow w-44 dark:bg-slate-800 border dark:border-slate-700 !top-[23px] rounded-md overflow-hidden">
                    <ul class="py-1 text-sm text-slate-800 dark:text-slate-200">
                        <li>
                            <a href="{{ route('webterminal') }}" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                <iconify-icon icon="gala:terminal" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                <span class="font-Inter">{{ __('Webterminal') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>--}}
            <div class="flex items-center md:space-x-4 space-x-2 xl:space-x-0 rtl:space-x-reverse vertical-box">
                <a href="{{ route('user.dashboard') }}" class="mobile-logo xl:hidden inline-block">
                    <img src="{{ asset(setting('site_favicon','global')) }}" class="black_logo h-8" alt="logo">
                    <img src="{{ asset(setting('site_favicon','global')) }}" class="white_logo h-8" alt="logo">
                </a>
                <button class="smallDeviceMenuController hidden md:inline-block xl:hidden">
                    <iconify-icon class="leading-none bg-transparent relative text-xl top-[2px] text-slate-900 dark:text-white" icon="heroicons-outline:menu-alt-3"></iconify-icon>
                </button>
            </div>

            <div class="relative">
                <div class="flex items-center text-left">
                    <p class="dark:text-white">{{auth()->user()->full_name}}</p>
                    @if(isset($user->kyc) && $user->kyc->status == \App\Enums\KycStatusEnums::VERIFIED)
                        <img src="https://cdn.brokeret.com/crm-assets/admin/kyc/verified.svg" class="inline-flex ml-2 mt-1" alt="" style="height: 14px;">
                    @else
                        <img src="https://cdn.brokeret.com/crm-assets/admin/kyc/unverified.svg" class="inline-flex ml-2 mt-1" alt="" style="height: 14px;">
                    @endif
                </div>
            </div>
            <!-- end vertcial -->

            <div class="nav-tools flex items-center lg:space-x-5 space-x-3 rtl:space-x-reverse leading-0 ml-auto">
                <div>
                    <button id="themeMood" class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-lg text-[20px] flex flex-col items-center justify-center">
                        <iconify-icon class="text-slate-800 dark:text-white text-xl dark:block hidden" id="moonIcon" icon="line-md:sunny-outline-to-moon-alt-loop-transition"></iconify-icon>
                        <iconify-icon class="text-slate-800 dark:text-white text-xl dark:hidden block" id="sunIcon" icon="line-md:moon-filled-to-sunny-filled-loop-transition"></iconify-icon>
                    </button>
                </div>

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
                <div class="md:block hidden w-full">
                    <button class="text-slate-800 dark:text-white focus:ring-0 focus:outline-none font-medium rounded-lg text-sm text-center inline-flex items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="lg:h-8 lg:w-8 h-7 w-7 rounded-full flex-1 border-2" style="border-color: #0ebe3b;">
                            <img src="@if(auth()->user()->avatar && file_exists('assets/'.auth()->user()->avatar)) {{asset($user->avatar)}} @else {{ asset('frontend/images/all-img/user.png') }}@endif" alt="user" class="block w-full h-full object-cover rounded-full">
                        </div>
                    </button>
                    <!-- Dropdown menu -->
                    <div class="dropdown-menu z-10 hidden bg-white divide-y divide-slate-100 shadow min-w-max border dark:border-slate-700 !top-[10px] rounded-md overflow-hidden">
                        <ul class="py-1 text-sm text-slate-800 dark:text-slate-200">
                            <li>
                                <a href="{{ route('user.setting.profile') }}" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                    <iconify-icon icon="lucide:settings" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Settings') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.change.password') }}" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                    <iconify-icon icon="lucide:lock-keyhole" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Change Password') }}</span>
                                </a>
                            </li>
                            @if(setting('user_ranking', 'permission',false))
                            <li>
                                <a href="{{ route('user.ranking-badge') }}" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                    <iconify-icon icon="lucide:star" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Ranking Badge') }}</span>
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ route('user.ticket.index') }}" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                    <iconify-icon icon="lucide:headphones" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Support Tickets') }}</span>
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <a href="{{ url('logout') }}" onclick="event.preventDefault(); localStorage.clear();  $('#logout-form').submit();" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                        <iconify-icon icon="lucide:power" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                        <span class="font-Inter">{{ __('Logout') }}</span>
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Profile DropDown Area -->

                {{-- <div class="md:block hidden">
                    <a href="javascript:;" class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-lg text-[20px] flex flex-col items-center justify-center">
                        <iconify-icon class="text-slate-800 dark:text-white text-2xl" icon="mdi:dots-grid"></iconify-icon>
                    </a>
                </div> --}}

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
