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
                    <img src="{{ asset(setting('site_favicon','global')) }}" class="black_logo h-12" alt="logo">
                    <img src="{{ asset(setting('site_favicon','global')) }}" class="white_logo h-12" alt="logo">
                </a>
                <button class="smallDeviceMenuController hidden md:inline-block xl:hidden">
                    <iconify-icon class="leading-none bg-transparent relative text-xl top-[2px] text-slate-900 dark:text-white" icon="heroicons-outline:menu-alt-3"></iconify-icon>
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
                @if(setting('is_webterminal','global'))
                    <a href="{{ route('webterminal') }}" class="block loaderBtn lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 rounded flex flex-col items-center justify-center">
                        <img src="{{ asset('frontend/images/trading.png') }}" class="dark:invert" alt="" style="height: 24px">
                    </a>
                @endif
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

            <div class="nav-tools flex items-center lg:space-x-5 space-x-3 rtl:space-x-reverse leading-0 ml-auto">
                <!-- BEGIN: Profile Dropdown -->
                <div class="md:block hidden w-full">
                    <button class="text-slate-800 dark:text-white focus:ring-0 focus:outline-none font-medium rounded-lg text-sm text-center inline-flex items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="flex-none text-slate-600 dark:text-white text-base font-medium items-center lg:flex hidden overflow-hidden text-ellipsis whitespace-nowrap ltr:mr-[10px] rtl:ml-[10px]">
                            <div class="text-right">
                                <span>{{auth()->user()->full_name}}</span><br>
                                <span class="flex items-center justify-end text-slate-400 text-sm font-normal">
                                    {{ $user->rank->ranking }}
                                    <iconify-icon class="text-primary ml-1" icon="bxs:badge-check"></iconify-icon>
                                </span>
                            </div>
                        </div>
                        <div class="lg:h-8 lg:w-8 h-7 w-7 rounded-full flex-1 border-2 border-primary">
                            <img src="@if(auth()->user()->avatar && file_exists('assets/'.auth()->user()->avatar)) {{asset($user->avatar)}} @else {{ asset('frontend/images/all-img/user.png') }}@endif" alt="user" class="block w-full h-full object-cover rounded-full">
                        </div>
                    </button>
                    <!-- Dropdown menu -->
                    <div class="dropdown-menu z-10 hidden bg-white divide-y divide-slate-100 shadow min-w-max border dark:border-slate-700 !top-[23px] rounded-md overflow-hidden">
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

                <div class="md:block hidden relative">
                    <select name="language" class="form-control !py-1 min-w-max" onchange="window.location.href=this.options[this.selectedIndex].value;">
                        @foreach(\App\Models\Language::where('status',true)->get() as $lang)
                            <option value="{{ route('language-update',['name'=> $lang->locale]) }}" @selected( app()->getLocale() == $lang->locale )>
                                {{$lang->name}}
                            </option>
                        @endforeach
                    </select>
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

