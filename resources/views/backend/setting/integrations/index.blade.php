@extends('backend.layouts.dual-sidebar')
@section('title')
    {{ __('Website Integrations') }}
@endsection
@section('content')
    @include('backend.setting.integrations.include.__side_nav')
    @yield('integrations-content')
@endsection
@section('script')
    <script>
        new SimpleBar($("#sidebar_subMenus, #scrollModal")[0]);
    </script>

    @yield('integrations-script')
@endsection

