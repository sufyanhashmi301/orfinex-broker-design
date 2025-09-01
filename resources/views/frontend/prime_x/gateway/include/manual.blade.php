<div class="col-span-12">
    <div class="frontend-editor-data space-y-4 text-lg mb-3">
        <h6 class="text-slate-900 dark:text-white">
            {{ __('Account Details:') }}
        </h6>
        <div class="dark:text-slate-200">
            {!! $paymentDetails !!}
        </div>
    </div>
</div>
@foreach(json_decode($fieldOptions, true) as $key => $field)

    @if($field['type'] == 'file')
        <div class="col-span-12">
            <label class="form-label">{{ __('' . $field['name']) }}</label>
            <div class="wrap-custom-file dark:border-slate-700">
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
                    <span class="dark:text-slate-200">{{ __('Select ') . $field['name'] }}</span>
                </label>
            </div>
        </div>
    @elseif($field['type'] == 'textarea')
        <div class="input-area">
            <textarea class="form-control" rows="5" @if($field['validation'] == 'required') required @endif placeholder="{{ __('Send Money Note') }}" name="manual_data[{{ $field['name'] }}]"></textarea>
        </div>
    @else
        <div class="input-area">
            <label for="{{ str_replace(' ', '_', $field['name']) }}" class="form-label">
                {{ $field['name'] }}
            </label>
            <input type="text" name="manual_data[{{ $field['name'] }}]"
                @if($field['validation'] == 'required') required @endif class="form-control !text-lg"
                aria-label="{{ str_replace(' ', '_', $field['name']) }}"
                id="{{ str_replace(' ', '_', $field['name']) }}"
                aria-describedby="basic-addon1">
        </div>
    @endif

@endforeach
