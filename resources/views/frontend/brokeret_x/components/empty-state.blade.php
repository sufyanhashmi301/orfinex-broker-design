@props([
    'icon' => null,
    'iconSize' => 'default', // sm, default, lg
    'title' => null,
    'subtitle' => null,
    'spacing' => 'default', // compact, default, spacious
    'shadow' => false,
    'border' => false,
    'actions' => null,
])

@php
    $iconSizes = [
        'sm' => 'w-8 h-8',
        'default' => 'w-13 h-13',
        'lg' => 'w-16 h-16'
    ];
    
    $spacingClasses = [
        'compact' => 'px-6 mt-6 lg:mt-10',
        'default' => 'px-10 mt-10 lg:mt-20',
        'spacious' => 'px-12 mt-12 lg:mt-24'
    ];

    $classes = [
        'flex flex-col items-center justify-center text-center gap-3',
        $border ? 'border border-gray-200 dark:border-gray-800 rounded-2xl' : '',
        $shadow ? 'shadow-theme-xs' : '',
        $spacingClasses[$spacing] ?? $spacingClasses['default'],
    ];
@endphp

<div {{ $attributes->merge(['class' => implode(' ', array_filter($classes))]) }} 
     x-data 
     x-init="$nextTick(() => {
         if (window.renderLucideIcons && typeof window.renderLucideIcons === 'function') {
             window.renderLucideIcons();
         } else if (window.lucide && typeof lucide.createIcons === 'function') {
             lucide.createIcons();
         }
     })">
    @if($icon)
        <i data-lucide="{{ $icon }}" class="{{ $iconSizes[$iconSize] ?? $iconSizes['default'] }} text-gray-400 dark:text-gray-500"></i>
    @endif

    @if($title)
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
            {{ $title }}
        </h2>
    @endif

    @if($subtitle)
        <p class="text-theme-sm text-gray-500 dark:text-gray-400 tracking-wide">
            {{ $subtitle }}
        </p>
    @endif

    @if($actions)
        <div class="flex flex-wrap justify-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
