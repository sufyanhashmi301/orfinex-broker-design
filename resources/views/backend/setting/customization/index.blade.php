@extends('backend.layouts.dual-sidebar')
@section('title')
    {{ __('Website Setting') }}
@endsection
@section('content')
    @include('backend.setting.customization.include.__side_nav')
    @yield('customization-content')
@endsection
@section('script')
    <script>
        new SimpleBar($("#sidebar_subMenus, #scrollModal")[0]);
    </script>

    @yield('customization-script')
@endsection

