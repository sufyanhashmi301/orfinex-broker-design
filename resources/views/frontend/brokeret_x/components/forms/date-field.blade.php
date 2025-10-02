@props([
    'fieldId' => '',
    'fieldLabel' => '',
    'fieldName' => '',
    'fieldValue' => '',
    'fieldPlaceholder' => 'Select date',
    'fieldRequired' => false,
    'fieldReadOnly' => false,
    'disabled' => false,
    'popover' => false,
    'containerClass' => '',
    'min' => '',
    'max' => '',
    'size' => 'md'
])

<div class="{{ $containerClass }}">
    <x-frontend::forms.label 
        :fieldId="$fieldId" 
        :fieldLabel="$fieldLabel" 
        :popover="$popover" 
        :fieldRequired="$fieldRequired"
    />
    
    <x-frontend::forms.date-input 
        :fieldId="$fieldId"
        :fieldName="$fieldName"
        :fieldValue="$fieldValue"
        :fieldPlaceholder="$fieldPlaceholder"
        :fieldRequired="$fieldRequired"
        :fieldReadOnly="$fieldReadOnly"
        :disabled="$disabled"
        :min="$min"
        :max="$max"
        :size="$size"
        {{ $attributes }}
    />
    
    @error($fieldName)
        <span class="text-xs text-error-500 mt-1.5 block">{{ $message }}</span>
    @enderror
</div>
