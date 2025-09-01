@extends('frontend::layouts.user')
@section('title')
    {{ __('Send Money') }}
@endsection
@section('content')
    <x-progress-steps 
        step-one-title="{{ __('Payment Details') }}"
        :current-step="isset($currentStep) ? $currentStep : 1"
        :step-one-class="$isStepOne ?? ''"
        :step-two-class="$isStepTwo ?? ''" />

    @yield('send_money_content')
@endsection
