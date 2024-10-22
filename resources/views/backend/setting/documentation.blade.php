@extends('backend.layouts.app')
@section('title')
    {{ __('Documentation') }}
@endsection
@section('content')
    <iframe src="https://app.theneo.io/brokeret/api" style="display:block; width:100%; height:100vh;" seamless="seamless" frameborder="0" scrolling="yes"></iframe>
@endsection
