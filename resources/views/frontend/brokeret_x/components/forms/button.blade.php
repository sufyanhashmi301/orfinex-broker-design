@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, outline, ghost, danger
    'size' => 'md', // sm, md, lg
    'loading' => false,
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'fullWidth' => false
])

@php
    $baseClasses = [
        'inline-flex items-center justify-center transition-colors duration-fast',
        'focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2',
        'disabled:opacity-50 disabled:cursor-not-allowed'
    ];
    
    $sizeClasses = [
        'sm' => 'px-4 py-1.5 text-theme-xs rounded gap-1.5 h-8',
        'md' => 'px-5 py-2.5 text-theme-sm rounded-sm gap-2 h-10',
        'lg' => 'px-6 py-3 text-base rounded-md gap-2 h-12'
    ];
    
    $variantClasses = [
        'primary' => 'bg-brand-500 text-white hover:bg-brand-600 focus-visible:ring-brand-500 shadow-theme-xs',
        'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 focus-visible:ring-gray-400 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700',
        'outline' => 'border border-gray-300 bg-transparent text-gray-700 hover:bg-gray-50 focus-visible:ring-gray-400 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800',
        'ghost' => 'bg-transparent text-gray-700 hover:bg-gray-100 focus-visible:ring-gray-400 dark:text-gray-300 dark:hover:bg-gray-800',
        'danger' => 'bg-red-500 text-white hover:bg-red-600 focus-visible:ring-red-500 shadow-theme-xs'
    ];
    
    $classes = array_merge(
        $baseClasses,
        [$sizeClasses[$size] ?? $sizeClasses['md']],
        [$variantClasses[$variant] ?? $variantClasses['primary']],
        $fullWidth ? ['w-full'] : []
    );
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => implode(' ', $classes)]) }}
    @if($disabled || $loading) disabled @endif
>
    @if($loading)
        <svg class="animate-spin w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.37 0 0 5.37 0 12h4zm2 5.29A7.96 7.96 0 014 12H0c0 3.04 1.13 5.82 3 7.94l3-2.65z"></path>
        </svg>
        <span class="ml-2">{{ __('Loading...') }}</span>
    @else
        @if($icon && $iconPosition === 'left')
            <i data-lucide="{{ $icon }}" class="@if($size === 'sm') w-3 h-3 @else w-4 h-4 @endif shrink-0"></i>
        @endif

        <span>{{ $slot }}</span>
        
        @if($icon && $iconPosition === 'right')
            <i data-lucide="{{ $icon }}" class="@if($size === 'sm') w-3 h-3 @else w-4 h-4 @endif shrink-0"></i>
        @endif
    @endif
</button>
