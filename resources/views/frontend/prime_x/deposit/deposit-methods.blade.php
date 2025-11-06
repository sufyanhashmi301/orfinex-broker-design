@extends('frontend::layouts.user')
@section('title')
    {{ __('Deposit Methods') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-3">
        <h4 class="text-xl text-slate-600 dark:text-slate-100">
            @yield('title')
        </h4>

        @php
            $clientFundSafetyDoc = \App\Models\DocumentLink::where('slug', 'client_fund_safety')->where('status', 1)->first();
        @endphp
        @if($clientFundSafetyDoc)
            <a href="{{ $clientFundSafetyDoc->link }}" target="_blank" class="btn-link text-primary">
                {{ __('Client Fund Safety') }}
                <iconify-icon icon="lucide:external-link"></iconify-icon>
            </a>
        @else
            {{-- <a href="{{ route('user.client-fund-safety') }}" target="_blank" class="btn-link text-primary">
                {{ __('Client Fund Safety') }}
                <iconify-icon icon="lucide:external-link"></iconify-icon>
            </a> --}}
        @endif
    </div>
    <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
        @foreach ($gateways as $gateway)
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
            <div class="card border hover:shadow-lg {{ !$gateway->is_accessible ? 'opacity-75' : '' }}">
                @if ($gateway->is_accessible)
                    <a href="{{ $route }}" class="block">
                @else
                    <a href="{{ $route }}" class="block">
                @endif
                <div class="card-header items-center noborder !p-4">
                    <img src="{{ $icon }}" class="h-10" alt="{{ $gateway->name }}" />
                    @if ($gateway->requires_payment_deposit && !$gateway->is_accessible)
                        <span class="badge bg-warning-500 text-white capitalize rounded-3xl py-1">
                            {{ __('Payment Request Required') }}
                        </span>
                    @elseif($gateway->requires_payment_deposit && $gateway->is_accessible)
                        <span class="badge bg-success-500 text-white capitalize rounded-3xl py-1">
                            {{ __('Custom Bank Details') }}
                        </span>
                    @else
                        <span class="badge badge-secondary capitalize rounded-3xl py-1">
                            {{ __('Verification required') }}
                        </span>
                    @endif
                </div>
                <div class="card-body p-4">
                    <h4 class="text-sm font-semibold text-slate-600 dark:text-white whitespace-nowrap mb-3">
                        {{ $gateway->name }}
                    </h4>

                    @if ($gateway->requires_payment_deposit && !$gateway->is_accessible)
                        <div class="mb-3 p-2 bg-warning-100 dark:bg-warning-900 rounded text-xs text-warning-700 dark:text-warning-300">
                            {{ __('You need to submit a payment deposit request to use this method.') }}
                        </div>
                    @endif

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
                            <span class="text-slate-500 dark:text-white font-medium">{{ $gateway->minimum_deposit }} -
                                {{ $gateway->maximum_deposit }} {{ $gateway->currency }}</span>
                        </li>
                    </ul>
                </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- Empty State: No deposit methods available --}}
    @if($gateways->isEmpty())
        <div class="card basicTable_wrapper flex items-center justify-center py-10 px-10">
            <div class="max-w-2xl mx-auto flex items-center justify-center flex-col gap-3">
                <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26 19.875V30.9167" stroke="rgba(255 0 0)" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba(255 0 0)" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M25.988 37.5417H26.0075" stroke="rgba(255 0 0)" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <div class="text-center">
                    <h4 class="text-xl font-medium text-slate-900 dark:text-white mb-2">
                        {{ __('No Deposit Methods Available') }}
                    </h4>
                    <p class="font-normal text-sm text-slate-500 dark:text-slate-300">
                        {{ __('There are currently no deposit methods configured. Please contact support for assistance.') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
@endsection
