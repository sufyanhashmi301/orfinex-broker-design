@extends('frontend::user.setting.index')
@section('title')
    {{ __('Settings') }}
@endsection
@section('settings-content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
        <h2 class="text-title-xs font-bold text-gray-800 dark:text-white/90">
            {{ __('Account') }}
        </h2>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 mb-10">
        <div class="rounded-lg border border-gray-200 p-5 dark:border-gray-800 h-full">
            @php
                $completedLevels = 0;
                foreach ($kycLevels as $kycLevel) {
                    if ($kycLevel->slug == \App\Enums\KycLevelSlug::LEVEL1 && $user->kyc >= \App\Enums\KYCStatus::Level1->value) {
                        $completedLevels++;
                    } elseif ($kycLevel->slug == \App\Enums\KycLevelSlug::LEVEL2 && $user->kyc >= \App\Enums\KYCStatus::Level2->value) {
                        $completedLevels++;
                    } elseif ($kycLevel->slug == \App\Enums\KycLevelSlug::LEVEL3 && $user->kyc >= \App\Enums\KYCStatus::Level3->value) {
                        $completedLevels++;
                    }
                }

                $totalLevels = $kycLevels->count();

                // Defaults
                $statusText = __('Not verified');
                $statusClass = 'text-error-600 dark:text-error-400';
                $activeFill = '#6c8595';
                $inactiveFill = '#141d221f';
                
                $path1Fill = $inactiveFill;
                $path2Fill = $inactiveFill;
                $path3Fill = $inactiveFill;
                
                // Set path fills based on completed levels
                if ($completedLevels >= 1) $path1Fill = $activeFill;
                if ($completedLevels >= 2) $path2Fill = $activeFill;
                if ($completedLevels >= 3) $path3Fill = $activeFill;

                if ($completedLevels == 1) {
                    $statusText = __('Verified (Email)');
                    $statusClass = 'text-blue-600 dark:text-blue-400';
                } elseif ($completedLevels > 1 && $completedLevels < $totalLevels) {
                    $statusText = __('Verified (Basic)');
                    $statusClass = 'text-warning-600 dark:text-warning-400';
                } elseif ($completedLevels == $totalLevels) {
                    $statusText = __('Fully verified');
                    $statusClass = 'text-success-600 dark:text-success-400';
                }
            @endphp
            <div class="flex items-center gap-2">
                <div class="relative w-12 h-12">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                        <path d="M25.2803 4.67623C25.2252 4.70383 25.1879 4.75842 25.1856 4.82004C25.1822 4.90968 25.253 4.98503 25.3426 4.98916C35.4801 5.45641 43.364 13.9928 42.9826 24.1316C42.8887 26.6258 42.523 28.8344 41.5475 30.9645C40.9733 32.2182 41.5264 33.699 42.7829 34.2719C44.0395 34.8448 45.5235 34.2929 46.0977 33.0392C47.3404 30.3257 47.8631 27.4767 47.9819 24.3189C48.4671 11.424 38.4271 0.569716 25.5269 0.000654727C25.4392 -0.00321638 25.3645 0.0652989 25.3612 0.153077C25.3589 0.213435 25.3913 0.269497 25.4432 0.300444C26.2367 0.773938 26.7354 1.66997 26.6534 2.65135C26.5782 3.55089 26.0338 4.29908 25.2803 4.67623Z" fill="{{ $path1Fill }}"></path>
                        <path d="M40.039 34.4539C40.042 34.3923 40.0127 34.3331 39.9601 34.3008C39.8835 34.2537 39.7828 34.2783 39.735 34.3544C34.3413 42.9313 23.015 45.5821 14.3552 40.2643C12.2248 38.9561 10.4576 37.2912 9.0786 35.3961C8.26694 34.2807 6.7027 34.0329 5.58478 34.8428C4.46687 35.6527 4.2186 37.2134 5.03027 38.3288C6.78699 40.743 9.03581 42.8592 11.733 44.5154C22.7468 51.2788 37.1562 47.8949 43.9975 36.968C44.0442 36.8934 44.0212 36.7946 43.9462 36.7485C43.8947 36.7169 43.8298 36.7175 43.7772 36.7474C42.9738 37.2038 41.9467 37.1964 41.1307 36.6425C40.3827 36.1348 39.9976 35.2939 40.039 34.4539Z" fill="{{ $path2Fill }}"></path>
                        <path d="M7.03801 32.2068C7.09 32.2406 7.1564 32.245 7.21086 32.2154C7.28956 32.1726 7.318 32.0733 7.27568 31.9943C2.39061 22.8782 5.74338 12.1008 14.6722 7.24352C16.8684 6.04875 19.1996 5.35488 21.5422 5.11897C22.921 4.98012 23.9073 3.73737 23.7453 2.34318C23.5833 0.948991 22.3342 -0.0686605 20.9554 0.0701932C17.9712 0.370718 15.007 1.25495 12.2264 2.76763C0.87075 8.94516 -3.3536 22.797 2.88475 34.3869C2.92633 34.4641 3.02341 34.4934 3.10047 34.4514C3.15381 34.4224 3.18546 34.3655 3.18504 34.3049C3.17853 33.3669 3.68994 32.4707 4.57791 32.04C5.39185 31.6451 6.32029 31.7393 7.03801 32.2068Z" fill="{{ $path3Fill }}"></path>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                        <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                        <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-theme-xs text-gray-800 dark:text-white/90">{{ __('Status') }}</p>
                    <h2 class="text-title-xs font-bold {{ $statusClass }}">
                        {{ $statusText }}
                    </h2>
                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">
                        {{ $completedLevels }}/{{ $totalLevels }} {{ __('steps complete') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 p-5 dark:border-gray-800 h-full">
            <div class="flex items-center gap-2">
                <div class="relative w-12 h-12
                    before:content-[''] 
                    before:absolute 
                    before:inset-0 
                    before:rounded-full 
                    before:border-[4px] 
                    before:border-solid 
                    before:border-[rgba(20,29,34,0.12)]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                        <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2"></path>
                        <path d="M12 3v3m0 12v3"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-theme-xs text-gray-800 dark:text-white/90">{{ __('Deposit limit') }}</p>
                    <h2 class="text-title-xs font-bold text-success-600 dark:text-success-400">
                        {{ __('Unlimited') }}
                    </h2>
                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">
                        {{ __('Depending on payment method') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
        <h2 class="text-title-xs font-bold text-gray-800 dark:text-white/90">
            {{ __('Verification steps') }}
        </h2>
    </div>

    <div class="rounded-lg border border-gray-200 dark:border-gray-800">
        @foreach($kycLevels as $kycLevel)
            @if($kycLevel->slug== \App\Enums\KycLevelSlug::LEVEL1)
                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-5 last:border-b-0 dark:border-gray-800">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full text-theme-sm bg-brand-100">
                            {{ 1 }}
                        </div>
                        <div>
                            <p class="text-theme-sm text-gray-800 dark:text-white/90 mb-1">
                                {{ __('Personal details')}}
                            </p>

                            @php
                                $phoneSubLevel = $kycLevel->kyc_sub_levels()->where('name', \App\Enums\KycType::PHONE)->first();
                                $emailSubLevel = $kycLevel->kyc_sub_levels()->where('name', \App\Enums\KycType::EMAIL)->first();

                                $maskedPhone = null;
                                if ($user->phone && strlen($user->phone) >= 4) {
                                    $phoneLength = strlen($user->phone);
                                    if ($phoneLength <= 6) {
                                        $maskedPhone = substr($user->phone, 0, 2) . str_repeat('*', $phoneLength - 3) . substr($user->phone, -1);
                                    } else {
                                        $maskedPhone = substr($user->phone, 0, 3) . str_repeat('*', $phoneLength - 5) . substr($user->phone, -2);
                                    }
                                }
                            @endphp

                            <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                                @if($emailSubLevel && $emailSubLevel->status)
                                    {{ $user->email }}
                                @endif

                                {{-- Separator if both exist --}}
                                @if(($emailSubLevel && $emailSubLevel->status) && ($phoneSubLevel && $phoneSubLevel->status))
                                    ,
                                @endif

                                {{-- Show phone if sub-level is active --}}
                                @if($phoneSubLevel && $phoneSubLevel->status)
                                    {{ $maskedPhone }}
                                @endif
                            </span>
                        </div>
                    </div>
                    @if($user->kyc >= \App\Enums\KYCStatus::Level1->value)
                        <x-frontend::badge variant="success" style="light" size="sm">
                            {{ __('Confirmed') }}
                        </x-frontend::badge>
                    @else
                        <x-frontend::link-button href="javascript:void(0);" variant="secondary" size="md">
                            {{ __('Verify Now') }}
                        </x-frontend::link-button>
                    @endif
                    
                </div>
            @endif

            @if($kycLevel->slug== \App\Enums\KycLevelSlug::LEVEL2)
                @php
                    $manualSubLevel = $kycLevel->kyc_sub_levels()->where('name', \App\Enums\KycType::MANUAL)->where('status', true)->first();
                    $automaticSubLevel = $kycLevel->kyc_sub_levels()->where('name', \App\Enums\KycType::AUTOMATIC)->where('status', true)->first();
                @endphp
                @if($automaticSubLevel && $automaticSubLevel->status)
                    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-5 last:border-b-0 dark:border-gray-800">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-6 h-6 rounded-full text-theme-sm bg-brand-100">
                                {{ 2 }}
                            </div>
                            <div>
                                <p class="text-theme-sm text-gray-800 dark:text-white/90 mb-1">
                                    {{ __('Identity verification')}}
                                </p>
                                <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                                    {{ $user->full_name }}
                                </span>
                            </div>
                        </div>
                        @if($user->kyc >= \App\Enums\KYCStatus::Level2->value)
                            <x-frontend::badge variant="success" style="light" size="sm">
                                {{ __('Confirmed') }}
                            </x-frontend::badge>
                        @elseif(!isset($user->kyc) || $user->kyc < \App\Enums\KYCStatus::Level1->value)
                            <x-frontend::link-button href="javascript:void(0);" variant="secondary" size="md">
                                {{ __('Verify Now') }}
                            </x-frontend::link-button>
                        @else
                            <x-frontend::link-button href="{{ route('user.kyc.automatic') }}" variant="secondary" size="md">
                                {{ __('Go to Sumsub') }}
                            </x-frontend::link-button>
                        @endif
                    </div>
                @endif
                @if($manualSubLevel && $manualSubLevel->status)
                    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-5 last:border-b-0 dark:border-gray-800">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-6 h-6 rounded-full text-theme-sm bg-brand-100">
                                {{ 2 }}
                            </div>
                            <div>
                                <p class="text-theme-sm text-gray-800 dark:text-white/90 mb-1">
                                    {{ __('Identity verification')}}
                                </p>
                                <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                                    {{ $user->full_name }}
                                </span>
                            </div>
                        </div>
                        @if($user->kyc >= \App\Enums\KYCStatus::Level2->value)
                            <x-frontend::badge variant="success" style="light" size="sm">
                                {{ __('Confirmed') }}
                            </x-frontend::badge>
                        @elseif($user->kyc == \App\Enums\KYCStatus::Pending->value)
                            <x-frontend::badge variant="warning" style="light" size="sm">
                                {{ __('Pending') }}
                            </x-frontend::badge>
                        @elseif(!isset($user->kyc) || $user->kyc < \App\Enums\KYCStatus::Level1->value)
                            <x-frontend::badge variant="light" style="light" size="sm">
                                {{ __('Complete step 1 to continue') }}
                            </x-frontend::badge>
                        @else
                            <x-frontend::link-button href="{{ route('user.kyc.basic') }}" variant="secondary" size="md">
                                {{ __('Go to Manual Submission') }}
                            </x-frontend::link-button>
                        @endif
                    </div>
                @endif
            @endif
            @if($kycLevel->slug== \App\Enums\KycLevelSlug::LEVEL3)
                <div class="border-b border-gray-100 px-4 py-5 last:border-b-0 dark:border-gray-800" x-data="{ open: false }">
                    <div class="flex items-center justify-between @if($user->kyc != \App\Enums\KYCStatus::Level3->value) cursor-pointer @endif" x-on:click="open = !open">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-6 h-6 rounded-full text-theme-sm bg-brand-100">
                                {{ 3 }}
                            </div>
                            <div>
                                <p class="text-theme-sm text-gray-800 dark:text-white/90 mb-1">
                                    {{ __('Residential address verification')}}
                                </p>
                                @if($user->kyc == \App\Enums\KYCStatus::Level3->value)
                                    <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                                        {{ $user->address }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($user->kyc == \App\Enums\KYCStatus::Level3->value)
                            <x-frontend::badge variant="success" style="light" size="sm">
                                {{ __('Confirmed') }}
                            </x-frontend::badge>
                        @else
                            <span class="text-gray-500 dark:text-gray-400">
                                <i data-lucide="chevron-down" class="w-4" x-show="!open"></i>
                                <i data-lucide="chevron-up" class="w-4" x-show="open"></i>
                            </span>
                        @endif
                    </div>
                    @if($user->kyc != \App\Enums\KYCStatus::Level3->value)
                        <div class="space-y-4 mt-5 overflow-hidden transition-all duration-500 ease-in-out"
                            x-show="open"
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 max-h-0"
                            x-transition:enter-end="opacity-100 max-h-screen"
                            x-transition:leave="transition ease-in duration-500"
                            x-transition:leave-start="opacity-100 max-h-screen"
                            x-transition:leave-end="opacity-0 max-h-0">
                            <div>
                                <p class="text-theme-xs text-gray-500 dark:text-gray-400 mb-1">
                                    {{ __('Provide proof of your place of residence') }}
                                </p>
                                <p class="text-theme-sm text-gray-800 dark:text-white/90">
                                    {{ $user->address ?? __('Not provided') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-theme-xs text-gray-500 dark:text-gray-400 mb-1">
                                    {{ __('Features and limits') }}
                                </p>
                                <ul class="list-disc list-inside text-theme-sm text-gray-800 dark:text-white/90">
                                    <li>{{ __('Unlimited deposits') }}</li>
                                </ul>
                            </div>
                            <x-frontend::link-button href="{{ route('user.kyc.level3') }}" variant="primary" size="md">
                                {{ __('Complete Now') }}
                            </x-frontend::link-button>
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    </div>

    <!-- Modal for Verify Email -->
    @include('frontend::user.kyc.modal.__email_verify')

@endsection

