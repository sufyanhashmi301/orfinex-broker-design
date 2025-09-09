@props([
    'fieldId' => '',
    'fieldLabel' => '',
    'fieldName' => '',
    'fieldPlaceholder' => '',
    'fieldValue' => '',
    'fieldRequired' => false,
    'fieldReadOnly' => false,
    'disabled' => false,
    'popover' => false,
    'containerClass' => '',
    'rows' => 4
])

<div class="{{ $containerClass }}">
    <x-frontend::forms.label 
        :fieldId="$fieldId" 
        :fieldLabel="$fieldLabel" 
        :popover="$popover" 
        :fieldRequired="$fieldRequired"
    />
    <x-frontend::forms.textarea 
        :fieldId="$fieldId" 
        :fieldName="$fieldName" 
        :fieldPlaceholder="$fieldPlaceholder" 
        :fieldValue="$fieldValue"
        :fieldReadOnly="$fieldReadOnly"
        :disabled="$disabled"
        :fieldRequired="$fieldRequired"
        :rows="$rows"
        {{ $attributes }}
    />
    @error($fieldName)
        <span class="text-xs text-error-500 mt-1.5 block">{{ $message }}</span>
    @enderror
</div>
