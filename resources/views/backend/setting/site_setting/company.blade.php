@extends('backend.setting.index')
@section('title')
    {{ __('Company Settings') }}
@endsection
@section('setting-content')
    <?php
        $section = 'common_settings';
        $fields = config('setting.common_settings');
        //   dd($fields);
    ?>

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ 'Common Settings'  }}
        </h4>
    </div>
    <div class="card">
        <div class="card-body p-6">
            @include('backend.setting.site_setting.include.form.__open_action')
                <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                    @foreach( $fields['elements'] as $key => $field)
                        @if($field['type'] == 'url')
                            <div class="input-area">
                                <label for="" class="form-label">
                                    {{ __($field['label']) }}
                                </label>
                                <div class="relative">
                                    <input 
                                        type="{{$field['type']}}" 
                                        name="{{$field['name']}}"
                                        class="form-control !pl-22 {{ $errors->has($field['name']) ? 'has-error' : '' }}" 
                                        value="{{ oldSetting($field['name'],$section) }}"
                                    />
                                    <span class="absolute left-0 top-1/2 px-3 -translate-y-1/2 h-full border-r border-r-slate-200 dark:border-r-slate-700 flex items-center justify-center">
                                        https://
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="input-area">
                                <label for="" class="form-label">
                                    {{ __($field['label']) }}
                                </label>
                                <input
                                    type="{{$field['type']}}"
                                    name="{{$field['name']}}"
                                    class=" form-control {{ $errors->has($field['name']) ? 'has-error' : '' }}"
                                    value="{{ oldSetting($field['name'],$section) }}"
                                />
                            </div>
                        @endif
                    @endforeach
                </div>
            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>
@endsection
