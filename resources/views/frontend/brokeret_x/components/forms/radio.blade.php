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
    
    $dotSizeClasses = [
        'sm' => 'h-1.5 w-1.5',
        'md' => 'h-2 w-2', 
        'lg' => 'h-2.5 w-2.5'
    ];
    
    $colorClasses = [
        'brand' => 'border-brand-500 bg-brand-500',
        'success' => 'border-green-500 bg-green-500',
        'error' => 'border-red-500 bg-red-500',
        'warning' => 'border-yellow-500 bg-yellow-500'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $dotSizeClass = $dotSizeClasses[$size] ?? $dotSizeClasses['md'];
    $checkedColorClass = $colorClasses[$color] ?? $colorClasses['brand'];
    
    // Generate fieldId if not provided
    $fieldId = $fieldId ?: 'radio_' . uniqid() . '_' . md5($fieldName . $fieldValue);
    
    // Better checked state logic
    $oldValue = old($fieldName);
    $isChecked = $checked || ($oldValue && $oldValue == $fieldValue);
@endphp

<div class="{{ $containerClass }}" x-data="{ radioToggle: {{ $isChecked ? 'true' : 'false' }} }">
    <label 
        for="{{ $fieldId }}"
        class="flex items-center text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400 {{ $fieldReadOnly ? 'opacity-50 cursor-not-allowed' : 'hover:text-gray-900 dark:hover:text-gray-200' }}"
    >
        <input 
            type="radio" 
            name="{{ $fieldName }}" 
            id="{{ $fieldId }}"
            value="{{ $fieldValue ?: '1' }}"
            class="sr-only" 
            @change="
                radioToggle = true; 
                $dispatch('radio-changed', { name: '{{ $fieldName }}', value: '{{ $fieldValue }}', id: '{{ $fieldId }}' });
            "
            @radio-changed.window="
                if ($event.detail.name === '{{ $fieldName }}' && $event.detail.id !== '{{ $fieldId }}') {
                    radioToggle = false;
                }
            "
            @if($fieldRequired) required @endif
            @if($fieldReadOnly) disabled @endif
            @if($isChecked) checked @endif
        />
        
        <div 
            :class="radioToggle ? '{{ $checkedColorClass }}' : 'bg-transparent border-gray-300 dark:border-gray-700'"
            class="mr-3 flex {{ $sizeClass }} items-center justify-center rounded-full border-[1.25px] transition-all duration-200 {{ !$fieldReadOnly ? 'hover:border-brand-400 dark:hover:border-brand-500' : '' }}"
        >
            <span 
                x-show="radioToggle"
                class="{{ $dotSizeClass }} rounded-full bg-white transition-all duration-200"
            ></span>
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
