@extends('frontend::layouts.user')
@section('title')
    {{ __('Webterminal') }}
@endsection
@section('content')
    <iframe src="https://webtrader.banexcapital.com/terminal?utm_campaign=BanexClientOffice&utm_source=www.banexcapital.com&mode=connect&lang=en&theme-mode=0&theme=blueRed" width="100%" height="600px"></iframe>
@endsection
