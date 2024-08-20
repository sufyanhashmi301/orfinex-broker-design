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
{{--    @if(setting('copy_trading_follower_access_show','platform_links',false))--}}
    <iframe src="{{setting('copy_trading_follower_access','platform_links','javascript:void(0);')}}" class="w-full h-screen" frameborder="0"></iframe>
{{--@endif--}}
@endsection
