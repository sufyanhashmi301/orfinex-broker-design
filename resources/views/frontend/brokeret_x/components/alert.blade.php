@props([
    'type' => 'info',
    'dismissible' => true,
    'icon' => null,
    'title' => null
])

@php
    $typeClasses = [
        'success' => 'border-success-500 bg-success-50 dark:border-success-500/30 dark:bg-success-500/15',
        'error' => 'border-error-500 bg-error-50 dark:border-error-500/30 dark:bg-error-500/15',
        'warning' => 'border-warning-500 bg-warning-50 dark:border-warning-500/30 dark:bg-warning-500/15',
        'info' => 'border-brand-500 bg-brand-50 dark:border-brand-500/30 dark:bg-brand-500/15'
    ];

    $iconMap = [
        'success' => 'circle-check',
        'error' => 'circle-alert',
        'warning' => 'triangle-alert',
        'info' => 'info'
    ];

    $iconColors = [
        'success' => 'text-success-500',
        'error' => 'text-error-500',
        'warning' => 'text-warning-500',
        'info' => 'text-brand-500'
    ];

    $alertClasses = $typeClasses[$type] ?? $typeClasses['info'];
    
    // Handle icon logic
    if ($icon) {
        // Use default icon for type
        $alertIcon = $iconMap[$type];
    } else {
        // No icon
        $alertIcon = null;
    }
    
    $iconColor = $iconColors[$type] ?? $iconColors['info'];
@endphp

<div {{ $attributes->merge(['class' => "rounded-xl border p-4 {$alertClasses}"]) }} role="alert">
    <div class="flex items-start gap-3">
        {{-- Show icon when icon prop is provided --}}
        @if($icon && $alertIcon)
            <div class="-mt-0.5 {{ $iconColor }}">
                <i data-lucide="{{ $alertIcon }}" class="w-5 h-5"></i>
            </div>
        @endif
        
        <div class="flex-1">
            @if($title)
                <h4 class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-1">
                    {{ $title }}
                </h4>
            @endif
            
            <div class="text-sm text-gray-600 dark:text-gray-400">
                {{ $slot }}
            </div>
        </div>

        @if($dismissible && $dismissible !== 'false')
            <div class="ms-auto">
                <button type="button" 
                    class="inline-block text-sm font-medium text-gray-500 dark:text-gray-400 hover:opacity-75 transition-opacity"
                    onclick="this.closest('[role=alert]').remove()"
                    aria-label="{{ __('Close') }}">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif
    </div>
</div>
