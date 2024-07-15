@extends('backend.setting.index')
@section('setting-title')
    {{ __('Platform API Settings') }}
@endsection
@section('title')
    {{ __('Platform API Settings') }}
@endsection
@section('setting-content')

    <div class="col-xl-8 col-lg-12 col-md-12 col-12">
        <div class="site-card">

            <div class="site-card-body">
                <form action="{{ route('admin.settings.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="section" value="platform_api">
                    <div class="site-input-groups row mb-0">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label">
                            {{ __('Platform API Setting') }}
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
                            <div class="form-row row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="site-input-groups">
                                        <label for=""
                                               class="box-input-label col-label">{{ __('MT5 API URL(Real)') }}</label>
                                        <input
                                            type="text"
                                            class="box-input"
                                            name="mt5_api_url_real"
                                            value="{{ setting('mt5_api_url_real','platform_api') }}"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="site-input-groups">
                                        <label for=""
                                               class="box-input-label col-label">{{ __('MT5 API Key(Real)') }}</label>
                                        <input
                                            type="text"
                                            class="box-input"
                                            name="mt5_api_key_real"
                                            value="{{ setting('mt5_api_key_real','platform_api') }}"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="site-input-groups">
                                        <label for=""
                                               class="box-input-label col-label">{{ __('MT5 API URL(Demo)') }}</label>
                                        <input
                                            type="text"
                                            class="box-input"
                                            name="mt5_api_url_demo"
                                            value="{{ setting('mt5_api_url_demo','platform_api') }}"

                                        />
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="site-input-groups">
                                        <label for=""
                                               class="box-input-label col-label">{{ __('MT5 API Key(Demo)') }}</label>
                                        <input
                                            type="text"
                                            class="box-input"
                                            name="mt5_api_key_demo"
                                            value="{{ setting('mt5_api_key_demo','platform_api') }}"

                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="offset-sm-3 col-sm-9 col-12">
                            <button type="submit" class="site-btn-sm primary-btn w-100">
                                {{ __(' Save Changes') }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>





@endsection
