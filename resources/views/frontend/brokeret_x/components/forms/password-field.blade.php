@props([
    'fieldId' => '',
    'fieldLabel' => '',
    'fieldName' => '',
    'fieldPlaceholder' => '',
    'fieldValue' => '',
    'fieldRequired' => false,
    'fieldReadOnly' => false,
    'popover' => false,
    'containerClass' => ''
])

<div class="{{ $containerClass }}">
    <x-frontend::forms.label 
        :fieldId="$fieldId" 
        :fieldLabel="$fieldLabel" 
        :popover="$popover" 
        :fieldRequired="$fieldRequired"
    />
    <div class="relative" x-data="{ showPassword: false }">
        <x-frontend::forms.input 
            {{ $attributes->merge(['class' => 'pr-12']) }}
            x-bind:type="showPassword ? 'text' : 'password'"
            type="password"
            :fieldId="$fieldId"
            :fieldName="$fieldName" 
            :fieldPlaceholder="$fieldPlaceholder"
            :fieldValue="$fieldValue"
            :fieldRequired="$fieldRequired"
            :fieldReadOnly="$fieldReadOnly"
        />
        <button type="button" 
            @click="showPassword = !showPassword"
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 focus:outline-none">
            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
            </svg>
        </button>
    </div>
    @error($fieldName)
        <span class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</span>
    @enderror
</div>
