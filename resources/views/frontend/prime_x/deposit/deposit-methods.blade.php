@extends('frontend::layouts.user')
@section('title')
    {{ __('Deposit Methods') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="text-xl text-slate-600 dark:text-slate-100">
            @yield('title')
        </h4>
    </div>
    <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
        @foreach($gateways as $gateway)
            @php
                // Priority 1: Check if $gateway->logo is set and file exists
                $icon = null;
                if (!empty($gateway->logo)) {
                    $icon = asset($gateway->logo);
                }
                // Priority 2: Fallback to gateway->logo if gateway_id exists
                elseif (isset($gateway->gateway_id) && !empty($gateway->gateway->logo)) {
                    $icon = $gateway->gateway->logo;
                }
                // Priority 3: Fallback to default $gateway->logo even if file doesn't exist
                else {
                    $icon = asset($gateway->logo ?? '');
                }

                // Determine link route
                $isVoucher = $gateway->gateway_code === 'voucher';
                $route = $isVoucher ? route('user.deposit.redeem.voucher', ['gateway_code' => the_hash($gateway->gateway_code)]) : route('user.deposit.amount', ['gateway_code' => the_hash($gateway->gateway_code)]);

            @endphp
            <a href="{{ $route }}" class="card border hover:shadow-lg">
                <div class="card-header items-center noborder !p-4">
                    <img src="{{ $icon }}" class="h-10" alt="{{ $gateway->name }}" />
                    <span class="badge badge-secondary capitalize rounded-3xl py-1">
                        {{ __('Verification required') }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <h4 class="text-sm font-semibold text-slate-600 dark:text-white whitespace-nowrap mb-3">
                        {{ $gateway->name }}
                    </h4>
                    <ul class="space-y-3">
                        <li class="text-sm">
                            <span class="text-slate-500 dark:text-slate-100 mr-1">{{ __('Processing Time') }}</span>
                            <span class="capitalize text-slate-500 dark:text-white font-medium">{{ $gateway->processing_time }}</span>
                        </li>
                        <li class="text-sm">
                            <span class="text-slate-500 dark:text-slate-100 mr-1">{{ __('Fee') }}</span>
                            <span class="text-slate-500 dark:text-white font-medium">{{ $gateway->charge }}</span>
                        </li>
                        <li class="text-sm">
                            <span class="text-slate-500 dark:text-slate-100 mr-1">{{ __('Limits') }}</span>
                            <span class="text-slate-500 dark:text-white font-medium">{{ $gateway->minimum_deposit }} - {{ $gateway->maximum_deposit }} {{ $gateway->currency }}</span>
                        </li>
                    </ul>
                </div>
            </a>
        @endforeach
    </div>
@endsection
