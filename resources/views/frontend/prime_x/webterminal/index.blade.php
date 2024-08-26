@extends('frontend::layouts.user')
@section('title')
    {{ __('Webterminal') }}
@endsection
@section('content')
    <iframe src="{{ setting('terminal_src_light','global') }}" width="{{ setting('terminal_width','global') }}" height="{{ setting('terminal_height','global') }}" class="dark:hidden"></iframe>
    <iframe src="{{ setting('terminal_src_dark','global') }}" width="{{ setting('terminal_width','global') }}" height="{{ setting('terminal_height','global') }}" class="hidden dark:block"></iframe>
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
