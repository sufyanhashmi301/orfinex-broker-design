@extends('frontend::layouts.user')
@section('title')
    {{ __('Deposit Methods') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="text-xl font-semibold text-gray-800 dark:text-white/90">
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

                // Determine link route
                $isVoucher = $gateway->gateway_code === 'voucher';
                $route = $isVoucher ? route('user.deposit.redeem.voucher', ['gateway_code' => the_hash($gateway->gateway_code)]) : route('user.deposit.amount', ['gateway_code' => the_hash($gateway->gateway_code)]);

            @endphp
            <a href="{{ $route }}" class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                <div class="flex items-center justify-between mb-3">
                    <img src="{{ isset($gateway->gateway_id) ? $gateway->gateway->logo : asset($icon) }}" class="h-10" alt="{{ $gateway->name }}" />
                    <x-badge variant="light" style="light" size="sm">
                        {{ __('Verification required') }}
                    </x-badge>
                </div>
                <div>
                    <h4 class="mb-3 text-base font-medium text-gray-800 dark:text-white/90">
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
