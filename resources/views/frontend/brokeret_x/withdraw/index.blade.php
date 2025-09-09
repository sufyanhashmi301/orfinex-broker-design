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
        <div class="flex items-center justify-center flex-col gap-3 px-10 mt-10 lg:mt-20">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                    {{ __("You're almost ready to withdraw!") }}
                </h2>
                <p class="mt-1 text-base text-gray-500 dark:text-gray-400">
                    {{ __('To make a withdraw, please add a withdraw account.') }}
                </p>
            </div>
            <x-frontend::link-button href="{{ route('user.withdraw.account.create') }}" variant="primary" size="md" icon="plus" icon-position="left">
                {{ __('Add Withdraw Account') }}
            </x-frontend::link-button>
        </div>
    @else
        <!-- Accounts List -->
        <div class="grid sm:grid-cols-2 grid-cols-1 gap-5">
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
