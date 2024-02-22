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
    <iframe id="widgetFrame" style="min-width: 100%;" src="https://brokeree.mbfx.co/widgets/ratings?widgetKey=social-ratings&theme=light&lang=en" scrolling="no" frameborder="0" onload="iFrameResize({ heightCalculationMethod: 'max', checkOrigin: false }, '#widgetFrame')"></iframe>
    <script src="https://brokeree.mbfx.co/widgets/assets/js/iframeResizer.js"></script>
@endsection
