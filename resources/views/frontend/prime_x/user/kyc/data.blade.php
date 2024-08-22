<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
@foreach( json_decode($fields, true) as $key => $field)

    @if($field['type'] == 'file')
        <div class="input-area">
            <label for="" class="form-label">{{ $field['name'] }}</label>
            <div class="wrap-custom-file">
                <input
                    type="file"
                    name="kyc_credential[{{$field['name']}}]"
                    id="{{ $key }}"
                    accept=".gif, .jpg, .png"
                    @if($field['validation'] == 'required') required @endif
                />
                <label for="{{ $key }}">
                    <img
                        class="upload-icon"
                        src="{{ asset('global/materials/upload.svg') }}"
                        alt=""
                    />
                    <span>{{ __('Select '). $field['name'] }}</span>
                </label>
            </div>
            <p class="text-xs dark:text-white">
                Provide files in <span class="font-medium">JPG</span> format, <span class="font-medium">10 MB</span> maximum
            </p>
        </div>
    @elseif($field['type'] == 'textarea')

        <div class="input-area col-span-2">
            <div class="progress-steps-form">
                <label for="exampleFormControlInput1" class="form-label">{{ $field['name'] }}</label>
                <div class="input-group">
                    <textarea class="form-control" @if($field['validation'] == 'required') required
                              @endif placeholder="Send Money Note" name="kyc_credential[{{$field['name']}}]"></textarea>
                </div>
            </div>
        </div>

    @else
        <div class="input-area col-span-2">
            <div class="progress-steps-form">
                <label for="exampleFormControlInput1" class="form-label">{{ $field['name'] }}</label>
                <input type="text" class="form-control" name="kyc_credential[{{$field['name']}}]"
                           @if($field['validation'] == 'required') required @endif class="form-control"
                           aria-label="Amount" id="amount" aria-describedby="basic-addon1">
            </div>
        </div>
    @endif

@endforeach
</div>
