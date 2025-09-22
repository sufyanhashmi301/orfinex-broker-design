@props([
    'href' => '#',
    'variant' => 'primary', // primary, secondary, outline, ghost, danger
    'size' => 'md', // sm, md, lg
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'iconOnly' => false, // true for icon-only buttons
    'fullWidth' => false,
    'target' => null,
    'external' => false // Opens in new tab/window
])

@php
    $baseClasses = [
        'inline-flex items-center justify-center transition-colors duration-fast no-underline',
        'focus:outline-none'
    ];
    
    $sizeClasses = [
        'sm' => $iconOnly ? 'p-1.5 text-theme-xs rounded w-8 h-8' : 'px-4 py-1.5 text-theme-xs rounded gap-1.5 h-8',
        'md' => $iconOnly ? 'p-2.5 text-theme-sm rounded-sm w-10 h-10' : 'px-5 py-2.5 text-theme-sm rounded-sm gap-2 h-10',
        'lg' => $iconOnly ? 'p-3 text-base rounded-md w-12 h-12' : 'px-6 py-3 text-base rounded-md gap-2 h-12'
    ];
    
    $variantClasses = [
        'primary' => 'bg-brand-500 text-white hover:bg-brand-600 shadow-theme-xs',
        'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700',
        'outline' => 'border border-gray-300 bg-transparent text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800',
        'ghost' => 'bg-transparent text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800',
        'danger' => 'bg-red-500 text-white hover:bg-red-600 shadow-theme-xs'
    ];
    
    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : '';
    
    $classes = array_merge(
        $baseClasses,
        [$sizeClasses[$size] ?? $sizeClasses['md']],
        [$variantClasses[$variant] ?? $variantClasses['primary']],
        $fullWidth ? ['w-full'] : [],
        $disabledClasses ? [$disabledClasses] : []
    );
    
    // Handle external links
    $targetAttr = $external ? '_blank' : $target;
    $relAttr = $external ? 'noopener noreferrer' : null;
@endphp

<a 
    href="{{ $disabled ? '#' : $href }}"
    @if($targetAttr) target="{{ $targetAttr }}" @endif
    @if($relAttr) rel="{{ $relAttr }}" @endif
    {{ $attributes->merge(['class' => implode(' ', array_filter($classes))]) }}
>
    @if($iconOnly && $icon)
        <i data-lucide="{{ $icon }}" class="@if($size === 'sm') w-3 h-3 @elseif($size === 'lg') w-5 h-5 @else w-4 h-4 @endif shrink-0"></i>
    @else
        @if($icon && $iconPosition === 'left')
            <i data-lucide="{{ $icon }}" class="@if($size === 'sm') w-3 h-3 @else w-4 h-4 @endif"></i>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <i data-lucide="{{ $icon }}" class="@if($size === 'sm') w-3 h-3 @else w-4 h-4 @endif"></i>
        @endif
        
        @if($external && !$iconOnly)
            <i data-lucide="external-link" class="@if($size === 'sm') w-3 h-3 @else w-4 h-4 @endif ml-1 opacity-70"></i>
        @endif
    @endif
</a>