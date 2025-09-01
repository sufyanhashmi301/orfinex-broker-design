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
    'error-message' => null
])

<div class="{{ $attributes->get('container-class') }}">
    <x-frontend::forms.label 
        :fieldId="$attributes->get('field-id')" 
        :fieldLabel="$attributes->get('field-label')" 
        :popover="$popover" 
        :fieldRequired="$attributes->get('field-required')"
    />
    <div class="relative">
        @if($prefix)
            <span class="absolute top-1/2 left-0 inline-flex -translate-y-1/2 cursor-pointer items-center gap-1 border-r border-gray-200 py-3 pl-3 pr-3.5 text-sm font-medium text-gray-700 dark:border-gray-800 dark:text-gray-400">
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
            {{ $attributes->except(['field-id', 'field-name', 'field-placeholder', 'field-value', 'field-required', 'field-readonly', 'field-label', 'container-class'])->merge(['class' => ($suffix ? 'pr-16' : '') . ($prefix ? ' pl-16' : '')]) }}
        />
        
        @if($suffix)
            <span class="absolute top-1/2 right-0 inline-flex -translate-y-1/2 cursor-pointer items-center gap-1 border-l border-gray-200 py-3 pr-3 pl-3.5 text-sm font-medium text-gray-700 dark:border-gray-800 dark:text-gray-400">
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
