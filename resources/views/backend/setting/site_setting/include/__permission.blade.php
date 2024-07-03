<div class="col-span-6">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Permission Settings') }}</h4>
        </div>
        <div class="card-body p-6">
            @include('backend.setting.site_setting.include.form.__open_action')

            @foreach($fields['elements'] as $key => $field)
                <div class="input-area grid grid-cols-12 gap-5 mb-5">
                    <div class="lg:col-span-4 col-span-12 form-label pt-0">
                        {{ __($field['label']) }}
                    </div>

                    <div class="lg:col-span-8 col-span-12">
                        <div class="form-switch ps-0">
                            <input class="form-check-input" type="hidden" value="0" name="{{$field['name']}}"/>
                            <div class="switch-field flex overflow-hidden same-type m-0">
                                <input
                                    type="radio"
                                    id="active-{{$key}}"
                                    name="{{$field['name']}}"
                                    value="1"
                                    @if(oldSetting($field['name'],$section)) checked @endif
                                />
                                <label for="active-{{$key}}">{{ __('Enable') }}</label>
                                <input
                                    type="radio"
                                    id="disable-{{$key}}"
                                    name="{{$field['name']}}"
                                    value="0"
                                    @if(!oldSetting($field['name'],$section)) checked @endif
                                />
                                <label for="disable-{{$key}}">{{ __('Disabled') }}</label>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>
</div>
