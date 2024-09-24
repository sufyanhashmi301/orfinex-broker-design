@extends('frontend::layouts.user')
@section('title')
    {{ __('Ratings') }}
@endsection
@section('style')
    <style>
        .page-content {
            padding: 0 !important;
        }
    </style>
@endsection
@section('content')
    @if(setting('copy_trading_ratings','platform_links',false))
        <iframe id="widgetFrame" style="min-width: 100%;"
                src="{{setting('copy_trading_ratings','platform_links','javascript:void(0);')}}"
                scrolling="no" frameborder="0"
                onload="iFrameResize({heightCalculationMethod: 'max', checkOrigin: false }, '#widgetFrame')"></iframe>
    @endif
    @if(setting('copy_trading_ratings_js','platform_links',false))
        <script src="{{setting('copy_trading_ratings_js','platform_links','javascript:void(0);')}}"></script>
    @endif
@endsection
