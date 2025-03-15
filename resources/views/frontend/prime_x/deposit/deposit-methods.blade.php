@extends('frontend::layouts.user')
@section('title')
    {{ __('Deposit Methods') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="text-xl text-slate-600 dark:text-slate-300">
            @yield('title')
        </h4>
    </div>
    <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
        @foreach($gateways as $gateway)
            @php
                $icon = $gateway->logo;
                if (null != $gateway->gateway_id && $gateway->icon == ''){
                    $icon = $gateway->gateway->logo;
                }
            @endphp
            <a href="{{ route('user.deposit.amount', ['gateway_code' => the_hash($gateway->gateway_code)]) }}" class="card border hover:shadow-lg">
                <div class="card-header items-center noborder !p-4">
                    <img src="{{ isset($gateway->gateway_id) ? $gateway->gateway->logo : asset($icon) }}" class="h-10" alt="{{ $gateway->name }}" />
                    <span class="badge badge-secondary capitalize rounded-3xl py-1">
                        {{ __('Verification required') }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap mb-3">
                        {{ $gateway->name }}
                    </h4>
                    <ul class="space-y-3">
                        <li class="text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Processing Time') }}</span>
                            <span class="capitalize">{{ $gateway->processing_time }}</span>
                        </li>
                        <li class="text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Fee') }}</span>
                            <span>{{ $gateway->charge }}</span>
                        </li>
                        <li class="text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Limits') }}</span>
                            <span>{{ $gateway->minimum_deposit }} - {{ $gateway->maximum_deposit }} {{ $gateway->currency }}</span>
                        </li>
                    </ul>
                </div>
            </a>
        @endforeach
    </div>
@endsection
