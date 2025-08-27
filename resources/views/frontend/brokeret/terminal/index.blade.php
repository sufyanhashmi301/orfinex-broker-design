@extends('frontend::layouts.user')
@section('title')
    {{ __('Terminal') }}
@endsection

@section('content')
    <iframe src="https://webtrading.orfinex.com/terminal" class="min-h-screen" width="100%" frameborder="0"></iframe>
@endsection
