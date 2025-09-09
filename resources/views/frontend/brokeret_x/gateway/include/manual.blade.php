<div class="col-span-12">
    <div class="frontend-editor-data space-y-4 text-lg">
        <h6 class="text-slate-900 dark:text-white">
            {{ __('Account Details:') }}
        </h6>
        <div class="text-slate-900 dark:text-slate-300">
            {!! $paymentDetails !!}
        </div>
    </div>
</div>
@foreach(json_decode($fieldOptions, true) as $key => $field)

    @if($field['type'] == 'file')
        <x-frontend::forms.file-field
            fieldId="{{ $key }}"
            fieldName="manual_data[{{ $field['name'] }}]"
            fieldLabel="{{ __('' . $field['name']) }}"
            fieldRequired="{{ $field['validation'] == 'required' ? 'true' : 'false' }}"
            accept=".gif, .jpg, .png"
        />
    @elseif($field['type'] == 'textarea')
        <x-frontend::forms.textarea-field
            fieldId="{{ $key }}"
            fieldName="manual_data[{{ $field['name'] }}]"
            fieldLabel="{{ __('' . $field['name']) }}"
            fieldPlaceholder="{{ __('Send Money Note') }}"
            fieldRequired="{{ $field['validation'] == 'required' ? 'true' : 'false' }}"
        />
        
    @else
        <x-frontend::forms.field
            fieldId="{{ str_replace(' ', '_', $field['name']) }}"
            fieldName="manual_data[{{ $field['name'] }}]"
            fieldLabel="{{ __('' . $field['name']) }}"
            fieldRequired="{{ $field['validation'] == 'required' ? 'true' : 'false' }}"

        />
    @endif

@endforeach
