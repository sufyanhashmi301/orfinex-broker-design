<x-frontend::forms.field
    :fieldId="'methodName'"
    :fieldName="'method_name'"
    :fieldLabel="__('Method Name')"
    :fieldValue="$withdrawMethod->name .'-'. $withdrawMethod->currency"
    :fieldPlaceholder="__('eg. Withdraw Method - USD')"
/>

@foreach(json_decode($withdrawMethod->fields, true) as $key => $field)

    @if($field['type'] == 'file')

        <input type="hidden" name="credentials[{{ $field['name']}}][type]" value="{{ $field['type'] }}">
        <input type="hidden" name="credentials[{{ $field['name']}}][validation]" value="{{ $field['validation'] }}">
        <x-frontend::forms.file-field
            :fieldId="$field['name']"
            :fieldName="'credentials[' . $field['name'] . '][value]'"
            :fieldLabel="__($field['name'])"
            :fieldRequired="$field['validation'] == 'required'"
            :fieldValue="$field['value'] ?? ''"
        />
    @elseif($field['type'] == 'textarea')
        <input type="hidden" name="credentials[{{ $field['name']}}][type]" value="{{ $field['type'] }}">
        <input type="hidden" name="credentials[{{ $field['name']}}][validation]" value="{{ $field['validation'] }}">
        <x-frontend::forms.textarea-field
            :fieldId="$field['name']"
            :fieldName="'credentials[' . $field['name'] . '][value]'"
            :fieldLabel="__($field['name'])"
            :fieldRequired="$field['validation'] == 'required'"
            :fieldValue="$field['value']"
            :fieldPlaceholder="__('Send Money Note')"
        />

    @else
        <input type="hidden" name="credentials[{{ $field['name']}}][type]" value="{{ $field['type'] }}">
        <input type="hidden" name="credentials[{{ $field['name']}}][validation]" value="{{ $field['validation'] }}">
        <x-frontend::forms.field
            :fieldId="$field['name']"
            :fieldName="'credentials[' . $field['name'] . '][value]'"
            :fieldLabel="ucwords( str_replace('_',' ', $field['name']) )"
            :fieldRequired="$field['validation'] == 'required'"
            :fieldValue="$field['value'] ?? ''"
            :fieldPlaceholder="__($field['name'])"
        />
    @endif

@endforeach
