<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" class="light">
@include('backend.include.__head')

<body class="font-inter dashcode-app" id="body_class">
    <div class="page-loader">
        <div class="dot bg-primary"></div>
        <div class="dot bg-primary"></div>
        <div class="dot bg-primary"></div>
    </div>
    <!--Full Layout-->
    <main class="app-wrapper">

        <x:notify-messages/>

        <!--Side Nav-->
        <div class="sidebar-wrapper group dark:shadow-slate-700">
            @include('backend.include.__side_nav')
        </div>
        <!--/Side Nav-->

        <div class="flex flex-col justify-between min-h-screen">
            <div>
                <!--Header-->
                @include('backend.include.__header')
                <!--/Header-->

                <div class="content-wrapper transition-all duration-150 ltr:ml-[248px] rtl:mr-[248px]" id="content_wrapper">
                    <!--Page Content-->
                    <div class="page-content">
                        <div class="transition-all duration-150 container-fluid" id="page_layout">
                            <div id="content_layout">
                                <div>
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Page Content-->
                </div>
            </div>
            <footer class="md:block sticky bottom-0 z-10" id="footer">
                <div class="site-footer px-6 text-slate-500 dark:text-slate-300 py-2 ltr:ml-[248px] rtl:mr-[248px]" style="height: 48px;">
                    <div class="flex items-center justify-between gap-5">
                        @if(!setting('is_whitelabel', 'global'))
                            <a href="https://brokeret.com/" target="_blank" class="text-primary font-semibold ml-1">
                                <img src="{{ asset('backend/images/brokeret_logo.png') }}" class="h-6 inline-flex" alt="">
                            </a>
                        @endif
                        <div class="ltr:md:text-right rtl:md:text-end text-center text-sm ml-auto">
                            <span class="toolTip onTop" style="line-height: 0" data-tippy-content="Your data is fully secure with advanced end-to-end encryption between you and your broker, ensuring that all sensitive client information and trading activities remain confidential. Technology Provider guarantees no access to or visibility of your encrypted data, safeguarding your privacy and trust.">
                                <span id="secure-data" style="display: inline-flex; width: 24px; height: 24px;"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>
    <!--/Full Layout-->

    @include('backend.include.__script')


</body>
</html>
