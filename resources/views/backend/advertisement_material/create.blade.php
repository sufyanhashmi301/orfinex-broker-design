@extends('backend.layouts.app')
@section('title')
    {{ __('Add New Advertisement Material') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('backend/css/choices.min.css') }}" >
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Add New Advertisement Material') }}</h4>
                <a href="{{ url()->previous() }}" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
            <div class="card-body p-6">
                <form action="{{route('admin.advertisement_material.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-2">
                            <div class="max-w-xs">
                                <div class="site-input-groups">
                                    <label class="form-label" for="">{{ __('Upload Image:') }}</label>
                                    <div class="wrap-custom-file">
                                        <input
                                            type="file"
                                            name="img"
                                            id="advertisement_material-icon"
                                            accept=".gif, .jpg, .png"
                                            required
                                        />
                                        <label for="advertisement_material-icon">
                                            <img
                                                class="upload-icon"
                                                src="{{asset('global/materials/upload.svg')}}"
                                                alt=""
                                            />
                                            <span>{{ __('Upload Image') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Select Language') }}</label>
                                <select  name="language" class="form-control w-100" placeholder="Manage Country" multiple>
                                    @foreach($languages as $language)
                                        <option value="{{$language->name}}">{{$language->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Select Type') }}</label>
                                <select  name="type" class="form-control w-100" placeholder="Manage Type" multiple>

                                    {{--@foreach( getCountries() as $country)--}}
                                    {{--<option  value="{{ $country['name'] }}">--}}
                                    {{--{{ $country['name']  }}--}}
                                    {{--</option>--}}
                                    {{--@endforeach--}}
                                    <option  value="social_media" >
                                        {{ __('Social Media') }}
                                    </option>
                                    <option  value="website_banner" >
                                        {{ __('Website Banner') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div class="input-area max-w-xs">
                                <label class="form-label" for="">{{ __('Status:') }}</label>
                                <div class="switch-field flex mb-3 overflow-hidden same-type">
                                    <input
                                        type="radio"
                                        id="status-active"
                                        name="status"
                                        checked=""
                                        value="1"
                                    />
                                    <label for="status-active">{{ __('Active') }}</label>
                                    <input
                                        type="radio"
                                        id="status-deactive"
                                        name="status"
                                        value="0"
                                    />
                                    <label for="status-deactive">{{ __('Deactivate') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2 text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Add New') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('backend/js/choices.min.js') }}"></script>
    <script>

        (function ($) {
            'use strict';

        var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
            removeItemButton: true,
            // maxItemCount:7,
            // searchResultLimit:7,
            // renderChoiceLimit:20
        });

        })(jQuery)
    </script>
@endsection
