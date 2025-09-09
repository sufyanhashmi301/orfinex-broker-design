@props([
    'fieldId' => '',
    'fieldName' => '',
    'fieldPlaceholder' => '',
    'fieldValue' => '',
    'fieldRequired' => false,
    'fieldReadOnly' => false,
    'disabled' => false,
    'rows' => 4
])

<textarea {{ $attributes->merge(['class' => 'dark:bg-dark-900 w-full rounded-sm border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 resize-vertical']) }}
    id="{{ $fieldId }}"
    name="{{ $fieldName }}"
    placeholder="{{ $fieldPlaceholder }}"
    rows="{{ $rows }}"
    @if($fieldRequired) required @endif
    @if($fieldReadOnly) readonly @endif
    @if($disabled) disabled @endif
>{{ $fieldValue }}</textarea>
