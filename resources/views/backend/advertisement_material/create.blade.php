@extends('backend.layouts.app')
@section('title')
    {{ __('Add New Advertisement Material') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('backend/css/choices.min.css') }}" >
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="title-content">
                            <h2 class="title">{{ __('Add New Advertisement Material') }}</h2>
                            <a href="{{ url()->previous() }}" class="title-btn"><i
                                    icon-name="corner-down-left"></i>{{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="site-card">
                        <div class="site-card-body">
                            <form action="{{route('admin.advertisement_material.store')}}" method="post" enctype="multipart/form-data"
                                  class="row">
                                @csrf
                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <div class="site-input-groups">
                                                <label class="box-input-label" for="">{{ __('Upload Image:') }}</label>
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
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Select Language') }}</label>
                                        <select  name="language" class="site-nice-select w-100" placeholder="Manage Country" multiple>

                                            @foreach( getCountries() as $country)
                                                <option  value="{{ $country['name'] }}">
                                                    {{ $country['name']  }}
                                                </option>
                                            @endforeach
                                                <option  value="All" >
                                                    {{ __('All') }}
                                                </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Select Type') }}</label>
                                        <select  name="type" class="site-nice-select w-100" placeholder="Manage Type" multiple>

{{--                                            @foreach( getCountries() as $country)--}}
{{--                                                <option  value="{{ $country['name'] }}">--}}
{{--                                                    {{ $country['name']  }}--}}
{{--                                                </option>--}}
{{--                                            @endforeach--}}
                                                <option  value="Social_media" >
                                                    {{ __('Social Media') }}
                                                </option>
                                            <option  value="website_banner" >
                                                {{ __('Website Banner') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Status:') }}</label>
                                        <div class="switch-field same-type">
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
                                <div class="col-xl-12">
                                    <button type="submit" class="site-btn-sm primary-btn w-100">
                                        {{ __('Add New') }}
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
