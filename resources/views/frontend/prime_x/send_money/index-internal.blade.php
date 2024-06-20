@extends('frontend::layouts.user')
@section('title')
    {{ __('Send Money') }}
@endsection
@section('content')
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Internal Transfer') }}</h4>
                    <div>
                        <a href="{{ route('user.send-money.log') }}" class="btn btn-dark">
                            {{ __('History') }}
                        </a>
                    </div>
                </div>
                <div class="card-body p-6">
                    <div class="progress-steps md:flex justify-between items-center mb-7 hidden">
                        <div class="single-step {{ $isStepOne }}">
                            <div class="number">{{ __('01') }}</div>
                            <div class="content">
                                <h4 class="leading-none text-dark dark:text-white">{{ __('Payment Details') }}</h4>
                                <p class="text-dark dark:text-white">{{ __('Enter your Payment details') }}</p>
                            </div>
                        </div>
                        <div class="single-step {{ $isStepTwo }}">
                            <div class="number">{{ __('02') }}</div>
                            <div class="content">
                                <h4 class="leading-none text-dark dark:text-white">{{ __('Success') }}</h4>
                                <p class="text-dark dark:text-white">{{  $notify['card-header'] ??  __('Successfully Payment') }}</p>
                            </div>
                        </div>
                    </div>
                    @yield('send_money_content_internal')
                </div>
            </div>
        </div>
    </div>
@endsection
