@extends('backend.layouts.dual-sidebar')
@section('title')
    {{ __('Website Setting') }}
@endsection
@section('content')
    @include('backend.setting.website.include.__side_nav')
    @yield('website-content')
@endsection
@section('script')
    <script>
        new SimpleBar($("#sidebar_subMenus, #scrollModal")[0]);
    </script>

    @yield('website-script')
@endsection

