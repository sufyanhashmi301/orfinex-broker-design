<div class="col-span-12">
    <div class="frontend-editor-data space-y-4 text-lg">
        <h6 class="text-slate-900 dark:text-white">
            {{ __('Account Details:') }}
        </h6>
        {!! $paymentDetails !!}
    </div>
</div>
@foreach(json_decode($fieldOptions, true) as $key => $field)

    @if($field['type'] == 'file')
        <div class="col-span-12">
            <div class="body-title">{{ __('' . $field['name']) }}</div>
            <div class="wrap-custom-file">
                <input
                    type="file"
                    name="manual_data[{{ $field['name'] }}]"
                    id="{{ $key }}"
                    accept=".gif, .jpg, .png"
                    @if($field['validation'] == 'required') required @endif
                />
                <label for="{{ $key }}">
                    <img
                        class="upload-icon"
                        src="{{ asset('global/materials/upload.svg') }}"
                        alt="{{ __('Upload Icon') }}"
                    />
                    <span>{{ __('Select ') . $field['name'] }}</span>
                </label>
            </div>
        </div>
    @elseif($field['type'] == 'textarea')
        <div class="input-area">
            <textarea class="form-control" rows="5" @if($field['validation'] == 'required') required @endif placeholder="{{ __('Send Money Note') }}" name="manual_data[{{ $field['name'] }}]"></textarea>
        </div>
    @else
        <div class="input-area">
            <label for="exampleFormControlInput1" class="form-label">{{ $field['name'] }}</label>
            <input type="text" name="manual_data[{{ $field['name'] }}]"
                   @if($field['validation'] == 'required') required @endif class="form-control"
                   aria-label="{{ __('Amount') }}" id="amount" aria-describedby="basic-addon1">
        </div>
    @endif

@endforeach
