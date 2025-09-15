@extends('frontend::layouts.user')
@section('title')
    {{ __('Settings') }}
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            @yield('title')
        </h2>
    </div>

    <div x-data="{ currentRoute: '{{ Route::currentRouteName() }}' }" class="max-w-[calc(100vw-2em)] overflow-x-auto overflow-y-hidden scrollbar-hide border-b border-gray-200 dark:border-gray-800 mb-10">
        <nav class="-mb-px flex h-full" role="tablist" aria-label="tab options">
            @if(setting('kyc_verification','permission'))
                <a href="{{ route('user.kyc') }}" 
                    class="text-base border-b-3 px-4 py-3 transition-colors duration-200 whitespace-nowrap"
                    x-on:click="currentRoute = 'user.kyc'" 
                    x-bind:aria-selected="currentRoute === 'user.kyc'" 
                    x-bind:tabindex="currentRoute === 'user.kyc' ? '0' : '-1'" 
                    x-bind:class="currentRoute === 'user.kyc'
                        ? 'border-brand-500 dark:border-brand-400' 
                        : 'text-gray-500 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'">
                    {{ __('Profile') }}
                </a>
            @endif
            <a href="{{ route('user.setting.security') }}" 
                class="text-base border-b-3 px-4 py-3 transition-colors duration-200 whitespace-nowrap"
                x-on:click="currentRoute = 'user.setting.security'" 
                x-bind:aria-selected="currentRoute === 'user.setting.security'" 
                x-bind:tabindex="currentRoute === 'user.setting.security' ? '0' : '-1'" 
                x-bind:class="currentRoute === 'user.setting.security'
                    ? 'border-brand-500 dark:border-brand-400' 
                    : 'text-gray-500 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'">
                {{ __('Security') }}
            </a>
            <a href="{{ route('user.withdraw.account.index') }}" 
                class="text-base border-b-3 px-4 py-3 transition-colors duration-200 whitespace-nowrap"
                x-on:click="currentRoute = 'user.withdraw.account.index'" 
                x-bind:aria-selected="currentRoute === 'user.withdraw.account.index'" 
                x-bind:tabindex="currentRoute === 'user.withdraw.account.index' ? '0' : '-1'" 
                x-bind:class="currentRoute === 'user.withdraw.account.index'
                    ? 'border-brand-500 dark:border-brand-400' 
                    : 'text-gray-500 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'">
                {{ __('Withdraw Accounts') }}
            </a>
            <a href="{{ route('user.setting.preference') }}" 
                class="text-base border-b-3 px-4 py-3 transition-colors duration-200 whitespace-nowrap"
                x-on:click="currentRoute = 'user.setting.preference'" 
                x-bind:aria-selected="currentRoute === 'user.setting.preference'" 
                x-bind:tabindex="currentRoute === 'user.setting.preference' ? '0' : '-1'" 
                x-bind:class="currentRoute === 'user.setting.preference'
                    ? 'border-brand-500 dark:border-brand-400' 
                    : 'text-gray-500 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'">
                {{ __('Preference') }}
            </a>
            <a href="{{ route('user.agreements') }}" 
                class="text-base border-b-3 px-4 py-3 transition-colors duration-200 whitespace-nowrap"
                x-on:click="currentRoute = 'user.agreements'" 
                x-bind:aria-selected="currentRoute === 'user.agreements'" 
                x-bind:tabindex="currentRoute === 'user.agreements' ? '0' : '-1'" 
                x-bind:class="currentRoute === 'user.agreements'
                    ? 'border-brand-500 dark:border-brand-400' 
                    : 'text-gray-500 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'">
                {{ __('Agreements') }}
            </a>
            <a href="{{ route('user.setting.tools') }}" 
                class="text-base border-b-3 px-4 py-3 transition-colors duration-200 whitespace-nowrap"
                x-on:click="currentRoute = 'user.setting.tools'" 
                x-bind:aria-selected="currentRoute === 'user.setting.tools'" 
                x-bind:tabindex="currentRoute === 'user.setting.tools' ? '0' : '-1'" 
                x-bind:class="currentRoute === 'user.setting.tools'
                    ? 'border-brand-500 dark:border-brand-400' 
                    : 'text-gray-500 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'">
                {{ __('Tools') }}
            </a>
        </nav>
    </div>

    @yield('settings-content')

    <!-- Modal for Edit Phone -->
    @include('frontend::.user.setting.profile.modal.__edit_phone')

    <!-- Modal for Edit Email -->
    @include('frontend::.user.setting.profile.modal.__edit_address')

@endsection
@section('script')
    @yield('settings-script')
@endsection
