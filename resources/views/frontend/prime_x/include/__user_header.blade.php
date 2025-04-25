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
            <div class="flex items-center md:space-x-4 space-x-2 mr-2 xl:space-x-0 rtl:space-x-reverse vertical-box">
                <a href="{{ route('user.dashboard') }}" class="mobile-logo xl:hidden inline-block active">
                    <img src="{{ asset(setting('site_favicon','global')) }}" class="black_logo h-8" alt="logo">
                    <img src="{{ asset(setting('site_favicon','global')) }}" class="white_logo h-8" alt="logo">
                </a>
                <button class="smallDeviceMenuController lg:h-[32px] lg:w-[32px] header-text-color rounded-lg hidden md:inline-block xl:hidden flex items-center justify-center">
                    <iconify-icon class="leading-none bg-transparent relative text-xl top-[2px] header-text-color" icon="heroicons-outline:menu-alt-3"></iconify-icon>
                </button>
            </div>
            @if(Route::is(['user.follower_access', 'user.provider_access', 'user.ratings']))
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <ul class="nav nav-tabs custom-tabs inline-flex items-center overflow-hidden rounded list-none border-0 pl-0">
                                <li class="nav-item">
                                    <a href="{{ route('user.follower_access') }}" class="btn btn-sm !text-nowrap inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.follower_access') }}">
                                        {{ __('Follower Access') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('user.provider_access') }}" class="btn btn-sm !text-nowrap inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.provider_access') }}">
                                        {{ __('Provider Access') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('user.ratings') }}" class="btn btn-sm !text-nowrap inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.ratings') }}">
                                        {{ __('Ratings') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <div class="relative">
                    <div class="flex items-center text-left">
                        <p class="header-text-color">{{auth()->user()->full_name}}</p>
                        @if($user->kyc >= \App\Enums\KYCStatus::Level2->value)
                            <img src="https://cdn.brokeret.com/crm-assets/admin/kyc/verified.svg" class="inline-flex ml-2 mt-1" alt="" style="height: 14px;">
                        @else
                            <img src="https://cdn.brokeret.com/crm-assets/admin/kyc/unverified.svg" class="inline-flex ml-2 mt-1" alt="" style="height: 14px;">
                        @endif
                    </div>
                </div>
            @endif
{{--            @if(setting('enc_mode', 'end_to_end_encryption'))--}}
{{--            <div class="ltr:md:text-right rtl:md:text-end text-center text-sm">--}}
{{--                <span class="toolTip onTop" style="line-height: 0"--}}
{{--                      data-tippy-content="Your data is fully secure with end-to-end encryption, ensuring all transactions and information are protected.">--}}
{{--                        <span id="lottie-container" style="display: inline-flex; width: 24px; height: 24px;"></span>--}}
{{--                </span>--}}
{{--            </div>--}}
{{--            @endif--}}
            <!-- end vertcial -->

            <div class="nav-tools flex items-center md:space-x-4 space-x-3 rtl:space-x-reverse leading-0 ml-auto">
                <div>
                    <form action="{{ route('user.setting.preference.theme') }}" method="POST">
                        @csrf
                        @if(auth()->user()->user_theme == 'light')
                            <input type="hidden" name="user_theme" value="dark">
                            <button type="submit" class="lg:h-[32px] lg:w-[32px] dark:text-slate-900 text-white cursor-pointer rounded-lg text-[20px] flex flex-col items-center justify-center">
                                <iconify-icon class="header-text-color text-xl" id="moonIcon" icon="line-md:sunny-outline-to-moon-alt-loop-transition"></iconify-icon>
                            </button>
                        @elseif(auth()->user()->user_theme == 'dark')
                            <input type="hidden" name="user_theme" value="light">
                            <button type="submit" class="lg:h-[32px] lg:w-[32px] dark:text-slate-900 text-white cursor-pointer rounded-lg text-[20px] flex flex-col items-center justify-center">
                                <iconify-icon class="header-text-color text-xl" id="sunIcon" icon="line-md:moon-filled-to-sunny-filled-loop-transition"></iconify-icon>
                            </button>
                        @endif
                    </form>
                </div>
                <div class="relative hidden md:block">
                    <button class="lg:h-[32px] lg:w-[32px] cursor-pointer rounded-lg text-[20px] flex flex-col items-center justify-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <iconify-icon class="header-text-color text-xl" icon="lucide:globe"></iconify-icon>
                    </button>
                    <!-- Dropdown menu -->
                    <div class="dropdown-menu z-10 hidden bg-white divide-y divide-slate-100 shadow w-44 border dark:border-slate-700 !top-[10px] rounded-md overflow-hidden">
                        <ul class="py-1 text-sm text-slate-800 dark:text-slate-200">
                            @foreach(\App\Models\Language::where('status',true)->get() as $lang)
                                <li>
                                    <a href="{{ route('language-update',['name'=> $lang->locale]) }}" class="loaderBtn block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                        <span class="font-Inter">{{$lang->name}}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
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
                <div>
                    <button class="item notification-dot lg:h-[32px] lg:w-[32px] dark:text-slate-900 text-white cursor-pointer rounded-lg text-[20px] hidden md:flex flex-col items-center justify-center" type="button" data-toggle="fullscreen">
                        <iconify-icon class="header-text-color text-xl" icon="mdi:fullscreen"></iconify-icon>
                    </button>
                </div>

                <!-- BEGIN: Profile Dropdown -->
                <div class="md:block hidden w-full">
                    <button class="text-slate-800 dark:text-white focus:ring-0 focus:outline-none font-medium rounded-lg text-sm text-center inline-flex items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="lg:h-8 lg:w-8 h-7 w-7 rounded-full flex-1 border-2 border-primary">
                            <img src="{{ getFilteredPath(auth()->user()->avatar, 'fallback/user.png') }}" alt="user" class="block w-full h-full object-cover rounded-full">
                        </div>
                    </button>
                    <!-- Dropdown menu -->
                    <div class="dropdown-menu z-10 hidden bg-white divide-y divide-slate-100 shadow min-w-max border dark:border-slate-700 !top-[10px] rounded-md overflow-hidden">
                        <ul class="py-1 text-sm text-slate-800 dark:text-slate-200">
                            <li>
                                <a href="{{ route('user.setting.profile') }}" class="loaderBtn block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                    <iconify-icon icon="lucide:settings" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Settings') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.change.password') }}" class="loaderBtn block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                    <iconify-icon icon="lucide:lock-keyhole" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Change Password') }}</span>
                                </a>
                            </li>
                            @if(setting('user_ranking', 'permission',false))
                                <li>
                                    <a href="{{ route('user.ranking-badge') }}" class="loaderBtn block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                        <iconify-icon icon="lucide:star" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                        <span class="font-Inter">{{ __('Ranking Badge') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('user.ticket.index') }}" class="loaderBtn block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
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

                <div class="relative hidden md:block">
                    <button class="lg:h-[32px] lg:w-[32px] dark:text-slate-900 text-white cursor-pointer rounded-lg text-[20px] flex flex-col items-center justify-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <iconify-icon class="header-text-color text-2xl" icon="mdi:dots-grid"></iconify-icon>
                    </button>
                    <!-- Dropdown menu -->
                    <div class="dropdown-menu z-10 hidden bg-white divide-y divide-slate-100 shadow w-44 border dark:border-slate-700 !top-[10px] rounded-md overflow-hidden">
                        <ul class="py-1 text-sm text-slate-800 dark:text-slate-200">
                            <li>
                                <a href="{{ route('user.dashboard') }}" class="loaderBtn block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                                    <iconify-icon icon="material-symbols:dashboard-outline" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Personal area') }}</span>
                                </a>
                            </li>
                            @if(setting('is_webterminal','global'))
                            <li>
                                <a href="{{ route('webterminal') }}" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal" target="_blank">
                                    <iconify-icon icon="ic:outline-candlestick-chart" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Web terminal') }}</span>
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ setting('company_website', 'common_settings') }}" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal" target="_blank">
                                    <iconify-icon icon="tabler:world-www" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Public website') }}</span>
                                </a>
                            </li>
                            @if(auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED)
                            <li>
                                <a href="{{ route('user.multi-level.ib.dashboard') }}" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal" target="_blank">
                                    <iconify-icon icon="lucide:users-round" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    <span class="font-Inter">{{ __('Partnership') }}</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- END: Header -->
                <button class="smallDeviceMenuController md:hidden block leading-0">
                    <iconify-icon class="cursor-pointer header-text-color text-2xl" icon="heroicons-outline:menu-alt-3"></iconify-icon>
                </button>
                <!-- end mobile menu -->
            </div>
            <!-- end nav tools -->
        </div>
    </div>
</div>
@if(setting('kyc_verification','permission'))
    {{-- Kyc Info--}}
    @if(!Route::is(['webterminal', 'user.follower_access', 'user.provider_access', 'user.ratings', 'user.ticket*', 'user.kyc*']))
        <div class="md:block hidden">
            @include('frontend::user.include.__kyc_info')
        </div>
        <div class="md:hidden block">
            {{-- @include('frontend::user.mobile_screen_include.kyc.__user_kyc_mobile')--}}
        </div>
    @endif
@endif

<!-- END: Header -->
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.14/lottie.min.js"></script>
    <script>
        // Color Switcher
        $(".color-switcher").on('click', function () {
            "use strict"
            $("body").toggleClass("dark-theme");
            var url = '{{ route("mode-theme") }}';
            $.get(url)
        });
        var animation = lottie.loadAnimation({
            container: document.getElementById('lottie-container'), // ID of the div where the animation will render
            renderer: 'svg',  // Render the animation in SVG format
            loop: true,       // Loop the animation
            autoplay: true,   // Autoplay the animation
            path: '{{ asset('global/json/secure.json') }}' // Path to your JSON file
        });
    </script>
@endpush

