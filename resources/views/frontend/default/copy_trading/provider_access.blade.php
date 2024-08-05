@extends('frontend::layouts.user')
@section('title')
    {{ __('Become Provider') }}
@endsection
@section('style')
    <style>
        .page-content {
            padding: 0 !important;
        }
    </style>
@endsection
@section('content')
    <iframe src="https://209.209.42.14:8080/portal/registration/provider" class="w-full h-screen" frameborder="0"></iframe>
@endsection
