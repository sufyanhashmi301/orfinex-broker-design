@extends('backend.layouts.dual-sidebar')
@section('title')
    {{ __('Organization Setting') }}
@endsection
@section('content')
    @include('backend.setting.organization.include.__side_nav')
    @yield('organization-content')
@endsection
@section('script')
    <script>
        new SimpleBar($("#sidebar_subMenus, #scrollModal")[0]);
    </script>

    @yield('organization-script')
@endsection

