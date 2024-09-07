<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" class="light">
@include('backend.include.__head')

<body class="font-inter dashcode-app" id="body_class">
    <!--Full Layout-->
    <main class="app-wrapper">

        <x:notify-messages/>

        <!--Side Nav-->
        <div class="sidebar-wrapper group">
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
                    <div class="page-content !p-0">
                        <div class="transition-all duration-150 container-fluid" id="page_layout">
                            <div id="content_layout">
                                <div>
                                    @include('backend.include.__submenu')
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Page Content-->
                </div>
            </div>
        </div>
    </main>
    <!--/Full Layout-->

    @include('backend.include.__script')


</body>
</html>