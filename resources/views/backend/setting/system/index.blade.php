@extends('backend.layouts.dual-sidebar')
@section('title')
    {{ __('System Setting') }}
@endsection
@section('content')
    @include('backend.setting.system.include.__side_nav')
    @yield('system-content')
@endsection
@section('script')
    <script>
        new SimpleBar($("#sidebar_subMenus, #scrollModal")[0]);
    </script>

    @yield('system-script')
@endsection
