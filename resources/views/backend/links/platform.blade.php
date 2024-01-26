@extends('backend.links.index')
@section('links-title')
    {{ __('Platform Links') }}
@endsection
@section('title')
    {{ __('Platform Links') }}
@endsection
@section('links-content')
    <?php
    $section = 'platform_links';

    $fields = config('setting.platform_links');
    //   dd($fields);
    ?>
    <div class="col-xl-12 col-lg-12 col-md-12 col-12">
        <div class="site-card">
            <div class="site-card-body">
                @include('backend.setting.site_setting.include.form.__open_action')
                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                    <div class="site-card">
                        <div class="site-card-header">
                            <h3 class="title">{{ __($fields['title']) }}</h3>
                        </div>
                        <div class="site-card-body">

                            @foreach($fields['elements']  as $key => $field)
                                <div class="row">
                                    <div class="site-input-groups row">
                                @if($field['type'] == 'checkbox')
                                        <div class="col-sm-3">
                                            <div class="form-switch ps-0">
                                                <input class="form-check-input" type="hidden" value="0" name="{{$field['name']}}"/>
                                                <div class="switch-field same-type m-0">
                                                    <input
                                                        type="radio"
                                                        id="{{$field['name'], $key}}"
                                                        name="{{$field['name']}}"
                                                        value="1"
                                                        @if(oldSetting($field['name'],$section)) checked @endif
                                                    />
                                                    <label for="{{$field['name'], $key}}">{{ __('Enable') }}</label>
                                                    <input
                                                        type="radio"
                                                        id="disable-{{$field['name'], $key}}"
                                                        name="{{$field['name']}}"
                                                        value="0"
                                                        @if(!oldSetting($field['name'],$section)) checked @endif
                                                    />
                                                    <label for="disable-{{$field['name'], $key}}">{{ __('Disabled') }}</label>
                                                </div>

                                            </div>
                                        </div>
                                @elseif($field['type'] == 'textarea')

                                        <div class="col-sm-4 col-label">{{ __($field['label']) }}</div>
                                        <div class="col-sm-8">
                            <textarea name="{{ $field['name'] }}"
                                      class="form-textarea  @if($errors->has($field['name'])) has-error @endif">{{oldSetting($field['name'],$section)}}</textarea>
                                        </div>
                                @else
                                        <div class="col-sm-3 col-label">{{ __($field['label']) }}
                                            @if($field['name'] == 'secret_key')
                                                <i icon-name="info" data-bs-toggle="tooltip" title=""
                                                   data-bs-original-title="Remember the Secret Key. Use domain/secret-key to trun back the website live"></i>
                                            @endif
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="{{$field['type']}}" name="{{ $field['name'] }}"
                                                   class="box-input @if($errors->has($field['name'])) has-error @endif"
                                                   placeholder="URL" value="{{oldSetting($field['name'],$section)}}" >
                                        </div>
                                @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{--                     <div class="site-input-groups row align-items-center">--}}
                {{--                        <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label pt-0">--}}
                {{--                            AML Policy--}}
                {{--                        </label>--}}
                {{--                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">--}}
                {{--                            <input type="url" name="cash_out_redeem" class="box-input" placeholder="">--}}
                {{--                        </div>--}}
                {{--                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">--}}
                {{--                            <div class="form-switch ps-0">--}}
                {{--                                <input class="form-check-input" type="hidden" value="0" name="aml_policy">--}}
                {{--                                <div class="switch-field same-type m-0">--}}
                {{--                                    <input type="radio" id="active-1" name="aml_policy" value="1">--}}
                {{--                                    <label for="active-1">Enable</label>--}}
                {{--                                    <input type="radio" id="disable-1" name="aml_policy" value="0" checked="">--}}
                {{--                                    <label for="disable-1">Disabled</label>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                    <div class="site-input-groups row align-items-center">--}}
                {{--                        <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label pt-0">--}}
                {{--                            Client Agreement--}}
                {{--                        </label>--}}
                {{--                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">--}}
                {{--                            <input type="url" name="cash_out_redeem" class="box-input" placeholder="">--}}
                {{--                        </div>--}}
                {{--                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">--}}
                {{--                            <div class="form-switch ps-0">--}}
                {{--                                <input class="form-check-input" type="hidden" value="0" name="client_agreement">--}}
                {{--                                <div class="switch-field same-type m-0">--}}
                {{--                                    <input type="radio" id="active-2" name="client_agreement" value="1">--}}
                {{--                                    <label for="active-2">Enable</label>--}}
                {{--                                    <input type="radio" id="disable-2" name="client_agreement" value="0" checked="">--}}
                {{--                                    <label for="disable-2">Disabled</label>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                    <div class="site-input-groups row align-items-center">--}}
                {{--                        <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label pt-0">--}}
                {{--                            Complaints Handling Policy--}}
                {{--                        </label>--}}
                {{--                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">--}}
                {{--                            <input type="url" name="cash_out_redeem" class="box-input" placeholder="">--}}
                {{--                        </div>--}}
                {{--                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">--}}
                {{--                            <div class="form-switch ps-0">--}}
                {{--                                <input class="form-check-input" type="hidden" value="0" name="complaints_handling_policy">--}}
                {{--                                <div class="switch-field same-type m-0">--}}
                {{--                                    <input type="radio" id="active-3" name="complaints_handling_policy" value="1">--}}
                {{--                                    <label for="active-3">Enable</label>--}}
                {{--                                    <input type="radio" id="disable-3" name="complaints_handling_policy" value="0" checked="">--}}
                {{--                                    <label for="disable-3">Disabled</label>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                    <div class="site-input-groups row align-items-center">--}}
                {{--                        <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label pt-0">--}}
                {{--                            Cookies Policy--}}
                {{--                        </label>--}}
                {{--                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">--}}
                {{--                            <input type="url" name="cash_out_redeem" class="box-input" placeholder="">--}}
                {{--                        </div>--}}
                {{--                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">--}}
                {{--                            <div class="form-switch ps-0">--}}
                {{--                                <input class="form-check-input" type="hidden" value="0" name="cookies_policy">--}}
                {{--                                <div class="switch-field same-type m-0">--}}
                {{--                                    <input type="radio" id="active-4" name="cookies_policy" value="1">--}}
                {{--                                    <label for="active-4">Enable</label>--}}
                {{--                                    <input type="radio" id="disable-4" name="cookies_policy" value="0" checked="">--}}
                {{--                                    <label for="disable-4">Disabled</label>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                    <div class="site-input-groups row align-items-center">--}}
                {{--                        <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label pt-0">--}}
                {{--                            IB Partner Agreement--}}
                {{--                        </label>--}}
                {{--                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">--}}
                {{--                            <input type="url" name="cash_out_redeem" class="box-input" placeholder="">--}}
                {{--                        </div>--}}
                {{--                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">--}}
                {{--                            <div class="form-switch ps-0">--}}
                {{--                                <input class="form-check-input" type="hidden" value="0" name="ib_partner_agreement">--}}
                {{--                                <div class="switch-field same-type m-0">--}}
                {{--                                    <input type="radio" id="active-5" name="ib_partner_agreement" value="1">--}}
                {{--                                    <label for="active-5">Enable</label>--}}
                {{--                                    <input type="radio" id="disable-5" name="ib_partner_agreement" value="0" checked="">--}}
                {{--                                    <label for="disable-5">Disabled</label>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                    <div class="site-input-groups row align-items-center">--}}
                {{--                        <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label pt-0">--}}
                {{--                            Order Execution Policy--}}
                {{--                        </label>--}}
                {{--                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">--}}
                {{--                            <input type="url" name="cash_out_redeem" class="box-input" placeholder="">--}}
                {{--                        </div>--}}
                {{--                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">--}}
                {{--                            <div class="form-switch ps-0">--}}
                {{--                                <input class="form-check-input" type="hidden" value="0" name="order_execution_policy">--}}
                {{--                                <div class="switch-field same-type m-0">--}}
                {{--                                    <input type="radio" id="active-6" name="order_execution_policy" value="1">--}}
                {{--                                    <label for="active-6">Enable</label>--}}
                {{--                                    <input type="radio" id="disable-6" name="order_execution_policy" value="0" checked="">--}}
                {{--                                    <label for="disable-6">Disabled</label>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                    <div class="site-input-groups row align-items-center">--}}
                {{--                        <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label pt-0">--}}
                {{--                            Privacy Policy--}}
                {{--                        </label>--}}
                {{--                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">--}}
                {{--                            <input type="url" name="cash_out_redeem" class="box-input" placeholder="">--}}
                {{--                        </div>--}}
                {{--                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">--}}
                {{--                            <div class="form-switch ps-0">--}}
                {{--                                <input class="form-check-input" type="hidden" value="0" name="privacy_policy">--}}
                {{--                                <div class="switch-field same-type m-0">--}}
                {{--                                    <input type="radio" id="active-7" name="privacy_policy" value="1">--}}
                {{--                                    <label for="active-7">Enable</label>--}}
                {{--                                    <input type="radio" id="disable-7" name="privacy_policy" value="0" checked="">--}}
                {{--                                    <label for="disable-7">Disabled</label>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                    <div class="site-input-groups row align-items-center">--}}
                {{--                        <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label pt-0">--}}
                {{--                            Risk Disclosure--}}
                {{--                        </label>--}}
                {{--                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">--}}
                {{--                            <input type="url" name="cash_out_redeem" class="box-input" placeholder="">--}}
                {{--                        </div>--}}
                {{--                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">--}}
                {{--                            <div class="form-switch ps-0">--}}
                {{--                                <input class="form-check-input" type="hidden" value="0" name="risk_disclosure">--}}
                {{--                                <div class="switch-field same-type m-0">--}}
                {{--                                    <input type="radio" id="active-8" name="risk_disclosure" value="1">--}}
                {{--                                    <label for="active-8">Enable</label>--}}
                {{--                                    <input type="radio" id="disable-8" name="risk_disclosure" value="0" checked="">--}}
                {{--                                    <label for="disable-8">Disabled</label>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                    <div class="site-input-groups row align-items-center">--}}
                {{--                        <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label pt-0">--}}
                {{--                            US Clients Policy--}}
                {{--                        </label>--}}
                {{--                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">--}}
                {{--                            <input type="url" name="cash_out_redeem" class="box-input" placeholder="">--}}
                {{--                        </div>--}}
                {{--                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">--}}
                {{--                            <div class="form-switch ps-0">--}}
                {{--                                <input class="form-check-input" type="hidden" value="0" name="us_clients_policy">--}}
                {{--                                <div class="switch-field same-type m-0">--}}
                {{--                                    <input type="radio" id="active-9" name="us_clients_policy" value="1">--}}
                {{--                                    <label for="active-9">Enable</label>--}}
                {{--                                    <input type="radio" id="disable-9" name="us_clients_policy" value="0" checked="">--}}
                {{--                                    <label for="disable-9">Disabled</label>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                @include('backend.setting.site_setting.include.form.__close_action')

            </div>
        </div>
    </div>
@endsection
