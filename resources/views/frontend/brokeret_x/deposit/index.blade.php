@extends('frontend::layouts.user')
@section('title')
    {{ __('Deposit Now') }}
@endsection
@section('content')
    <x-frontend::progress-steps 
        step-one-title="{{ __('Deposit Amount') }}"
        :current-step="isset($currentStep) ? $currentStep : 1"
        :step-one-class="$isStepOne ?? ''"
        :step-two-class="$isStepTwo ?? ''" />

    @yield('deposit_content')
    
@endsection
