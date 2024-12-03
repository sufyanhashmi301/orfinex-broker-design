@extends('backend.setting.platform_api.index')
@section('title')
    <div>
        <h4 class="font-medium text-xl capitalize dark:text-white inline-block ltr:pr-4 rtl:pl-4 mb-1">
            {{ __('X9 Web Terminal Setting') }}
        </h4>
        <p class="text-sm text-slate-500 dark:text-slate-300">
            {{ __("Seamlessly configure web terminal for user access.") }}
        </p>
    </div>
@endsection
@section('platform-api-content')
    <?php
        $section = 'x9_webterminal';
        $fields = config('setting.x9_webterminal');
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
                            <select name="{{ $field['name'] }}" class="form-control">
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
                            <div class="relative">
                                <input type="{{$field['type']}}" name="{{ $field['name'] }}"
                                   class="form-control @if($errors->has($field['name'])) has-error @endif"
                                   value="{{oldSetting($field['name'],$section)}}"
                                   required="">
                                @if($field['name'] == 'x9_webterminal_width' || $field['name'] == 'x9_webterminal_height')
                                    <span class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center">
                                        <iconify-icon icon="lucide:percent"></iconify-icon>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            @can('x9-webterminal-edit')
            @include('backend.setting.site_setting.include.form.__close_action')
            @endcan
        </div>
    </div>

@endsection
