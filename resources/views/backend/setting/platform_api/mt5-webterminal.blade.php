@extends('backend.setting.platform_api.index')
@section('title')
    {{ __('MT5 Web Terminal Settings') }}
@endsection
@section('platform-api-content')
    <?php
        $section = 'webterminal';
        $fields = config('setting.webterminal');
        //   dd($fields);
    ?>
    <div class="card">
        <div class="card-body p-6">
            @include('backend.setting.site_setting.include.form.__open_action')
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                @foreach($fields['elements'] as $key => $field)
                    @if($field['type'] == 'checkbox')
                        <div class="input-area">
                            <div class="flex items-center space-x-7 flex-wrap">
                                <label class="form-label !w-auto !flex items-center">
                                    {{ __($field['label']) }}
                                    <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="{{ __($field['description']) }}"></iconify-icon>
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
                    @elseif($field['type'] == 'select')
                        <div class="input-area">
                            <label class="form-label !w-auto !flex items-center">
                                {{ __($field['label']) }}
                                <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="{{ __($field['description']) }}"></iconify-icon>
                            </label>
                            <select name="" class="form-control">
                                @foreach ($field['options'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    @elseif($field['type'] == 'textarea')
                        <div class="input-area">
                            <label class="form-label !flex items-center">
                                {{ __($field['label']) }}
                                <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="{{ __($field['description']) }}"></iconify-icon>
                            </label>
                            <textarea name="{{ $field['name'] }}" class="form-control @if($errors->has($field['name'])) has-error @endif" rows="6">
                                {{oldSetting($field['name'],$section)}}
                            </textarea>
                        </div>

                    @else
                        <div class="input-area">
                            <label class="form-label !flex items-center">
                                {{ __($field['label']) }}
                                <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="{{ __($field['description']) }}"></iconify-icon>
                            </label>
                            <input type="{{$field['type']}}" name="{{ $field['name'] }}"
                               class="form-control @if($errors->has($field['name'])) has-error @endif"
                               value="{{oldSetting($field['name'],$section)}}"
                               required=""
                            >
                        </div>
                    @endif
                @endforeach
            </div>
            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>

@endsection
