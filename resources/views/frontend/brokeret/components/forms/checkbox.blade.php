@props([
    'fieldId' => '',
    'fieldName' => '',
    'fieldValue' => '',
    'fieldRequired' => false,
    'fieldReadOnly' => false,
    'checked' => false,
    'label' => '',
    'size' => 'md', // sm, md, lg
    'color' => 'brand', // brand, success, error, warning
    'containerClass' => ''
])

@php
    $sizeClasses = [
        'sm' => 'h-4 w-4',
        'md' => 'h-5 w-5', 
        'lg' => 'h-6 w-6'
    ];
    
    $colorClasses = [
        'brand' => 'border-brand-500 bg-brand-500',
        'success' => 'border-green-500 bg-green-500',
        'error' => 'border-red-500 bg-red-500',
        'warning' => 'border-yellow-500 bg-yellow-500'
    ];
    
    $iconSizes = [
        'sm' => 'width="12" height="12"',
        'md' => 'width="14" height="14"',
        'lg' => 'width="16" height="16"'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $checkedColorClass = $colorClasses[$color] ?? $colorClasses['brand'];
    $iconSize = $iconSizes[$size] ?? $iconSizes['md'];
    $isChecked = $checked || old($fieldName) || $fieldValue;
@endphp

<div class="{{ $containerClass }}" x-data="{ checkboxToggle: {{ $isChecked ? 'true' : 'false' }} }">
    <label 
        for="{{ $fieldId }}"
        class="flex items-center text-sm font-normal text-gray-700 cursor-pointer select-none dark:text-gray-400 {{ $fieldReadOnly ? 'opacity-50 cursor-not-allowed' : 'hover:text-gray-900 dark:hover:text-gray-200' }}"
    >
        <input 
            type="checkbox" 
            name="{{ $fieldName }}" 
            id="{{ $fieldId }}"
            value="{{ $fieldValue ?: '1' }}"
            class="sr-only" 
            @change="checkboxToggle = !checkboxToggle"
            @if($fieldRequired) required @endif
            @if($fieldReadOnly) disabled @endif
            @if($isChecked) checked @endif
        />
        
        <div 
            :class="checkboxToggle ? '{{ $checkedColorClass }}' : 'bg-transparent border-gray-300 dark:border-gray-700'"
            class="mr-3 flex {{ $sizeClass }} items-center justify-center rounded-md border-[1.25px] transition-all duration-200 {{ !$fieldReadOnly ? 'hover:border-brand-400 dark:hover:border-brand-500' : '' }}"
        >
            <span :class="checkboxToggle ? '' : 'opacity-0'" class="transition-opacity duration-200">
                <svg {!! $iconSize !!} viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path 
                        d="M11.6666 3.5L5.24992 9.91667L2.33325 7" 
                        stroke="white" 
                        stroke-width="1.94437" 
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                    />
                </svg>
            </span>
        </div>
        
        @if($label)
            <span class="inline-block font-normal text-gray-500 dark:text-gray-400">
                {{ $label }}
            </span>
        @endif
        
        {{ $slot }}
    </label>
    
    @error($fieldName)
        <span class="text-xs text-error-500 mt-1.5 ml-8 block">{{ $message }}</span>
    @enderror
</div>
