@extends('backend.setting.copy_trading.index')
@section('title')
    {{ __('Brokeree Setting') }}
@endsection
@section('copy-trading-content')
    <?php
    $section = 'copy_trading';
    $fields = config('setting.copy_trading');
    //   dd($fields);
    ?>
    <div class="card">
        <div class="card-body p-6">
            @include('backend.setting.site_setting.include.form.__open_action')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($fields['elements'] as $key => $field)
                    @if($field['type'] == 'checkbox')
                        <div class="input-area md:col-span-2">
                            <div class="flex items-center space-x-7 flex-wrap">
                                <label class="form-label !w-auto pt-0">
                                    {{ __($field['label']) }}
                                </label>
                                <div class="form-switch ps-0">
                                    <input class="form-check-input" type="hidden" value="0" name="{{$field['name']}}"/>
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="{{$field['name']}}" value="1" @if(oldSetting($field['name'],$section)) checked @endif class="sr-only peer">
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @elseif($field['type'] == 'textarea')
                        <div class="input-area md:col-span-2">
                            <label class="form-label pt-0">
                                {{ __($field['label']) }}
                            </label>
                            <textarea name="{{ $field['name'] }}" class="form-control @if($errors->has($field['name'])) has-error @endif" rows="6">
                                {{oldSetting($field['name'],$section)}}
                            </textarea>
                        </div>

                    @else
                        <div class="input-area">
                            <div class="lg:col-span-4 col-span-12 form-label pt-0">
                                {{ __($field['label']) }}
                                @if($field['name'] == 'secret_key')
                                    <iconify-icon class="toolTip onTop" icon="lucide:info" data-tippy-theme="dark" title=""
                                                  data-tippy-content="Remember the Secret Key. Use domain/secret-key to trun back the website live"></iconify-icon>
                                @endif
                            </div>
                            <input type="{{$field['type']}}" name="{{ $field['name'] }}"
                               class="form-control @if($errors->has($field['name'])) has-error @endif"
                               placeholder="Label"
                               value="{{oldSetting($field['name'],$section)}}"
                               required="">
                        </div>
                    @endif
                @endforeach
            </div>
            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>
@endsection
