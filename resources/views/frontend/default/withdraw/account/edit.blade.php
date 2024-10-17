@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Account Edit') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary font-Inter ">
                {{ __('Withdraw Accounts') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Edit Withdraw Account') }}
            </li>
        </ul>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Edit Withdraw Account') }}</h4>
                    <div>
                        <a href="{{ route('user.withdraw.account.index') }}" class="btn btn-dark">
                            {{ __('Withdraw Accounts') }}
                        </a>
                    </div>
                </div>
                <div class="card-body p-6">
                    <div class="progress-steps-form">
                        <form action="{{ route('user.withdraw.account.update',$withdrawAccount->id) }}" method="post"
                              enctype="multipart/form-data">
                            @method('PUT')
                            @csrf

                            <input type="hidden" name="withdraw_method_id" value="{{$withdrawAccount->withdraw_method_id}}">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5 selectMethodRow">
                                <div class="input-area relative">
                                    <label for="exampleFormControlInput1" class="form-label">{{ __('Method Name:') }}</label>
                                    <input type="text" name="method_name" class="form-control !text-lg"
                                           placeholder="eg. Withdraw Method - USD"
                                           value="{{ $withdrawAccount->method_name }}">
                                </div>


                                @foreach( json_decode($withdrawAccount->credentials, true) as $key => $field)

                                    @if($field['type'] == 'file')

                                        <input type="hidden" name="credentials[{{ $key }}][type]"
                                               value="{{ $field['type'] }}">
                                        <input type="hidden" name="credentials[{{ $key }}][validation]"
                                               value="{{ $field['validation'] }}">

                                        <div class="col-xl-6 col-md-12">
                                            <div class="body-title">{{ $key }}</div>
                                            <div class="wrap-custom-file">
                                                <input
                                                    type="file"
                                                    name="credentials[{{ $key}}][value]"
                                                    id="{{ $key }}"
                                                    accept=".gif, .jpg, .png"
                                                    @if($field['value'] == "" && $field['validation'] == 'required') required @endif
                                                />
                                                <label for="{{ $key }}" class="file-ok"
                                                       style="background-image: url({{ asset( $field['value'] ) }})">
                                                    <img
                                                        class="upload-icon"
                                                        src="{{ asset('global/materials/upload.svg') }}"
                                                        alt=""
                                                    />
                                                    <span>{{ __('Update Icon') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    @elseif($field['type'] == 'textarea')
                                        <input type="hidden" name="credentials[{{ $key}}][type]"
                                               value="{{ $field['type'] }}">
                                        <input type="hidden" name="credentials[{{ $key}}][validation]"
                                               value="{{ $field['validation'] }}">

                                        <div class="input-area relative col-span-12">
                                            <label for="exampleFormControlInput1" class="form-label">{{ $key }}</label>
                                            <textarea class="form-control !text-lg" rows="5"
                                                          @if($field['validation'] == 'required') required
                                                          @endif placeholder="Send Money Note"
                                                          name="credentials[{{$key}}][value]">{{$field['value']}}</textarea>
                                        </div>

                                    @else
                                        <input type="hidden" name="credentials[{{ $key}}][type]"
                                               value="{{ $field['type'] }}">
                                        <input type="hidden" name="credentials[{{ $key}}][validation]"
                                               value="{{ $field['validation'] }}">

                                        <div class="input-area relative">
                                            <label for="exampleFormControlInput1" class="form-label">{{ $key }}</label>
                                            <input type="text" name="credentials[{{ $key}}][value]"
                                                       value="{{ $field['value'] }}"
                                                       @if($field['validation'] == 'required') required
                                                       @endif class="form-control !text-lg" aria-label="Amount" id="amount"
                                                       aria-describedby="basic-addon1">
                                        </div>
                                    @endif

                                @endforeach


                            </div>
                            <div class="buttons text-right mt-4">
                                <button type="submit" class="btn inline-flex justify-center btn-dark">
                                    {{ __('Update Withdraw Account') }}<i class="anticon anticon-double-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#selectMethod").on('change',function (e) {
            "use strict"
            e.preventDefault();

            //$('.manual-row').empty();
            $('.selectMethodRow').children().not(':first').remove();

            var id = $(this).val()

            var url = '{{ route("user.withdraw.method",":id") }}';
            url = url.replace(':id', id);
            $.get(url, function (data) {
                $(data).insertAfter(".selectMethodCol");
                imagePreview()
            })
        })
    </script>
@endsection
