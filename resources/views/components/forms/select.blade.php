@props([
    'fieldId' => '',
    'fieldName' => '',
    'fieldValue' => '',
    'fieldRequired' => false,
    'fieldReadOnly' => false,
    'options' => [],
    'placeholder' => null
])

<select {{ $attributes->merge(['class' => 'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800']) }}
    id="{{ $fieldId }}"
    name="{{ $fieldName }}"
    @if($fieldRequired) required @endif
    @readonly($fieldReadOnly == 'true')
>
    @if($placeholder)
        <option value="" {{ !$fieldValue ? 'selected' : '' }}>{{ $placeholder }}</option>
    @endif
    
    {{ $slot }}
    
    @foreach($options as $value => $label)
        <option value="{{ $value }}" {{ $fieldValue == $value ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
</select>

@error($fieldName)
    <span class="text-xs text-error-500 mt-1.5 block">{{ $message }}</span>
@enderror