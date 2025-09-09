@extends('frontend::layouts.user')
@section('title')
    {{ __('Web Terminal') }}
@endsection
@section('content')
    @if(setting('webterminal_status', 'webterminal'))
        <iframe src="{{ setting('webterminal_src_light','webterminal') }}" width="{{ setting('webterminal_width','webterminal') }}%" height="{{ setting('webterminal_height','webterminal') }}%" class="dark:hidden"></iframe>
        <iframe src="{{ setting('webterminal_src_dark','webterminal') }}" width="{{ setting('webterminal_width','webterminal') }}%" height="{{ setting('webterminal_height','webterminal') }}%" class="hidden dark:block"></iframe>
    @elseif(setting('x9_webterminal_status', 'x9_webterminal'))
        <iframe src="{{ setting('x9_webterminal_src_light','x9_webterminal') }}" width="{{ setting('x9_webterminal_width','x9_webterminal') }}%" height="{{ setting('x9_webterminal_height','x9_webterminal') }}%" class="dark:hidden"></iframe>
        <iframe src="{{ setting('x9_webterminal_src_dark','x9_webterminal') }}" width="{{ setting('x9_webterminal_width','x9_webterminal') }}%" height="{{ setting('x9_webterminal_height','x9_webterminal') }}%" class="hidden dark:block"></iframe>
    @endif
@endsection
@section('style')
    <style>
        .page-content {
            padding: 0 !important;
        }
        #content_layout > div {
            height: calc(100vh - 92px);
        }
    </style>
@endsection
