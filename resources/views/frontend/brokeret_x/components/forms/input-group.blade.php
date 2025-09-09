@props([
    'type' => 'text',
    'field-id' => '',
    'field-label' => '',
    'field-name' => '',
    'field-placeholder' => '',
    'field-value' => '',
    'field-required' => false,
    'field-readonly' => false,
    'disabled' => false,
    'popover' => false,
    'container-class' => '',
    'suffix' => null,
    'prefix' => null,
    'error-message' => null,
    'size' => 'md'
])

<div class="{{ $attributes->get('container-class') }}">
    <x-frontend::forms.label 
        :fieldId="$attributes->get('field-id')" 
        :fieldLabel="$attributes->get('field-label')" 
        :popover="$popover" 
        :fieldRequired="$attributes->get('field-required')"
    />
    @php
    $prefixSuffixClasses = [
        'xs' => 'py-1.5 pl-3 pr-3 text-xs',
        'sm' => 'py-2 pl-3 pr-3 text-xs',
        'md' => 'py-3 pl-3 pr-3.5 text-sm',
        'lg' => 'py-3 pl-4 pr-4 text-sm',
        'xl' => 'py-3.5 pl-5 pr-5 text-base'
    ];
    $prefixSuffixClass = $prefixSuffixClasses[$size] ?? $prefixSuffixClasses['md'];
    
    $inputPadding = [
        'xs' => ['prefix' => 'pl-12', 'suffix' => 'pr-12'],
        'sm' => ['prefix' => 'pl-12', 'suffix' => 'pr-12'],
        'md' => ['prefix' => 'pl-16', 'suffix' => 'pr-16'],
        'lg' => ['prefix' => 'pl-16', 'suffix' => 'pr-16'],
        'xl' => ['prefix' => 'pl-20', 'suffix' => 'pr-20']
    ];
    $padding = $inputPadding[$size] ?? $inputPadding['md'];
    
    $inputClasses = '';
    if ($prefix) $inputClasses .= ' ' . $padding['prefix'];
    if ($suffix) $inputClasses .= ' ' . $padding['suffix'];
    @endphp
    
    <div class="relative">
        @if($prefix)
            <span class="absolute top-1/2 left-0 inline-flex -translate-y-1/2 cursor-pointer items-center gap-1 border-r border-gray-200 font-medium text-gray-700 dark:border-gray-800 dark:text-gray-400 {{ $prefixSuffixClass }}">
                {{ $prefix }}
            </span>
        @endif
        
        <x-frontend::forms.input 
            :type="$type"
            :fieldId="$attributes->get('field-id')"
            :fieldName="$attributes->get('field-name')"
            :fieldPlaceholder="$attributes->get('field-placeholder')"
            :fieldValue="$attributes->get('field-value')"
            :fieldRequired="$attributes->get('field-required')"
            :fieldReadOnly="$attributes->get('field-readonly')"
            :disabled="$disabled"
            :size="$size"
            {{ $attributes->except(['field-id', 'field-name', 'field-placeholder', 'field-value', 'field-required', 'field-readonly', 'field-label', 'container-class', 'size'])->merge(['class' => $inputClasses]) }}
        />
        
        @if($suffix)
            <span class="absolute top-1/2 right-0 inline-flex -translate-y-1/2 cursor-pointer items-center gap-1 border-l border-gray-200 font-medium text-gray-700 dark:border-gray-800 dark:text-gray-400 {{ $prefixSuffixClass }}">
                {{ $suffix }}
            </span>
        @endif
    </div>
    
    @if($attributes->get('error-message'))
        <div class="text-theme-xs text-error-500 mt-1">{{ $attributes->get('error-message') }}</div>
    @endif
    
    @if($slot->isNotEmpty())
        <div class="text-theme-xs text-error-500 mt-1">{{ $slot }}</div>
    @endif
    
    @error($attributes->get('field-name'))
        <span class="text-xs text-error-500 mt-1.5 block">{{ $message }}</span>
    @enderror
</div>
