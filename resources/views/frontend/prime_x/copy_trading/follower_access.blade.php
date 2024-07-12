@extends('frontend::layouts.user')
@section('title')
    {{ __('Become Subscriber') }}
@endsection
@section('style')
    <style>
        .page-content {
            padding: 0 !important;
        }
    </style>
@endsection
@section('content')
    <iframe src="https://copytrader.mbfx.co/portal/registration/subscription" class="w-full h-screen" frameborder="0"></iframe>
@endsection
