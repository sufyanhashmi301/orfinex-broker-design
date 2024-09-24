@extends('backend.layouts.dual-sidebar')
@section('title')
    {{ __('Communications Setting') }}
@endsection
@section('content')
    @include('backend.setting.communication.include.__side_nav')
    @yield('communication-content')
@endsection
@section('script')
    <script>
        new SimpleBar($("#sidebar_subMenus, #scrollModal")[0]);
    </script>

    @yield('communication-script')
@endsection

