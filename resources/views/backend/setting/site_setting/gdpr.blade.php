@extends('backend.setting.website.index')
@section('title')
    {{ __('Currency Settings') }}
@endsection
@section('website-content')
    <?php
        $section = 'gdpr';
        $fields = config('setting.gdpr');
        //   dd($fields);
    ?>

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __($fields['title']) }}
        </h4>
    </div>
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
                                            <input
                                                type="checkbox"
                                                name="{{$field['name']}}"
                                                value="1"
                                                @if(oldSetting($field['name'],$section)) checked @endif
                                                class="sr-only peer"
                                            >
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
                                <textarea name="{{ $field['name'] }}" class="form-control basicTinymce @if($errors->has($field['name'])) has-error @endif" rows="6">
                                    {{oldSetting($field['name'],$section)}}
                                </textarea>
                            </div>
                        @else
                            <div class="input-area">
                                <label class="form-label pt-0">
                                    {{ __($field['label']) }}
                                </label>
                                <input type="{{$field['type']}}" name="{{ $field['name'] }}"
                                    class="form-control @if($errors->has($field['name'])) has-error @endif"
                                    placeholder="Label" value="{{oldSetting($field['name'],$section)}}" required="">
                            </div>
                        @endif
                    @endforeach
                </div>
            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>
@endsection
