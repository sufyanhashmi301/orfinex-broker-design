@extends('backend.setting.index')
@section('setting-title')
    {{ __('Forex API Settings') }}
@endsection
@section('title')
    {{ __('Forex API Settings') }}
@endsection
@section('setting-content')

    <div class="col-xl-8 col-lg-12 col-md-12 col-12">
        <div class="site-card">

            <div class="site-card-body">
                <form action="{{ route('admin.settings.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="section" value="forex_api">
                    <div class="site-input-groups row mb-0">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label">
                            {{ __('Forex API Setting') }}
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
                            <div class="form-row row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="site-input-groups">
                                        <label for=""
                                               class="box-input-label col-label">{{ __('Forex API URL') }}</label>
                                        <input
                                            type="text"
                                            class="box-input"
                                            name="forex_api_url"
                                            value="{{ setting('forex_api_url','forex_api') }}"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="site-input-groups">
                                        <label for=""
                                               class="box-input-label col-label">{{ __('Forex API Key') }}</label>
                                        <input
                                            type="text"
                                            class="box-input"
                                            name="forex_api_key"
                                            value="{{ setting('forex_api_key','forex_api') }}"
                                            required
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


{{--mail connection test--}}

    <div
        class="modal fade"
        id="mailConnection"
        tabindex="-1"
        aria-labelledby="mailConnectionLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content site-table-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="mailConnectionLabel">
                        {{ __('SMTP Connection') }}
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.settings.mail.connection.test') }}" method="post">
                        @csrf
                        <div class="row">

                            <div class="col-xl-12">
                                <div class="site-input-groups">
                                    <label for="" class="box-input-label">{{ __('Your Email:') }}</label>
                                    <input
                                        type="email"
                                        name="email"
                                        class="box-input mb-0"
                                        required=""
                                    />
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <button type="submit" class="site-btn primary-btn w-100">
                                    {{ __('Check Now') }}
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


@endsection
