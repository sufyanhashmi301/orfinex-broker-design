@extends('backend.layouts.app')
@section('title')
    {{ __('Add New Account Type') }}
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
                            <h2 class="title">{{ __('Add New Account Type') }}</h2>
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
                            <form action="{{route('admin.accountType.store')}}" method="post" enctype="multipart/form-data"
                                  class="row">
                                @csrf
                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <div class="site-input-groups">
                                                <label class="box-input-label" for="">{{ __('Upload Icon:') }}</label>
                                                <div class="wrap-custom-file">
                                                    <input
                                                        type="file"
                                                        name="icon"
                                                        id="schema-icon"
                                                        accept=".gif, .jpg, .png"
                                                        required
                                                    />
                                                    <label for="schema-icon">
                                                        <img
                                                            class="upload-icon"
                                                            src="{{asset('global/materials/upload.svg')}}"
                                                            alt=""
                                                        />
                                                        <span>{{ __('Upload Avatar') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 schema-name">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Title:') }}</label>
                                        <input
                                            type="text"
                                            name="title"
                                            class="box-input"
                                            placeholder="Forex Account Title"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-xl-6 schema-badge">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Account Type Badge:') }}</label>
                                        <input
                                            type="text"
                                            class="box-input"
                                            placeholder="Account Type Badge"
                                            name="badge"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-xl-6 ">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Leverage:') }}</label>
                                        <input
                                            type="text"
                                            name="leverage"
                                            class="box-input"
                                            placeholder="leverage e.g 10,20,50"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-xl-6 ">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('First Min Deposit:') }}</label>
                                        <input
                                            type="text"
                                            name="first_min_deposit"
                                            class="box-input"
                                            placeholder="Min deposit"

                                        />
                                    </div>
                                </div>
                                <div class="col-xl-6 ">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Demo (Swap):') }}</label>
                                        <input
                                            type="text"
                                            name="demo_swap_free"
                                            class="box-input"
                                            placeholder="Demo (Swap) Group"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-xl-6 ">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Demo (Islamic):') }}</label>
                                        <input
                                            type="text"
                                            name="demo_islamic"
                                            class="box-input"
                                            placeholder="Demo (Islamic) Group"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-xl-6 ">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Real (Swap):') }}</label>
                                        <input
                                            type="text"
                                            name="real_swap_free"
                                            class="box-input"
                                            placeholder="Real (Swap) Group"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-xl-6 ">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Real (Islamic):') }}</label>
                                        <input
                                            type="text"
                                            name="real_islamic"
                                            class="box-input"
                                            placeholder="Real (Islamic) Group"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-xl-6 ">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Priority:') }}</label>
                                        <input
                                            type="text"
                                            name="priority"
                                            oninput="this.value = validateDouble(this.value)"
                                            class="box-input"
                                            placeholder="Priority e.g 1,2,3.."
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Select countries where you want to show this forex scheme(select "All" if you have to show this scheme to whole world):') }}</label>
                                        <select id="choices-multiple-remove-button" name="country[]" placeholder="Manage Country" multiple>

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
                                <div class="col-xl-12">
                                    <div class="site-input-groups fw-normal">
                                        <label for="" class="box-input-label">{{ __('Detail:') }}</label>
                                        <div class="site-editor">
                                        <textarea class="summernote"
                                                  name="desc"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                            <div class="site-input-groups">
                                                <label class="box-input-label" for="">{{ __('Withdraw:') }}</label>
                                                <div class="switch-field same-type">
                                                    <input
                                                        type="radio"
                                                        id="radio-seven"
                                                        name="is_withdraw"
                                                        value="1"
                                                    />
                                                    <label for="radio-seven">{{ __('Yes') }}</label>
                                                    <input
                                                        type="radio"
                                                        id="radio-eight"
                                                        name="is_withdraw"
                                                        checked=""
                                                        value="0"
                                                    />
                                                    <label for="radio-eight">{{ __('No') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                            <div class="site-input-groups">
                                                <label class="box-input-label" for="">{{ __('IB Partner:') }}</label>
                                                <div class="switch-field same-type">
                                                    <input
                                                        type="radio"
                                                        id="ib-partner-yes"
                                                        name="is_ib_partner"
                                                        value="1"
                                                    />
                                                    <label for="ib-partner-yes">{{ __('Yes') }}</label>
                                                    <input
                                                        type="radio"
                                                        id="ib-partner-no"
                                                        name="is_ib_partner"
                                                        checked=""
                                                        value="0"
                                                    />
                                                    <label for="ib-partner-no">{{ __('No') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6 ">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Internal Transfer:') }}</label>
                                        <div class="switch-field same-type">
                                            <input
                                                type="radio"
                                                id="internal-transfer-yes"
                                                name="is_internal_transfer"
                                                value="1"
                                            />
                                            <label for="internal-transfer-yes">{{ __('Yes') }}</label>
                                            <input
                                                type="radio"
                                                id="internal-transfer-no"
                                                name="is_internal_transfer"
                                                checked=""
                                                value="0"
                                            />
                                            <label for="internal-transfer-no">{{ __('No') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 ">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('External Transfer:') }}</label>
                                        <div class="switch-field same-type">
                                            <input
                                                type="radio"
                                                id="external-transfer-yes"
                                                name="is_external_transfer"
                                                value="1"
                                            />
                                            <label for="external-transfer-yes">{{ __('Yes') }}</label>
                                            <input
                                                type="radio"
                                                id="external-transfer-no"
                                                name="is_external_transfer"
                                                checked=""
                                                value="0"
                                            />
                                            <label for="external-transfer-no">{{ __('No') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 ">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Bonus:') }}</label>
                                        <div class="switch-field same-type">
                                            <input
                                                type="radio"
                                                id="bonus-yes"
                                                name="is_bonus"
                                                value="1"
                                            />
                                            <label for="bonus-yes">{{ __('Yes') }}</label>
                                            <input
                                                type="radio"
                                                id="bonus-no"
                                                name="is_bonus"
                                                checked=""
                                                value="0"
                                            />
                                            <label for="bonus-no">{{ __('No') }}</label>
                                        </div>
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
