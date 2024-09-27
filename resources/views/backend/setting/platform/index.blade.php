@extends('backend.layouts.dual-sidebar')
@section('title')
    {{ __('Platform Setting') }}
@endsection
@section('content')
    @include('backend.setting.platform.include.__side_nav')
    @yield('platform-content')
@endsection
@section('script')
    <script>
        new SimpleBar($("#sidebar_subMenus, #scrollModal")[0]);
    </script>

    @yield('platform-script')
@endsection
