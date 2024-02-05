<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!--Head-->
@include('backend.include.__head')
<!--/Head-->
<body>


<!--Auth Page-->
<div class="admin-auth" style="background: url(https://cloud.orfinex.com/crm/orfinexlogin.png) no-repeat center center;background-size:cover;">
    <x:notify-messages/>
    @yield('auth-content')
</div>
<!--/Auth Page-->

<!--Script-->
@include('backend.include.__script')
<!--/Script-->

</body>
</html>
