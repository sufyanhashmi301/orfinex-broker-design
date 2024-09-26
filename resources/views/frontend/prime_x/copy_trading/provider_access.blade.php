@extends('frontend::layouts.user')
@section('title')
    {{ __('Become Provider') }}
@endsection
@section('content')
{{--    @if(setting('copy_trading_provider_access_show','platform_links',false))--}}
        <iframe src="{{setting('copy_trading_provider_access','platform_links','javascript:void(0);')}}"
                class="w-full h-screen"
                frameborder="0"></iframe>
{{--    @endif--}}
@endsection
