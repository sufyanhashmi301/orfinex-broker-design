@extends('backend.layouts.dual-sidebar')
@section('title')
    {{ __('Data Management') }}
@endsection
@section('content')
    @include('backend.setting.data_management.include.__side_nav')
    @yield('data-management-content')
@endsection
@section('script')
    <script>
        new SimpleBar($("#sidebar_subMenus, #scrollModal")[0]);
    </script>

    @yield('data-management-script')
@endsection

