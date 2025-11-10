@props([
    'variant' => 'primary', // primary, success, error, warning, info, light, dark
    'style' => 'light', // light, solid
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'size' => 'sm' // xs, sm, md, lg
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-1 rounded-full font-medium';
    
    // Size classes
    $sizeClasses = [
        'xs' => 'px-2 py-0.5 text-xs tracking-wide',
        'sm' => 'px-2.5 py-1 text-theme-xs tracking-wide',
        'md' => 'px-3 py-1.5 text-sm tracking-wide',
        'lg' => 'px-4 py-2 text-base tracking-wide'
    ];
    
    // Light background variants
    $lightVariants = [
        'primary' => 'bg-brand-50 text-brand-500 dark:bg-brand-500/15 dark:text-brand-400',
        'success' => 'bg-success-50 text-success-700 dark:bg-success-900/20 dark:text-success-400',
        'error' => 'bg-error-50 text-error-700 dark:bg-error-900/20 dark:text-error-400',
        'warning' => 'bg-warning-50 text-warning-700 dark:bg-warning-900/20 dark:text-warning-400',
        'info' => 'bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500',
        'light' => 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80',
        'dark' => 'bg-gray-500 text-white dark:bg-white/5 dark:text-white'
    ];
    
    // Solid background variants
    $solidVariants = [
        'primary' => 'bg-brand-500 text-white',
        'success' => 'bg-success-500 text-white',
        'error' => 'bg-error-500 text-white',
        'warning' => 'bg-warning-500 text-white',
        'info' => 'bg-blue-light-500 text-white',
        'light' => 'bg-gray-100 text-white dark:bg-white/5 dark:text-white/80',
        'dark' => 'bg-gray-800 text-white dark:bg-white/5 dark:text-white/80'
    ];
    
    $variants = $style === 'solid' ? $solidVariants : $lightVariants;
    
    $classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variants[$variant];
    
    // Icon size based on badge size
    $iconSizes = [
        'xs' => 'w-3 h-3',
        'sm' => 'w-3.5 h-3.5', 
        'md' => 'w-4 h-4',
        'lg' => 'w-5 h-5'
    ];
    
    $iconSize = $iconSizes[$size];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon && $iconPosition === 'left')
        <i data-lucide="{{ $icon }}" class="{{ $iconSize }}"></i>
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
        <i data-lucide="{{ $icon }}" class="{{ $iconSize }}"></i>
    @endif
</span>
