<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <!--Head-->
    @include('backend.include.__head')

    <!--/Head-->
    <body class="font-inter dashcode-app" id="body_class">
        <!--Auth Page-->
        <div class="loginwrapper flex-col justify-center items-center bg-cover bg-no-repeat bg-center p-8 lg:py-20 lg:px-0">
            <x:notify-messages/>
            @yield('auth-content')

        </div>
        <!--/Auth Page-->

        <!--Script-->
        @include('backend.include.__script')
        <!--/Script-->

    </body>
</html>
