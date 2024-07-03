@extends('backend.theme.index')
@section('theme-title')
    {{ __('Page Settings') }}
@endsection
@section('theme-content')
    <div class="lg:col-span-6 col-span-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Basic Settings') }}</h4>
            </div>
            <div class="card-body p-6">
                <form action="{{ route('admin.page.setting.update') }}" method="post"
                    enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div class="input-area grid grid-cols-12 gap-5">
                        <div class="md:col-span-3 col-span-12 form-label">
                            {{ __('Page Breadcrumb') }}<i icon-name="info" data-bs-toggle="tooltip" title=""
                                                        data-bs-original-title="All the pages Breadcrumb Background Image"></i>
                        </div>
                        <div class="md:col-span-9 col-span-12">
                            <div class="wrap-custom-file">
                                <input type="file" name="breadcrumb" id="breadcrumbBg"
                                    accept=".gif, .jpg, .png"/>
                                <label for="breadcrumbBg" class="file-ok"
                                    style="background-image: url({{ asset(getPageSetting('breadcrumb')) }})">
                                    <img class="upload-icon"
                                        src="{{ asset('global/materials/upload.svg') }}" alt=""/>
                                    <span>{{ __('Update Background') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="lg:col-span-6 col-span-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Register Field Settings') }}</h4>
            </div>
            <div class="card-body p-6">
                <form action="{{ route('admin.page.setting.update') }}" method="post"
                    enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div class="input-area grid grid-cols-12 gap-5">
                        <div class="md:col-span-3 col-span-12 form-label">{{ __('Manage Fields for Registration') }}</div>
                        <div class="md:col-span-9 col-span-12 space-y-5">
                            <div class="form-row grid grid-cols-12 gap-5">
                                <div class="md:col-span-6 col-span-12">
                                    <div class="input-area">
                                        <label class="form-label" for="">{{ __('Username:') }}</label>
                                        <div class="switch-field flex overflow-hidden">
                                            <input
                                                type="radio"
                                                id="username-show"
                                                name="username_show"
                                                @checked( getPageSetting('username_show'))
                                                value="1"
                                            />
                                            <label for="username-show">{{ __('Show') }}</label>
                                            <input
                                                type="radio"
                                                id="username-hide"
                                                name="username_show"
                                                @checked(!getPageSetting('username_show'))
                                                value="0"
                                            />
                                            <label for="username-hide">{{ __('Hide') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="md:col-span-6 col-span-12">
                                    <div class="input-area">
                                        <label class="form-label"
                                            for="">{{ __('Phone Number:') }}</label>
                                        <div class="switch-field flex overflow-hidden">
                                            <input
                                                type="radio"
                                                id="phone-show"
                                                name="phone_show"
                                                @checked(getPageSetting('phone_show'))
                                                value="1"
                                            />
                                            <label for="phone-show">{{ __('Show') }}</label>
                                            <input
                                                type="radio"
                                                id="phone-hide"
                                                name="phone_show"
                                                @checked(!getPageSetting('phone_show'))
                                                value="0"
                                            />
                                            <label for="phone-hide">{{ __('Hide') }}</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-row grid grid-cols-12 gap-5">
                                <div class="md:col-span-6 col-span-12">
                                    <div class="input-area">
                                        <label class="form-label" for="">{{ __('Country:') }}</label>
                                        <div class="switch-field flex overflow-hidden">
                                            <input
                                                type="radio"
                                                id="country-show"
                                                name="country_show"
                                                @checked( getPageSetting('country_show'))
                                                value="1"
                                            />
                                            <label for="country-show">{{ __('Show') }}</label>
                                            <input
                                                type="radio"
                                                id="country-hide"
                                                name="country_show"
                                                @checked( !getPageSetting('country_show'))
                                                value="0"
                                            />
                                            <label for="country-hide">{{ __('Hide') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="md:col-span-6 col-span-12">
                                    <div class="input-area">
                                        <label class="form-label"
                                            for="">{{ __('Referral Code:') }}</label>
                                        <div class="switch-field flex overflow-hidden">
                                            <input
                                                type="radio"
                                                id="referral-code-show"
                                                name="referral_code_show"
                                                @checked( getPageSetting('referral_code_show'))
                                                value="1"
                                            />
                                            <label for="referral-code-show">{{ __('Show') }}</label>
                                            <input
                                                type="radio"
                                                id="referral-code-hide"
                                                name="referral_code_show"
                                                @checked( !getPageSetting('referral_code_show'))
                                                value="0"
                                            />
                                            <label for="referral-code-hide">{{ __('Hide') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
