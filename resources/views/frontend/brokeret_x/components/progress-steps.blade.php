@props([
    'stepOneTitle',
    'stepTwoTitle' => 'Success',
    'currentStep' => 1,
    'stepOneClass' => '',
    'stepTwoClass' => ''
])

@php
    // Determine step states
    $stepOneState = $currentStep >= 1 ? ($currentStep > 1 ? 'completed' : 'current') : 'pending';
    $stepTwoState = $currentStep >= 2 ? 'completed' : 'pending';
    
    // Progress bar colors
    $stepOneColor = match($stepOneState) {
        'completed' => 'bg-green-500',
        'current' => 'bg-blue-500',
        default => 'bg-gray-200 dark:bg-gray-800'
    };
    
    $stepTwoColor = match($stepTwoState) {
        'completed' => 'bg-green-500',
        'current' => 'bg-blue-500',
        default => 'bg-gray-200 dark:bg-gray-800'
    };
@endphp
<div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6 hidden md:block mb-6">
    <div class="progress-steps md:flex justify-between items-center gap-5">
        <div class="single-step w-full {{ $stepOneState }} {{ $stepOneClass }}">
            <div class="w-full h-2 rounded-full {{ $stepOneColor }} progress_bar mb-5"></div>
            <div class="">
                <div class="text-theme-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Step - 1') }}</div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ $stepOneTitle }}</h4>
            </div>
        </div>
        <div class="single-step w-full {{ $stepTwoState }} {{ $stepTwoClass }}">
            <div class="w-full h-2 rounded-full {{ $stepTwoColor }} progress_bar mb-5"></div>
            <div class="">
                <div class="text-theme-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Step - 2') }}</div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ $stepTwoTitle }}</h4>
            </div>
        </div>
    </div>
</div>
