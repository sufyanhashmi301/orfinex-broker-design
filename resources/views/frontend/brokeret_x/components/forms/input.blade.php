@props([
    'type' => 'text',
    'fieldId' => '',
    'fieldName' => '',
    'fieldPlaceholder' => '',
    'fieldValue' => '',
    'fieldRequired' => false,
    'fieldReadOnly' => false,
    'disabled' => false,
    'size' => 'md'
])

@php
$sizeClasses = [
    'xs' => 'h-8 px-3 py-1.5 text-xs',
    'sm' => 'h-9 px-3 py-2 text-sm',
    'md' => 'h-10 px-4 py-2.5 text-sm',
    'lg' => 'h-12 px-4 py-3 text-base',
    'xl' => 'h-14 px-5 py-3.5 text-base'
];
$appliedSizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<input {{ $attributes->merge(['class' => "dark:bg-dark-900 w-full rounded-sm border border-gray-300 bg-transparent text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 {$appliedSizeClass}"]) }}
    type="{{ $type }}"
    id="{{ $fieldId }}"
    name="{{ $fieldName }}"
    placeholder="{{ $fieldPlaceholder }}"
    value="{{ $fieldValue }}"
    @if($fieldRequired) required @endif
    @if($fieldReadOnly) readonly @endif
    @if($disabled) disabled @endif
/>