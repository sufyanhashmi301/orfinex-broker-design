<!DOCTYPE html>
<html lang="zxx" dir="ltr" class="light">
@include('frontend::include.__head')
<body class="font-inter dashcode-app" id="body_class">
@include('notify::components.notify')
<!--Full Layout-->
<main class="app-wrapper">

    <div class="sidebar-wrapper group dark:shadow-slate-700">
        @include('frontend::include.__user_side_nav')
    </div>

    <div class="flex flex-col justify-between min-h-screen">
        <div>
            <!--Header-->
            @include('frontend::include.__user_header')
            <!--/Header-->

            {{-- KYC --}}
            @if(setting('kyc_verification','permission'))
                {{-- Kyc Info--}}
                @if(!Route::is(['webterminal', 'user.verification*', 'user.ticket*']) && show_kyc_notice()['show'])
                    <div class="md:block hidden">
                        @include('frontend::user.include.__kyc_info', ['notice' => show_kyc_notice()['message']])
                    </div>
                @endif
                <div class="md:hidden block">
                    {{--@include('frontend::user.mobile_screen_include.kyc.__user_kyc_mobile')--}}
                </div>
            @endif

            <div class="content-wrapper transition-all duration-150 ltr:ml-[248px] rtl:mr-[248px]" id="content_wrapper">
                <div class="page-content">
                  <div class="transition-all duration-150 container-fluid" id="page_layout">
                        <div id="content_layout">
                            <div>
                                
                                <!--Page Content-->
                                @yield('content')
                                <!--Page Content-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Show in 575px in Mobile Screen -->
        <div class="mobile-screen-show md:hidden">
            <div class="bg-white bg-no-repeat custom-dropshadow footer-bg dark:bg-slate-700 flex justify-around items-center backdrop-filter backdrop-blur-[40px] fixed left-0 bottom-0 w-full z-[9999] bothrefm-0 py-[12px] px-4">
                @include('frontend::dashboard.mobile_screen_include.__menu')
            </div>
        </div>
        <!-- Show in 575px in Mobile Screen End -->

    </div>
    <!-- Automatic Popup -->
    @if(Session::get('signup_bonus'))
        @include('frontend::user.include.__signup_bonus')
    @endif

    <!-- /Automatic Popup End -->
</main>
<!--/Full Layout-->

    @include('frontend::include.__script')
    @stack('single-script')
    {!! setting('site_user_footer_code', 'defaults') !!}

</body>
</html>

