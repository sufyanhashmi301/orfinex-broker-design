@extends('frontend::layouts.user')
@section('style')
    <style>
        .page-content {
            padding: 0 !important;
        }
        .nav-tabs .nav-link.active {
            color: #FED000 !important;
            border-color: #FED000 !important;
        }
    </style>
@endsection
@section('content')
    @yield('copy-trading-content')
@endsection
