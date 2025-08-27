@extends('frontend::user.setting.index')
@section('title')
    {{ __('KYC') }}
@endsection
@section('settings-content')
    <div class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-5">
        <div class="flex flex-col gap-5 mb-10 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 flex items-center gap-3">
                    <div class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-white/90">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none">
                            <path fill="none" stroke="currentColor" stroke-width="1.5" d="M3 10.417c0-3.198 0-4.797.378-5.335c.377-.537 1.88-1.052 4.887-2.081l.573-.196C10.405 2.268 11.188 2 12 2s1.595.268 3.162.805l.573.196c3.007 1.029 4.51 1.544 4.887 2.081C21 5.62 21 7.22 21 10.417v1.574c0 5.638-4.239 8.375-6.899 9.536C13.38 21.842 13.02 22 12 22s-1.38-.158-2.101-.473C7.239 20.365 3 17.63 3 11.991z"/>
                        </svg>
                    </div>
                    {{ __('KYC Verification Center') }}
                </h3>
                <p class="mt-2 text-gray-500 text-theme-sm dark:text-gray-400 leading-relaxed">
                    {{ __('To maintain a secure and compliant trading environment, identity verification is required.') }} <br>
                    {{ __('Complete your KYC steps to unlock full access including deposits, trading, and withdrawals.') }}
                </p>
            </div>
        </div>
            @if($totalActiveLevels > 1)

                <div class="max-w-5xl mx-auto mb-10">
                    <div class="relative w-full flex items-center justify-between">
                    @php
                        $completedStep = $user->kyc >= \App\Enums\KYCStatus::Level3->value ? 3 :
                                         ($user->kyc >= \App\Enums\KYCStatus::Level2->value ? 2 :
                                         ($user->kyc >= \App\Enums\KYCStatus::Level1->value ? 1 : 0));
                    @endphp

                    <!-- Progress Line -->
                        <div class="absolute top-1/2 left-0 right-0 h-1 z-0 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full transition-all duration-300 ease-in-out rounded-full bg-brand-600"
                                 style="width: {{ $completedStep == 1 ? '50%' : ($completedStep == 2 ? '100%' : ($completedStep == 3 ? '100%' : '0%')) }}">
                            </div>
                        </div>

                        <!-- Step 1 -->
                        <div class="relative z-10 w-8 h-8 flex items-center justify-center border-2 rounded-full
                            @if($user->kyc >= \App\Enums\KYCStatus::Level1->value) border-brand-600 bg-brand-600 text-white @else border-slate-300 bg-white dark:bg-dark text-slate-400 @endif">
                            @if($user->kyc >= \App\Enums\KYCStatus::Level1->value)
                                <i data-lucide="check" class="text-lg"></i>
                            @else
                                <span class="text-xs font-semibold">1</span>
                            @endif
                        </div>

                        <!-- Step 2 -->
                        <div class="relative z-10 w-8 h-8 flex items-center justify-center border-2 rounded-full
                            @if($user->kyc >= \App\Enums\KYCStatus::Level2->value) border-brand-600 bg-brand-600 text-white @else border-slate-300 bg-white dark:bg-dark text-slate-400 @endif">
                            @if($user->kyc >= \App\Enums\KYCStatus::Level2->value)
                                <i data-lucide="check" class="text-lg"></i>
                            @else
                                <span class="text-xs font-semibold">2</span>
                            @endif
                        </div>

                    @if($totalActiveLevels == 3)
                        <!-- Step 3 -->
                            <div class="relative z-10 w-8 h-8 flex items-center justify-center border-2 rounded-full
                                @if($user->kyc >= \App\Enums\KYCStatus::Level3->value) border-brand-600 bg-brand-600 text-white @else border-slate-300 bg-white dark:bg-dark text-slate-400 @endif">
                                @if($user->kyc >= \App\Enums\KYCStatus::Level3->value)
                                    <i data-lucide="check" class="text-lg"></i>
                                @else
                                    <span class="text-xs font-semibold">3</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif


            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($kycLevels as $kycLevel)
                    @if($kycLevel->slug== \App\Enums\KycLevelSlug::LEVEL1)
                        <div class="flex flex-col rounded-xl border border-gray-200 p-5 dark:border-gray-800 h-full">
                            <div class="mb-4">
                                <span class="inline-flex items-center rounded-full bg-brand-100 px-3 py-1 text-xs font-medium text-brand-800 dark:bg-brand-900 dark:text-brand-200">{{ __('Automated') }}</span>
                                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Verify your details please') }}
                                </p>
                            </div>
                            @php
                                $phoneSubLevel = $kycLevel->kyc_sub_levels()->where('name', \App\Enums\KycType::PHONE)->first();
                                $emailSubLevel = $kycLevel->kyc_sub_levels()->where('name', \App\Enums\KycType::EMAIL)->first();
                            @endphp
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">{{ __('1 - Confirm ') }} @if($emailSubLevel && $emailSubLevel->status) {{__('Email')}}  @endif @if($kycLevel->kyc_sub_levels()->where('status', true)->count()>1) {{__(' and ')}} @endif @if($phoneSubLevel && $phoneSubLevel->status) {{__('Phone')}}  @endif</h4>
                            @if($phoneSubLevel && $phoneSubLevel->status)
                                <div class="mb-3 w-full">
                                    <div class="relative">
                                        <input type="text" class="shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm disabled:border-gray-100 disabled:bg-gray-50 dark:disabled:text-white/90 disabled:placeholder:text-gray-300 dark:placeholder:text-white/30 dark:disabled:border-gray-800 dark:disabled:bg-white/[0.03] dark:disabled:placeholder:text-white/15" value="{{ $user->phone }}" disabled>
                                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                            <a href="javascript:void(0);" class="py-1 px-2 bg-gray-200 text-sm rounded inline-flex items-center dark:bg-gray-800 dark:text-white/90">
                                                {{ __('Verify Now') }}
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            @endif
                            @if($emailSubLevel && $emailSubLevel->status)
                                <div class="mb-3 w-full">
                                    <div class="relative">
                                        <input type="text" class="shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm disabled:border-gray-100 disabled:bg-gray-50 dark:disabled:text-white/90 disabled:placeholder:text-gray-300 dark:placeholder:text-white/30 dark:disabled:border-gray-800 dark:disabled:bg-white/[0.03] dark:disabled:placeholder:text-white/15" value="{{ $user->email }}" disabled>
                                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                            @if($user->email_verified_at != null)
                                                <x-badge variant="success" style="light" size="sm" icon="badge-check">
                                                    {{ __('Verified') }}
                                                </x-badge>
                                            @else
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#emailVerifyModal" class="py-1 px-2 bg-gray-200 text-sm rounded inline-flex items-center dark:bg-gray-800 dark:text-white/90">
                                                    {{ __('Verify Now') }}
                                                </button>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <div class="flex-1">
                                <p class="text-gray-800 dark:text-white/90 mb-3 text-sm font-semibold">{{ __('Privileges of Account Verification') }}</p>
                                <ul class="space-y-3 mb-6">
                                    <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="mt-0.5 text-brand-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                            </svg>
                                        </div>
                                        <span>{{ __('Update your full profile securely.') }}</span>
                                    </li>
                                    <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="mt-0.5 text-brand-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                            </svg>
                                        </div>
                                        <span>{{ __('Deposit funds without restrictions.') }}</span>
                                    </li>
                                    <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="mt-0.5 text-brand-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                            </svg>
                                        </div>
                                        <span>{{ __('Open demo and real trading accounts.') }}</span>
                                    </li>
                                    <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="mt-0.5 text-brand-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                            </svg>
                                        </div>
                                        <span>{{ __('Transfer funds internally.') }}</span>
                                    </li>
                                    <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="mt-0.5 text-brand-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                            </svg>
                                        </div>
                                        <span>{{ __('Create support ticket for assistance.') }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-auto">
                                @if($user->kyc >= \App\Enums\KYCStatus::Level1->value)
                                    <x-link-button href="javascript:void(0);" class="w-full" 
                                        variant="secondary" 
                                        size="lg" 
                                        icon="check" 
                                        icon-position="left"
                                        disabled="true">
                                        {{ __('Completed') }}
                                    </x-link-button>
                                @else
                                    <x-link-button href="javascript:void(0);" class="w-full" 
                                        variant="primary" 
                                        size="lg">
                                        {{ __('Verify Your Level 1') }}
                                    </x-link-button>
                                @endif
                            </div>
                        </div>
                    @endif
                    @if($kycLevel->slug== \App\Enums\KycLevelSlug::LEVEL2)
                        @php
                            $manualSubLevel = $kycLevel->kyc_sub_levels()->where('name', \App\Enums\KycType::MANUAL)->where('status', true)->first();
                            $automaticSubLevel = $kycLevel->kyc_sub_levels()->where('name', \App\Enums\KycType::AUTOMATIC)->where('status', true)->first();
                        @endphp
                        @if($automaticSubLevel && $automaticSubLevel->status)

                            <div class="flex flex-col rounded-xl border border-gray-200 p-5 dark:border-gray-800 h-full">
                                <div class="mb-4">
                                    <span class="inline-flex items-center rounded-full bg-brand-100 px-3 py-1 text-xs font-medium text-brand-800 dark:bg-brand-900 dark:text-brand-200">{{ __('Automated') }}</span>
                                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Provide a document confirming your name') }}
                                    </p>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">
                                    {{ __('2 - Verify your identity using Sumsub') }}
                                </h4>

                                <div class="flex-1">
                                    <p class="text-gray-800 dark:text-white/90 mb-3 text-sm font-semibold">
                                        {{ __('Privileges of Profile Verification') }}
                                    </p>
                                    <ul class="space-y-3 mb-6">
                                        <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                            <div class="mt-0.5 text-brand-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                                </svg>
                                            </div>
                                            <span>{{ __('Withdraw funds from verified accounts.') }}</span>
                                        </li>
                                        <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                            <div class="mt-0.5 text-brand-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                                </svg>
                                            </div>
                                            <span>{{ __('Make external transfers securely.') }}</span>
                                        </li>
                                        <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                            <div class="mt-0.5 text-brand-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                                </svg>
                                            </div>
                                            <span>{{ __('Get approved for higher trading limits.') }}</span>
                                        </li>
                                        <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                            <div class="mt-0.5 text-brand-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                                </svg>
                                            </div>
                                            <span>{{ __('Unlock advanced account features.') }}</span>
                                        </li>
                                        <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                            <div class="mt-0.5 text-brand-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                                </svg>
                                            </div>
                                            <span>{{ __('Faster processing of requests and reviews.') }}</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="mt-auto">
                                    @if($user->kyc >= \App\Enums\KYCStatus::Level2->value)
                                        <x-link-button href="javascript:void(0);" class="w-full" 
                                            variant="secondary" 
                                            size="lg" 
                                            icon="check" 
                                            icon-position="left"
                                            disabled="true">
                                            {{ __('Completed') }}
                                        </x-link-button>
                                    @elseif(!isset($user->kyc) || $user->kyc < \App\Enums\KYCStatus::Level1->value)
                                        <x-link-button href="javascript:void(0);" class="w-full" 
                                            variant="secondary" 
                                            size="lg"
                                            disabled="true">
                                            {{ __('Complete step 1 to continue') }}
                                        </x-link-button>
                                    @else
                                        <x-link-button href="{{ route('user.kyc.automatic') }}" class="w-full" 
                                            variant="primary" 
                                            size="lg">
                                            {{ __('Go to Sumsub') }}
                                        </x-link-button>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if($manualSubLevel && $manualSubLevel->status)
                            <div class="flex flex-col rounded-xl border border-gray-200 p-5 dark:border-gray-800 h-full">
                                <div class="mb-4">
                                    <span class="inline-flex items-center rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-orange-800 dark:bg-orange-900 dark:text-orange-200">{{ __('Manual') }}</span>
                                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Provide a document confirming your name') }}
                                    </p>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">
                                    {{ __('2 - Verify your identity manually') }}
                                </h4>
                                <div class="flex-1">
                                    <p class="text-gray-800 dark:text-white/90 mb-3 text-sm font-semibold">{{ __('Privileges and Benefit') }}</p>
                                    <ul class="space-y-3 mb-6">
                                        @include('frontend::.user.kyc.include.__level_2_benefits')
                                    </ul>
                                </div>
                                <div class="mt-auto">
                                    @if($user->kyc >= \App\Enums\KYCStatus::Level2->value)
                                        <x-link-button href="javascript:void(0);" class="w-full" 
                                            variant="secondary" 
                                            size="lg" 
                                            icon="check" 
                                            icon-position="left"
                                            disabled="true">
                                            {{ __('Completed') }}
                                        </x-link-button>
                                    @elseif($user->kyc == \App\Enums\KYCStatus::Pending->value)
                                        <x-link-button href="javascript:void(0);" class="w-full" 
                                            variant="secondary" 
                                            size="lg"
                                            disabled="true">
                                            {{ __('Pending') }}
                                        </x-link-button>
                                    @elseif(!isset($user->kyc) || $user->kyc < \App\Enums\KYCStatus::Level1->value)
                                        <x-link-button href="javascript:void(0);" class="w-full" 
                                            variant="secondary" 
                                            size="lg"
                                            disabled="true">
                                            {{ __('Complete step 1 to continue') }}
                                        </x-link-button>
                                    @else
                                        <x-link-button href="{{ route('user.kyc.basic') }}" class="w-full" 
                                            variant="primary" 
                                            size="lg">
                                            {{ __('Go to Manual Submission') }}
                                        </x-link-button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                    @if($kycLevel->slug== \App\Enums\KycLevelSlug::LEVEL3)

                        <div class="flex flex-col rounded-xl border border-gray-200 p-5 dark:border-gray-800 h-full">
                            <div class="mb-4">
                                <span class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-200">{{ __('Semi-Automated') }}</span>
                                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('You will need to provide proof of your place of residence') }}
                                </p>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">
                                {{ __('3 - Verify residential address') }}
                            </h4>
                            <div class="flex-1">
                                <p class="text-gray-800 dark:text-white/90 mb-3 text-sm font-semibold">{{ __('Privileges and Benefit') }}</p>
                                <ul class="space-y-3 mb-6">
                                    <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="mt-0.5 text-brand-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                            </svg>
                                        </div>
                                        <span>{{ __("Access to client's area.") }}</span>
                                    </li>
                                    <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="mt-0.5 text-brand-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                            </svg>
                                        </div>
                                        <span>{{ __('Open demo & real accounts.') }}</span>
                                    </li>
                                    <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="mt-0.5 text-brand-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                            </svg>
                                        </div>
                                        <span>{{ __('Trade on demo accounts') }}</span>
                                    </li>
                                    <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="mt-0.5 text-brand-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                            </svg>
                                        </div>
                                        <span>{{ __('Trade on Real Accounts.') }}</span>
                                    </li>
                                    <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="mt-0.5 text-brand-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                            </svg>
                                        </div>
                                        <span>{{ __('Deposit money in real accounts') }}</span>
                                    </li>
                                    <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="mt-0.5 text-brand-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                            </svg>
                                        </div>
                                        <span>{{ __('Withdrawal money from real accounts') }}</span>
                                    </li>
                                    <li class="text-sm text-gray-600 dark:text-gray-400 flex items-start space-x-3 rtl:space-x-reverse">
                                        <div class="mt-0.5 text-brand-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6L9 17l-5-5"/>
                                            </svg>
                                        </div>
                                        <span>{{ __('Priority Customer Support') }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-auto">
                                @if($user->kyc == \App\Enums\KYCStatus::Level3->value)
                                    <x-link-button href="javascript:void(0);" class="w-full" 
                                        variant="secondary" 
                                        size="lg" 
                                        icon="check" 
                                        icon-position="left"
                                        disabled="true">
                                        {{ __('Completed') }}
                                    </x-link-button>
                                @elseif($user->kyc == \App\Enums\KYCStatus::PendingLevel3->value)
                                    <x-link-button href="javascript:void(0);" class="w-full" 
                                        variant="secondary" 
                                        size="lg"
                                        disabled="true">
                                        {{ __('Pending') }}
                                    </x-link-button>
                                @elseif($user->kyc < \App\Enums\KYCStatus::Level2->value)
                                    <x-link-button href="javascript:void(0);" class="w-full" 
                                        variant="secondary" 
                                        size="lg"
                                        disabled="true">
                                        {{ __('Complete step 2 to continue') }}
                                    </x-link-button>
                                @else
                                    <x-link-button href="{{ route('user.kyc.level3') }}" class="w-full" 
                                        variant="primary" 
                                        size="lg">
                                        {{ __('Go to Manual Submission') }}
                                    </x-link-button>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>


            <div class="pt-10 border-t border-gray-200 dark:border-gray-700 mt-14">
                <h4 class="text-lg font-semibold mb-6 text-gray-800 dark:text-white/90">{{ __('Why KYC Matters') }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm text-gray-600 dark:text-gray-400">
                    <!-- Secure Account -->
                    <div class="flex items-start gap-4">
                        <div class="text-brand-600 text-xl mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path fill="none" stroke="currentColor" stroke-width="1.5" d="M3 10.417c0-3.198 0-4.797.378-5.335c.377-.537 1.88-1.052 4.887-2.081l.573-.196C10.405 2.268 11.188 2 12 2s1.595.268 3.162.805l.573.196c3.007 1.029 4.51 1.544 4.887 2.081C21 5.62 21 7.22 21 10.417v1.574c0 5.638-4.239 8.375-6.899 9.536C13.38 21.842 13.02 22 12 22s-1.38-.158-2.101-.473C7.239 20.365 3 17.63 3 11.991z"/>
                            </svg>
                        </div>
                        <div>
                            <h5 class="font-semibold text-base text-gray-800 dark:text-white/90 mb-1">
                                {{ __('Secure Your Account') }}
                            </h5>
                            <p class="leading-relaxed">
                                {{ __('Your personal information and funds are safeguarded with top-level encryption and ID checks.') }}    
                            </p>
                        </div>
                    </div>

                    <!-- Full Access -->
                    <div class="flex items-start gap-4">
                        <div class="text-brand-600 text-xl mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                    <circle cx="12" cy="16" r="1"/>
                                    <path d="m7 11V7a5 5 0 0 1 10 0v4"/>
                                </g>
                            </svg>
                        </div>
                        <div>
                            <h5 class="font-semibold text-base text-gray-800 dark:text-white/90 mb-1">
                                {{ __('Full Platform Access') }}
                            </h5>
                            <p class="leading-relaxed">
                                {{ __('Unlock deposits, withdrawals, real account trading, and financial transactions without limits.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Priority Support -->
                    <div class="flex items-start gap-4">
                        <div class="text-brand-600 text-xl mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path fill="currentColor" d="M12 2c5.522 0 10 4.478 10 10s-4.478 10-10 10S2 17.522 2 12S6.478 2 12 2m-.16 6L9.5 9.5l.01 1.008L10.5 10l-.01 1.007L12 12.5l1.5-1.5L13.491 10L14.5 9.992L14.489 9L12.16 8z"/>
                            </svg>
                        </div>
                        <div>
                            <h5 class="font-semibold text-base text-gray-800 dark:text-white/90 mb-1">
                                {{ __('Priority Support') }}
                            </h5>
                            <p class="leading-relaxed">
                                {{ __('Get faster support, quicker approvals, and special attention from our client success team.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Verify Email -->
    @include('frontend::user.kyc.modal.__email_verify')

@endsection

