@extends('backend.layouts.app')
@section('title')
    {{ __('Add New IB') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="title-content">
                            <h2 class="title">{{ __('Edit IB Form') }}</h2>
                            <a href="{{ route('admin.ib-form.index') }}" class="title-btn"><i
                                    icon-name="corner-down-left"></i>{{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-12 col-md-12 col-12">
                    <div class="site-card">
                        <div class="site-card-body">
                            <form action="{{ route('admin.ib-form.update',$kyc->id) }}" method="post" class="row">
                                @method('PUT')
                                @csrf

                                <div class="col-xl-12">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Name:') }}</label>
                                        <input type="text" name="name" value="{{ old('name',$kyc->name) }}"
                                               class="box-input" placeholder="IB Type Name" required/>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <a href="javascript:void(0)" id="generate"
                                       class="site-btn-xs primary-btn mb-3">{{ __('Add Field option') }}</a>
                                </div>

                                <div class="addOptions">

                                    @foreach(json_decode($kyc->fields) as $field)
{{--                                        {{dd($field->name)}}--}}
                                        <div class="input-area">
                                            <div class="grid grid-cols-12">
                                                <div class="col-span-12">
                                                    <label class="form-label text-lg font-medium">{{ $field->name }}</label>
                                                </div>
                                                @if($field->type === 'text')
                                                    <div class="md:col-span-6 col-span-12">
                                                        <input name="fields[{{ $kyc->name }}]"
                                                               class="form-control !text-lg" type="text" value="">
                                                    </div>
                                                @elseif($field->type === 'checkbox')
                                                    <div class="col-span-12">
                                                        @foreach($field->options as $index=>$option)
                                                            <div class="checkbox-area mb-2">
                                                                <label for="flexCheckDefault{{$index}}"
                                                                       class="inline-flex items-center cursor-pointer">
                                                                    <input class="hidden" type="checkbox"
                                                                           name="fields[{{ $kyc->name }}][]"
                                                                           value="{{ $option }}" id="flexCheckDefault{{$index}}"
                                                                           required/>
                                                                    <span
                                                                        class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                                <img
                                                                    src="{{ asset('frontend/images/icon/ck-white.svg') }}"
                                                                    alt=""
                                                                    class="h-[10px] w-[10px] block m-auto opacity-0">
                                                            </span>
                                                                    <span
                                                                        class="text-slate-500 dark:text-slate-400 text-sm leading-6">
                                                                {{ $option }}
                                                            </span>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($field->type === 'radio')
                                                    <div class="col-span-12">
                                                        @foreach($field->options as $option)
                                                            <div class="basicRadio mb-2">
                                                                <label class="flex items-center cursor-pointer">
                                                                    <input type="radio" class="hidden"
                                                                           name="fields[{{ $kyc->name }}]"
                                                                           value="{{ $option }}">
                                                                    <span
                                                                        class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                                    <span
                                                                        class="text-slate-500 dark:text-slate-400 text-sm leading-6 capitalize">
                                                                {{ $option }}
                                                            </span>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($field->type === 'dropdown')
                                                    <div class="md:col-span-6 col-span-12 select2-lg">
                                                        <select name="fields[{{ $kyc->name }}]"
                                                                class="select2 form-control w-full mt-2 py-2">
                                                            @foreach($field->options as $option)
                                                                <option value="{{ $option }}"
                                                                        class="inline-block font-Inter font-normal text-sm text-slate-600"
                                                                ">
                                                                {{ $option }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
{{--                                    @foreach(json_decode($kyc->fields,true) as $key => $value)--}}
{{--                                        <div class="mb-4">--}}
{{--                                            <div class="option-remove-row row">--}}
{{--                                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">--}}
{{--                                                    <div class="site-input-groups">--}}
{{--                                                        <input name="fields[{{$key}}][name]" class="box-input"--}}
{{--                                                               type="text" value="{{$value['name']}}" required--}}
{{--                                                               placeholder="Field Name">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">--}}
{{--                                                    <div class="site-input-groups">--}}
{{--                                                        <select name="fields[{{$key}}][type]"--}}
{{--                                                                class="form-select form-select-lg mb-3">--}}
{{--                                                            <option value="text"--}}
{{--                                                                    @if($value['type'] == 'text') selected @endif>{{ __('Input Text') }}</option>--}}
{{--                                                            <option value="textarea"--}}
{{--                                                                    @if($value['type'] == 'textarea') selected @endif>{{ __('Textarea') }}</option>--}}
{{--                                                            <option value="file"--}}
{{--                                                                    @if($value['type'] == 'file') selected @endif>{{ __('File upload') }}</option>--}}
{{--                                                        </select>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">--}}
{{--                                                    <div class="site-input-groups mb-0">--}}
{{--                                                        <select name="fields[{{ $key }}][validation]"--}}
{{--                                                                class="form-select form-select-lg mb-1">--}}
{{--                                                            <option value="required"--}}
{{--                                                                    @if($value['validation'] == 'required') selected @endif>{{ __('Required') }}</option>--}}
{{--                                                            <option value="nullable"--}}
{{--                                                                    @if($value['validation'] == 'nullable') selected @endif>{{ __('Optional') }}</option>--}}
{{--                                                        </select>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-xl-1 col-lg-6 col-md-6 col-sm-6 col-12">--}}
{{--                                                    <button class="delete-option-row delete_desc" type="button">--}}
{{--                                                        <i class="fas fa-times"></i>--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    @endforeach--}}
                                </div>

                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                                            <div class="site-input-groups">
                                                <label class="box-input-label" for="">{{ __('Status:') }}</label>
                                                <div class="switch-field">
                                                    <input
                                                        type="radio"
                                                        id="active-status"
                                                        name="status"
                                                        @if($kyc->status) checked @endif
                                                        value="1"
                                                    />
                                                    <label for="active-status">{{ __('Active') }}</label>
                                                    <input
                                                        type="radio"
                                                        id="deactivate-status"
                                                        name="status"
                                                        @if(!$kyc->status) checked @endif
                                                        value="0"
                                                    />
                                                    <label for="deactivate-status">{{ __('Deactivate') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <button type="submit" class="site-btn primary-btn w-100">
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function (e) {
            "use strict";
            var i = Object.keys(JSON.parse(@json($kyc->fields))).length;

            $("#generate").on('click', function () {
                ++i;
                var form = `<div class="mb-4">
                  <div class="option-remove-row row">
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                      <div class="site-input-groups">
                        <input name="fields[` + i + `][name]" class="box-input" type="text" value="" required placeholder="Field Name">
                      </div>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                      <div class="site-input-groups">
                        <select name="fields[` + i + `][type]" class="form-select form-select-lg mb-3">
                            <option value="text">Input Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="file">File upload</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                      <div class="site-input-groups mb-0">
                        <select name="fields[` + i + `][validation]" class="form-select form-select-lg mb-1">
                            <option value="required">Required</option>
                            <option value="nullable">Optional</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-xl-1 col-lg-6 col-md-6 col-sm-6 col-12">
                      <button class="delete-option-row delete_desc" type="button">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                    </div>
                  </div>`;
                $('.addOptions').append(form)
            });

            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.option-remove-row').parent().remove();
            });
        });
    </script>
@endsection
