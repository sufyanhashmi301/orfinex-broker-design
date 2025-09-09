@props([
    'fieldId' => '',
    'fieldLabel' => 'Upload file',
    'fieldName' => '',
    'fieldRequired' => false,
    'disabled' => false,
    'popover' => false,
    'containerClass' => '',
    'accept' => '',
    'multiple' => false
])

<div class="{{ $containerClass }}">
    <x-frontend::forms.label 
        :fieldId="$fieldId" 
        :fieldLabel="$fieldLabel" 
        :popover="$popover" 
        :fieldRequired="$fieldRequired"
    />
    <x-frontend::forms.file-input 
        :fieldId="$fieldId" 
        :fieldName="$fieldName" 
        :fieldRequired="$fieldRequired"
        :disabled="$disabled"
        :accept="$accept"
        :multiple="$multiple"
        {{ $attributes }}
    />
    @error($fieldName)
        <span class="text-xs text-error-500 mt-1.5 block">{{ $message }}</span>
    @enderror
</div>
