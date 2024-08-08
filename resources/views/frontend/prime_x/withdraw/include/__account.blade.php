<div class="input-area relative">
    <label for="exampleFormControlInput1" class="form-label">{{ __('Method Name:') }}</label>
    <input type="text" name="method_name" class="form-control !text-lg" placeholder="eg. Withdraw Method - USD"
               value="{{ $withdrawMethod->name .'-'. $withdrawMethod->currency}}">
</div>

@foreach( json_decode($withdrawMethod->fields, true) as $key => $field)

    @if($field['type'] == 'file')

        <input type="hidden" name="credentials[{{ $field['name']}}][type]" value="{{ $field['type'] }}">
        <input type="hidden" name="credentials[{{ $field['name']}}][validation]" value="{{ $field['validation'] }}">

        <div class="col-xl-6 col-md-12">
            <div class="body-title">{{ $field['name'] }}</div>
            <div class="wrap-custom-file">
                <input
                    type="file"
                    name="credentials[{{ $field['name'] }}][value]"
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
        </div>
    @elseif($field['type'] == 'textarea')
        <input type="hidden" name="credentials[{{ $field['name']}}][type]" value="{{ $field['type'] }}">
        <input type="hidden" name="credentials[{{ $field['name']}}][validation]" value="{{ $field['validation'] }}">

        <div class="input-area relative">
            <label for="exampleFormControlInput1" class="form-label">{{ $field['name'] }}</label>
            <textarea class="form-control" @if($field['validation'] == 'required') required
                          @endif placeholder="Send Money Note" name="credentials[{{$field['name']}}][value]"></textarea>
        </div>

    @else
        <input type="hidden" name="credentials[{{ $field['name']}}][type]" value="{{ $field['type'] }}">
        <input type="hidden" name="credentials[{{ $field['name']}}][validation]" value="{{ $field['validation'] }}">

        <div class="input-area relative">
            <label for="exampleFormControlInput1"
                   class="form-label">{{ ucwords( str_replace('_',' ',$field['name']) ) }}</label>
            <input type="text" name="credentials[{{ $field['name']}}][value]"
                   @if($field['validation'] == 'required') required @endif class="form-control !text-lg" aria-label="Amount"
                   id="amount" aria-describedby="basic-addon1">
        </div>
    @endif

@endforeach


