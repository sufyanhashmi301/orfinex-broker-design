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
        'inline-flex items-center justify-center font-medium transition-colors duration-fast',
        'focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed'
    ];
    
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm rounded-md gap-1.5 h-8',
        'md' => 'px-4 py-2.5 text-sm rounded-lg gap-2 h-10',
        'lg' => 'px-6 py-3 text-base rounded-lg gap-2 h-12'
    ];
    
    $variantClasses = [
        'primary' => 'bg-brand-500 text-white hover:bg-brand-600 shadow-theme-xs',
        'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700',
        'outline' => 'border border-gray-300 bg-transparent text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800',
        'ghost' => 'bg-transparent text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800',
        'danger' => 'bg-red-500 text-white hover:bg-red-600 shadow-theme-xs'
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
    @if($disabled || $loading) disabled @endif
    {{ $attributes->merge(['class' => implode(' ', $classes)]) }}
>
    @if($loading)
        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>{{ __('Loading...') }}</span>
    @else
        @if($icon && $iconPosition === 'left')
            <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
        @endif
    @endif
</button>
