@extends('frontend::layouts.user')
@section('title')
    {{ __('Deposit Methods') }}
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            @yield('title')
        </h2>
    </div>
    <div class="grid sm:grid-cols-2 grid-cols-1 gap-5">
        @foreach($gateways as $gateway)
            @php
                $icon = $gateway->logo;
                if (null != $gateway->gateway_id && $gateway->icon == ''){
                    $icon = $gateway->gateway->logo;
                }

                // Determine link route
                $isVoucher = $gateway->gateway_code === 'voucher';
                $route = $isVoucher ? route('user.deposit.redeem.voucher', ['gateway_code' => the_hash($gateway->gateway_code)]) : route('user.deposit.amount', ['gateway_code' => the_hash($gateway->gateway_code)]);

            @endphp
            <a href="{{ $route }}" class="flex gap-3 rounded-lg border border-gray-200 p-5 hover:shadow-lg dark:border-gray-800 md:p-6">
                <div class="shrink-0 h-11 w-11 overflow-hidden rounded-full">
                    <img src="{{ isset($gateway->gateway_id) ? $gateway->gateway->logo : asset($icon) }}" class="h-10" alt="{{ $gateway->name }}" />
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <h4 class="text-base font-medium text-gray-800 dark:text-white/90">
                            {{ $gateway->name }}
                        </h4>
                        <x-badge variant="success" style="light" size="sm">
                            {{ __('Verification required') }}
                        </x-badge>
                    </div>
                    
                    <ul class="space-y-1">
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
