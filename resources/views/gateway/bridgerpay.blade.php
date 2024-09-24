
@extends('frontend::layouts.user')
@section('title')
    {{ __('Dashboard') }}
@endsection
@section('content')
    <iframe width="700px" height="1000px" src="https://checkout.bridgerpay.com/v2/?cashierKey={{ $cashierKey }}&cashierToken={{ $cashierToken }}"></iframe>

@endsection
