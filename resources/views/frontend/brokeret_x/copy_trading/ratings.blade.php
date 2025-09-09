@extends('frontend::copy_trading.index')
@section('title')
    {{ __('Ratings') }}
@endsection
@section('copy-trading-content')
    @if(setting('copy_trading_ratings','platform_links',false))
        <iframe id="widgetFrame" class="w-full h-screen" style="min-width: 100%;"
                src="{{setting('copy_trading_ratings','platform_links','javascript:void(0);')}}"
                scrolling="no" frameborder="0"
                onload="iFrameResize({heightCalculationMethod: 'max', checkOrigin: false }, '#widgetFrame')"></iframe>
    @endif
    @if(setting('copy_trading_ratings_js','platform_links',false))
        <script src="{{setting('copy_trading_ratings_js','platform_links','javascript:void(0);')}}"></script>
    @endif
@endsection
