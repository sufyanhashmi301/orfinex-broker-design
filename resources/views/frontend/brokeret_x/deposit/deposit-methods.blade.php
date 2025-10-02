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
    <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
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

                // Determine link route and accessibility
                $isVoucher = $gateway->gateway_code === 'voucher';
                $route = $isVoucher
                    ? route('user.deposit.redeem.voucher', ['gateway_code' => the_hash($gateway->gateway_code)])
                    : route('user.deposit.amount', ['gateway_code' => the_hash($gateway->gateway_code)]);

                // If method requires payment deposit request but user doesn't have approved one, redirect to payment deposit page
                if ($gateway->requires_payment_deposit && !$gateway->is_accessible) {
                    $route = route('user.payment-deposit');
                }

            @endphp


            <a href="{{ $route }}" class="flex gap-3 rounded-lg border border-gray-200 p-5 hover:shadow-lg dark:border-gray-800 md:p-6 {{ !$gateway->is_accessible ? 'cursor-not-allowed' : '' }}">
                <div class="shrink-0 h-11 w-11 overflow-hidden rounded-full">
                    <img src="{{ $icon }}" class="h-10" alt="{{ $gateway->name }}" />
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <h4 class="text-base font-medium text-gray-800 dark:text-white/90">
                            {{ $gateway->name }}
                        </h4>
                        @if ($gateway->requires_payment_deposit && !$gateway->is_accessible)
                            <x-frontend::badge variant="warning" style="light" size="sm">
                                {{ __('Payment Request Required') }}
                            </x-frontend::badge>
                        @elseif($gateway->requires_payment_deposit && $gateway->is_accessible)
                            <x-frontend::badge variant="success" style="light" size="sm">
                                {{ __('Custom Bank Details') }}
                            </x-frontend::badge>
                        @else
                            <x-frontend::badge variant="secondary" style="light" size="sm">
                                {{ __('Verification required') }}
                            </x-frontend::badge>
                        @endif
                    </div>
                    
                    @if ($gateway->requires_payment_deposit && !$gateway->is_accessible)
                        <x-frontend::alert type="warning" dismissible="false" class="!border-none mb-3">
                            {{ __('You need to submit a payment deposit request to use this method.') }}
                        </x-frontend::alert>
                    @endif

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
