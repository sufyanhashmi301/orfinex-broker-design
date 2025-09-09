@props([
    'fieldId' => '',
    'fieldLabel' => '',
    'fieldName' => '',
    'fieldValue' => '',
    'fieldRequired' => false,
    'fieldReadOnly' => false,
    'disabled' => false,
    'popover' => false,
    'containerClass' => '',
    'options' => [],
    'placeholder' => null
])

<div class="{{ $containerClass }}">
    <x-frontend::forms.label 
        :fieldId="$fieldId" 
        :fieldLabel="$fieldLabel" 
        :popover="$popover" 
        :fieldRequired="$fieldRequired"
    />
    <x-frontend::forms.select 
        :fieldId="$fieldId" 
        :fieldName="$fieldName" 
        :fieldValue="$fieldValue"
        :fieldReadOnly="$fieldReadOnly"
        :disabled="$disabled"
        :fieldRequired="$fieldRequired"
        :options="$options"
        :placeholder="$placeholder"
        {{ $attributes }}
    >
        {{ $slot }}
    </x-frontend::forms.select>
    @error($fieldName)
        <span class="text-xs text-error-500 mt-1.5 block">{{ $message }}</span>
    @enderror
</div>
