@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Accounts') }}
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            @yield('title')
        </h2>

        <x-frontend::link-button href="{{ route('user.withdraw.account.create') }}" variant="secondary" icon="plus" icon-position="left">
            {{ __('Add New') }}
        </x-frontend::link-button>
    </div>
    @if(count($accounts) == 0)
        <!-- Empty State -->
         <x-frontend::empty-state icon="inbox">
            <x-slot name="title">
                {{ __("You're almost ready to withdraw!") }}
            </x-slot>
            <x-slot name="subtitle">
                {{ __('To make a withdraw, please add a withdraw account.') }}
            </x-slot>
            <x-slot name="actions">
                <x-frontend::link-button href="{{ route('user.withdraw.account.create') }}" variant="primary" size="md" icon="plus" icon-position="left">
                    {{ __('Add Withdraw Account') }}
                </x-frontend::link-button>
            </x-slot>
         </x-frontend::empty-state>
    @else
        <!-- Accounts List -->
        <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
            @foreach($accounts as $account)
                <a href="{{ route('user.withdraw.view', ['gateway_code' => the_hash($account->id)]) }}" class="flex gap-3 rounded-lg border border-gray-200 p-5 hover:shadow-lg dark:border-gray-800 md:p-6">
                    <!-- Account Content -->
                    <div class="shrink-0 h-11 w-11 overflow-hidden rounded-full">
                        <img src="{{ $account->logo_url }}" class="h-10" alt="{{ $account->method_name }}" />
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <h4 class="text-base font-medium text-gray-800 dark:text-white/90">
                                {{ $account->method_name }}
                            </h4>
                            <x-frontend::badge variant="success" style="light" size="sm">
                                {{ __('Verification required') }}
                            </x-frontend::badge>
                        </div>
                        <ul class="space-y-1">
                            <li class="text-sm">
                                <span class="text-slate-400 mr-1">{{ __('Processing Time') }}</span>
                                <span class="capitalize">{{ $account->method->processing_time.' '. $account->method->required_time_format }}</span>
                            </li>
                            <li class="text-sm">
                                <span class="text-slate-400 mr-1">{{ __('Fee') }}</span>
                                <span>
                                    {{ $account->method->charge }} {{ $account->method->charge_type == 'percentage' ? '%' : setting('currency_symbol', 'global') }}
                                </span>
                            </li>
                            <li class="text-sm">
                                <span class="text-slate-400 mr-1">{{ __('Limits') }}</span>
                                <span>{{ $account->method->min_withdraw }} - {{ $account->method->max_withdraw }} {{ $account->method->currency }}</span>
                            </li>
                        </ul>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
