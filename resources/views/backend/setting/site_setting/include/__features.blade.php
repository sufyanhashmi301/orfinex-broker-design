<div class="col-xl-6 col-lg-12 col-md-12 col-12">
    <div class="site-card">
        <div class="site-card-header">
            <h3 class="title">{{ __('Features Settings') }}</h3>
        </div>
        <div class="site-card-body">
            @include('backend.setting.site_setting.include.form.__open_action')

            @foreach($fields['elements'] as $key => $field)
                <div class="site-input-groups row">
                    <div class="col-sm-4 col-label pt-0">{{ __($field['label']) }}</div>
{{--{{dd($field['name'])}}--}}
                    @if($field['name'] == 'withdraw_deduction')
                         <div class="col-sm-8">
                        <div class="form-switch ps-0">
                            <input class="form-check-input" type="hidden" value="0" name="{{$field['name']}}"/>
                            <div class="switch-field same-type m-0">
                                <input
                                    type="radio"
                                    id="withdraw-active-{{$key}}"
                                    name="{{$field['name']}}"
                                    value="1"
                                    @if(oldSetting($field['name'],$section)) checked @endif
                                />
                                <label for="withdraw-active-{{$key}}">{{ __('On Request') }}</label>
                                <input
                                    type="radio"
                                    id="withdraw-disable-{{$key}}"
                                    name="{{$field['name']}}"
                                    value="0"
                                    @if(!oldSetting($field['name'],$section)) checked @endif
                                />
                                <label for="withdraw-disable-{{$key}}">{{ __('On Approval') }}</label>
                            </div>
                        </div>
                    </div>
                    @elseif($field['name'] == 'copy_trading')
                         <div class="col-sm-8">
                        <div class="form-switch ps-0">
                            <input class="form-check-input" type="hidden" value="0" name="{{$field['name']}}"/>
                            <div class="switch-field same-type m-0">
                                <input
                                    type="radio"
                                    id="copy-trading-active-{{$key}}"
                                    name="{{$field['name']}}"
                                    value="1"
                                    @if(oldSetting($field['name'],$section)) checked @endif
                                />
                                <label for="copy-trading-active-{{$key}}">{{ __('Show') }}</label>
                                <input
                                    type="radio"
                                    id="copy-trading-disable-{{$key}}"
                                    name="{{$field['name']}}"
                                    value="0"
                                    @if(!oldSetting($field['name'],$section)) checked @endif
                                />
                                <label for="copy-trading-disable-{{$key}}">{{ __('Hide') }}</label>
                            </div>
                        </div>
                    </div>
                    @else
                         <div class="col-sm-8">
                            <div class="form-switch ps-0">
                                <input class="form-check-input" type="hidden" value="0" name="{{$field['name']}}"/>
                                <div class="switch-field same-type m-0">
                                    <input
                                        type="radio"
                                        id="features-active-{{$key}}"
                                        name="{{$field['name']}}"
                                        value="1"
                                        @if(oldSetting($field['name'],$section)) checked @endif
                                    />
                                    <label for="features-active-{{$key}}">{{ __('Enable') }}</label>
                                    <input
                                        type="radio"
                                        id="features-deduction-{{$key}}"
                                        name="{{$field['name']}}"
                                        value="0"
                                        @if(!oldSetting($field['name'],$section)) checked @endif
                                    />
                                    <label for="features-disable-{{$key}}">{{ __('Disabled') }}</label>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            @endforeach
            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>
</div>
