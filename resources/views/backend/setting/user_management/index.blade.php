@extends('backend.layouts.dual-sidebar')
@section('title')
    {{ __('User Management') }}
@endsection
@section('content')
    @include('backend.setting.user_management.include.__side_nav')
    @yield('user-management-content')
@endsection
@section('script')
    <script>
        new SimpleBar($("#sidebar_subMenus, #scrollModal")[0]);
    </script>

    @yield('user-management-script')
@endsection
