@props([
    'href' => '#',
    'variant' => 'primary', // primary, warning, error
    'size' => 'sm', // sm, md, lg
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'target' => null,
    'external' => false // Opens in new tab/window
])

@php
    $sizeClasses = [
        'sm' => 'text-sm gap-1.5',
        'md' => 'text-base gap-2',
        'lg' => 'text-lg gap-2'
    ];
    
    $variantClasses = [
        'primary' => 'text-brand-500 hover:text-brand-600 dark:text-brand-400',
        'warning' => 'text-yellow-500 hover:text-yellow-600 dark:text-yellow-400',
        'error' => 'text-red-500 hover:text-red-600 dark:text-red-400'
    ];
    
    $baseClasses = [
        'inline-flex items-center font-medium transition-colors duration-fast no-underline',
        'focus:outline-none'
    ];
    
    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : '';
    
    $classes = array_merge(
        $baseClasses,
        [$sizeClasses[$size] ?? $sizeClasses['sm']],
        [$variantClasses[$variant] ?? $variantClasses['primary']],
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
    @if($icon && $iconPosition === 'left')
        <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
        <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
    @endif
    
    @if($external)
        <i data-lucide="external-link" class="w-3 h-3 ml-1 opacity-70"></i>
    @endif
</a>
