<div class="col-span-6">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __($fields['title']) }}</h4>
        </div>
        <div class="card-body p-6">

            <p class="paragraph text-xs mb-4"><iconify-icon class="mr-1" icon="lucide:alert-triangle"></iconify-icon><strong>Warning:</strong> Once you <strong>Enable</strong>
                the <strong>Maintenance Mode</strong> then you need to remember the <strong>Secret Key</strong> to turn
                back the website.</p>

            @include('backend.setting.site_setting.include.form.__open_action')
            @foreach($fields['elements'] as $key => $field)
                @if($field['type'] == 'checkbox')
                    <div class="input-area grid grid-cols-12 gap-5 mb-5">
                        <div class="lg:col-span-4 col-span-12 form-label pt-0">
                            {{ __($field['label']) }}
                            <iconify-icon class="toolTip onTop" icon="lucide:info" data-tippy-theme="dark" title=""
                            data-tippy-content="Do not enable it unless you want the site need to be under Maintenance"></iconify-icon>
                        </div>
                        <div class="lg:col-span-8 col-span-12">
                            <div class="form-switch ps-0">
                                <input class="form-check-input" type="hidden" value="0" name="{{$field['name']}}"/>
                                <div class="switch-field flex overflow-hidden same-type m-0">
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
                    </div>
                @elseif($field['type'] == 'textarea')
                    <div class="input-area grid grid-cols-12 gap-5 mb-5">
                        <div class="lg:col-span-4 col-span-12 form-label pt-0">{{ __($field['label']) }}</div>
                        <div class="lg:col-span-8 col-span-12">
                            <textarea name="{{ $field['name'] }}" class="form-control @if($errors->has($field['name'])) has-error @endif" rows="6">
                                {{oldSetting($field['name'],$section)}}
                            </textarea>
                        </div>
                    </div>

                @else
                    <div class="input-area grid grid-cols-12 gap-5 mb-5">
                        <div class="lg:col-span-4 col-span-12 form-label pt-0">{{ __($field['label']) }}
                            @if($field['name'] == 'secret_key')
                                <iconify-icon class="toolTip onTop" icon="lucide:info" data-tippy-theme="dark" title=""
                                   data-tippy-content="Remember the Secret Key. Use domain/secret-key to trun back the website live"></iconify-icon>
                            @endif
                        </div>
                        <div class="lg:col-span-8 col-span-12">
                            <input type="{{$field['type']}}" name="{{ $field['name'] }}"
                                   class="form-control @if($errors->has($field['name'])) has-error @endif"
                                   placeholder="Label" value="{{oldSetting($field['name'],$section)}}" required="">
                        </div>
                    </div>

                @endif
            @endforeach
            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>
</div>
