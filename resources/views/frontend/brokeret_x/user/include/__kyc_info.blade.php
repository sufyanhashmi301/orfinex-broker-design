@php
    $kycLevels = \App\Models\KycLevel::with('kyc_sub_levels')->where('status', true)->get();
    $totalActiveLevels = $kycLevels->count();
    $completedSteps = 0;
    if($totalActiveLevels > 0){
        if($user->kyc >= \App\Enums\KYCStatus::Level1->value){
            $completedSteps = 1;
        }
        if($user->kyc >= \App\Enums\KYCStatus::Level2->value){
            $completedSteps = 2;
        }
        if($user->kyc >= \App\Enums\KYCStatus::Level3->value){
            $completedSteps = 3;
        }
    }

    // Defaults
    $statusText = __('Not verified');
    $activeFill = '#6c8595';
    $inactiveFill = '#141d221f';
    
    $path1Fill = $inactiveFill;
    $path2Fill = $inactiveFill;
    $path3Fill = $inactiveFill;
    
    if ($completedSteps >= 1) $path1Fill = $activeFill;
    if ($completedSteps >= 2) $path2Fill = $activeFill;
    if ($completedSteps >= 3) $path3Fill = $activeFill;

    if ($completedSteps < 1) {
        $statusText = __('You need to verify your email address.');
    } elseif ($completedSteps < 2) {
        $statusText = __('You need to confirm your basic identity.');
    } elseif ($completedSteps < 3) {
        $statusText = __('You need to confirm your residential address.');
    }
    
@endphp
@if($totalActiveLevels > 0 && $user->kyc < \App\Enums\KYCStatus::Level2->value)
    <div class="bg-warning-500/15">
        <div class="mx-auto max-w-(--breakpoint-xl) p-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-2">
                    <div class="relative w-12 h-12">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                            <path d="M25.2803 4.67623C25.2252 4.70383 25.1879 4.75842 25.1856 4.82004C25.1822 4.90968 25.253 4.98503 25.3426 4.98916C35.4801 5.45641 43.364 13.9928 42.9826 24.1316C42.8887 26.6258 42.523 28.8344 41.5475 30.9645C40.9733 32.2182 41.5264 33.699 42.7829 34.2719C44.0395 34.8448 45.5235 34.2929 46.0977 33.0392C47.3404 30.3257 47.8631 27.4767 47.9819 24.3189C48.4671 11.424 38.4271 0.569716 25.5269 0.000654727C25.4392 -0.00321638 25.3645 0.0652989 25.3612 0.153077C25.3589 0.213435 25.3913 0.269497 25.4432 0.300444C26.2367 0.773938 26.7354 1.66997 26.6534 2.65135C26.5782 3.55089 26.0338 4.29908 25.2803 4.67623Z" fill="{{ $path1Fill }}"></path>
                            <path d="M40.039 34.4539C40.042 34.3923 40.0127 34.3331 39.9601 34.3008C39.8835 34.2537 39.7828 34.2783 39.735 34.3544C34.3413 42.9313 23.015 45.5821 14.3552 40.2643C12.2248 38.9561 10.4576 37.2912 9.0786 35.3961C8.26694 34.2807 6.7027 34.0329 5.58478 34.8428C4.46687 35.6527 4.2186 37.2134 5.03027 38.3288C6.78699 40.743 9.03581 42.8592 11.733 44.5154C22.7468 51.2788 37.1562 47.8949 43.9975 36.968C44.0442 36.8934 44.0212 36.7946 43.9462 36.7485C43.8947 36.7169 43.8298 36.7175 43.7772 36.7474C42.9738 37.2038 41.9467 37.1964 41.1307 36.6425C40.3827 36.1348 39.9976 35.2939 40.039 34.4539Z" fill="{{ $path2Fill }}"></path>
                            <path d="M7.03801 32.2068C7.09 32.2406 7.1564 32.245 7.21086 32.2154C7.28956 32.1726 7.318 32.0733 7.27568 31.9943C2.39061 22.8782 5.74338 12.1008 14.6722 7.24352C16.8684 6.04875 19.1996 5.35488 21.5422 5.11897C22.921 4.98012 23.9073 3.73737 23.7453 2.34318C23.5833 0.948991 22.3342 -0.0686605 20.9554 0.0701932C17.9712 0.370718 15.007 1.25495 12.2264 2.76763C0.87075 8.94516 -3.3536 22.797 2.88475 34.3869C2.92633 34.4641 3.02341 34.4934 3.10047 34.4514C3.15381 34.4224 3.18546 34.3655 3.18504 34.3049C3.17853 33.3669 3.68994 32.4707 4.57791 32.04C5.39185 31.6451 6.32029 31.7393 7.03801 32.2068Z" fill="{{ $path3Fill }}"></path>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-gray-600 dark:text-gray-300">
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" stroke="currentColor"></path>
                            <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" stroke="currentColor"></path>
                            <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" stroke="currentColor"></path>
                        </svg>
                    </div>
                    <p class="text-base text-gray-800 dark:text-white/90">
                        {{ $user->first_name }}, {{ $statusText }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <x-frontend::link-button href="javascript:void(0);" class="text-nowrap" variant="secondary" size="md">
                        {{ __('Learn more') }}
                    </x-frontend::link-button>
                    <x-frontend::link-button href="{{ route('user.kyc') }}" class="text-nowrap" variant="error" size="md">
                        {{ __('Complete Profile') }}
                    </x-frontend::link-button>
                </div>
            </div>
        </div>
    </div>
@endif
