@extends('backend.layouts.dual-sidebar')
@section('title')
    {{ __('Payment Setting') }}
@endsection
@section('content')
    @include('backend.setting.payment.include.__side_nav')
    @yield('payment-content')
@endsection
@section('script')
    <script>
        new SimpleBar($("#sidebar_subMenus, #scrollModal")[0]);
    </script>

    @yield('payment-script')
@endsection
