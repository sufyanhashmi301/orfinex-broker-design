@props([
    'fieldId' => '',
    'fieldLabel' => '',
    'fieldRequired' => false,
    'popover' => false
])

<label {{ $attributes->merge(['class' => 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400']) }} data-label="{{ $fieldRequired }}" for="{{ $fieldId }}">
    @if ($popover || $fieldRequired)
        <span class="inline-flex items-center gap-1">
            {!! $fieldLabel ?? '&nbsp;' !!}
            
            @if ($popover)
                <svg xmlns="http://www.w3.org/2000/svg" width="1rem" height="1rem" viewBox="0 0 24 24" fill="none">
                    <path fill="currentColor" d="M12 17.75a.75.75 0 0 0 .75-.75v-6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75M12 7a1 1 0 1 1 0 2a1 1 0 0 1 0-2"/>
                    <path fill="currentColor" fill-rule="evenodd" d="M1.25 12C1.25 6.063 6.063 1.25 12 1.25S22.75 6.063 22.75 12S17.937 22.75 12 22.75S1.25 17.937 1.25 12M12 2.75a9.25 9.25 0 1 0 0 18.5a9.25 9.25 0 0 0 0-18.5" clip-rule="evenodd"/>
                </svg>
            @endif

            @if ($fieldRequired)
                <span class="text-error-500">*</span>
            @endif
        </span>
    @else
        {!! $fieldLabel ?? '&nbsp;' !!}
    @endif
</label>