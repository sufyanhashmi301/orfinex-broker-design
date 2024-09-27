@extends('backend.layouts.dual-sidebar')
@section('title')
    {{ __('Misc Setting') }}
@endsection
@section('content')
    @include('backend.setting.misc.include.__side_nav')
    @yield('misc-content')
@endsection
@section('script')
    <script>
        new SimpleBar($("#sidebar_subMenus, #scrollModal")[0]);
    </script>

    @yield('misc-script')
@endsection
