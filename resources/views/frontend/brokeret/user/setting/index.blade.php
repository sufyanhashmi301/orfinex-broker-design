@extends('frontend::layouts.user')
@section('title')
    {{ __('Settings') }}
@endsection
@section('content')
    <div class="pageTitle overflow-x-auto mb-5" x-data="{ currentRoute: '{{ Route::currentRouteName() }}' }">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden">
                <div class="inline-flex items-center shadow-theme-xs">
                    <a href="{{ route('user.setting.profile') }}" 
                       :class="currentRoute === 'user.setting.profile' ? 'bg-brand-500 text-white ring-brand-500' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/[0.03]'"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium ring-1 ring-inset ring-gray-300 transition first:rounded-l-lg last:rounded-r-lg dark:bg-white/[0.03] dark:ring-gray-700">
                        {{ __('Profile') }}
                    </a>
                    <a href="{{ route('user.withdraw.account.index') }}" 
                       :class="currentRoute === 'user.withdraw.account.index' ? 'bg-brand-500 text-white ring-brand-500' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/[0.03]'"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium ring-1 ring-inset ring-gray-300 transition first:rounded-l-lg last:rounded-r-lg dark:bg-white/[0.03] dark:ring-gray-700">
                        {{ __('Withdraw Accounts') }}
                    </a>
                    <a href="{{ route('user.setting.security') }}" 
                       :class="currentRoute === 'user.setting.security' ? 'bg-brand-500 text-white ring-brand-500' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/[0.03]'"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium ring-1 ring-inset ring-gray-300 transition first:rounded-l-lg last:rounded-r-lg dark:bg-white/[0.03] dark:ring-gray-700">
                        {{ __('Security') }}
                    </a>
                    @if(setting('kyc_verification','permission'))
                        <a href="{{ route('user.kyc') }}" 
                           :class="currentRoute === 'user.kyc' ? 'bg-brand-500 text-white ring-brand-500' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/[0.03]'"
                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium ring-1 ring-inset ring-gray-300 transition first:rounded-l-lg last:rounded-r-lg dark:bg-white/[0.03] dark:ring-gray-700">
                            {{ __('KYC') }}
                        </a>
                    @endif
                    <a href="{{ route('user.setting.preference') }}" 
                       :class="currentRoute === 'user.setting.preference' ? 'bg-brand-500 text-white ring-brand-500' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/[0.03]'"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium ring-1 ring-inset ring-gray-300 transition first:rounded-l-lg last:rounded-r-lg dark:bg-white/[0.03] dark:ring-gray-700">
                        {{ __('Preference') }}
                    </a>
                    <a href="{{ route('user.agreements') }}" 
                       :class="currentRoute === 'user.agreements' ? 'bg-brand-500 text-white ring-brand-500' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/[0.03]'"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium ring-1 ring-inset ring-gray-300 transition first:rounded-l-lg last:rounded-r-lg dark:bg-white/[0.03] dark:ring-gray-700">
                        {{ __('Agreements') }}
                    </a>
                    <a href="{{ route('user.setting.tools') }}" 
                       :class="currentRoute === 'user.setting.tools' ? 'bg-brand-500 text-white ring-brand-500' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/[0.03]'"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium ring-1 ring-inset ring-gray-300 transition first:rounded-l-lg last:rounded-r-lg dark:bg-white/[0.03] dark:ring-gray-700">
                        {{ __('Tools') }}
                    </a>
                </div>
            </div>
        </div>
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
