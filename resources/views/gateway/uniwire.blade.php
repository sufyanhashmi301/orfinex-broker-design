@extends('frontend::layouts.user')
@section('content')
<div class="payment-container h-screen">
    <iframe 
        src="{{ $data['invoice_url'] }}" 
        frameborder="0" 
        width="100%" 
        height="100%" 
        style="min-height: 800px;"
        allowfullscreen
    ></iframe>
</div>
@endsection 