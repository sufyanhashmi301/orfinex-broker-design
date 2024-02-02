@extends('backend.layouts.app')
@section('title')
    {{ __('Add New Black List Country') }}
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
                            <h2 class="title">{{ __('Add New Black List Country') }}</h2>
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
                            <form action="{{route('admin.blackListCountry.store')}}" method="post" enctype="multipart/form-data"
                                  class="row">
                                @csrf
                                <div class="row">
                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="formGroup">
                                            <label class="block capitalize form-label">{{ __('Select Country*') }}</label>
                                            <div class="relative ">
                                                <select name="name" id="countrySelect" class="form-control py-2 h-[48px] w-full mt-2">
                                                    @foreach( getCountries() as $country)
                                                        <option  value="{{ $country['name'] }}"
                                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                            {{ $country['name']  }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <br>
                                <br>
                                <br>
                                <br>
                                <div class="row">
                                <div class="col-xl-12">
                                    <button type="submit" class="site-btn-sm primary-btn w-100 ">
                                        {{ __('Add New') }}
                                    </button>
                                </div>
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
