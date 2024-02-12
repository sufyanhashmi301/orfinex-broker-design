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
    <iframe id="widgetFrame" style="min-width: 100%;" src="http://108.181.199.20:8081/widgets/ratings?widgetKey=social-ratings&theme=light&lang=en" scrolling="no" frameborder="0" onload="iFrameResize({ heightCalculationMethod: 'max', checkOrigin: false }, '#widgetFrame')"></iframe>
    <script src="http://108.181.199.20:8081/widgets/assets/js/iframeResizer.js"></script>
@endsection