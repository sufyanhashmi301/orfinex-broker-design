@props([
    'title' => null,
    'subtitle' => null,
    'padding' => 'md', // sm, md, lg, none
    'shadow' => true,
    'border' => true,
    'headerActions' => null
])

@php
    $paddingClasses = [
        'none' => '',
        'sm' => 'p-4',
        'md' => 'p-5 md:p-6',
        'lg' => 'p-6 md:p-8'
    ];
    
    $classes = [
        'rounded-2xl bg-white dark:bg-white/[0.03]',
        $border ? 'border border-gray-200 dark:border-gray-800' : '',
        $shadow ? 'shadow-theme-xs' : '',
        $paddingClasses[$padding] ?? $paddingClasses['md']
    ];
@endphp

<div {{ $attributes->merge(['class' => implode(' ', array_filter($classes))]) }}>
    @if($title || $subtitle || $headerActions)
        <div class="flex items-start justify-between {{ $padding !== 'none' ? 'mb-4' : 'p-5 pb-4 md:p-6 md:pb-4' }}">
            <div class="flex-1 min-w-0">
                @if($title)
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 truncate">
                        {{ $title }}
                    </h3>
                @endif
                @if($subtitle)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
            @if($headerActions)
                <div class="flex-shrink-0 ml-4">
                    {{ $headerActions }}
                </div>
            @endif
        </div>
    @endif
    
    <div class="{{ $title || $subtitle || $headerActions ? ($padding !== 'none' ? '' : 'p-5 pt-0 md:p-6 md:pt-0') : '' }}">
        {{ $slot }}
    </div>
</div>