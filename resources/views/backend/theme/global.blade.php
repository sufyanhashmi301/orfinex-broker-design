@extends('backend.setting.index')
@section('title')
    {{ __('Theme Settings') }}
@endsection
@section('setting-content')
    <?php
        $section = 'theme';
        $fields = config('setting.theme');
        //   dd($fields);
    ?>

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Theme Settings') }}
        </h4>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <div class="space-y-5">
                <div>
                    <label for="" class="form-label">
                        {{ __('Site Theme') }}
                    </label>
                    <div class="grid lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-5">
                        @foreach($themes as $theme)
                        <div class="card h-full">
                            <div class="card-body rounded-md bg-white dark:bg-slate-800 shadow-base overflow-hidden">
                                <div class="h-fit group">
                                    <div class="relative overflow-hidden">
                                        <div class="bg-slate-50 dark:bg-slate-900 p-4">
                                            <img src="{{ asset('backend/materials/theme/'.$theme->name . '.jpg') }}" alt="image" class="block w-full h-[350px] object-cover rounded-t-md">
                                        </div>
                                        <div class="absolute h-full w-full bg-black/20 flex items-center justify-center -bottom-10 group-hover:bottom-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                            @if($theme->status)
                                                <a href="javascript:;" class="btn btn-dark inline-flex items-center justify-center mt-4 disabled">
                                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:circle-slash-2"></iconify-icon>
                                                    {{ __('Activated Theme') }}
                                                </a>
                                            @else
                                                <a href="{{ route('admin.theme.status-update',['id' => $theme->id]) }}" class="btn btn-dark inline-flex items-center justify-center mt-4">
                                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                                    {{ __('Active Now') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="items-center p-5">
                                    <div class="success-radio">
                                        <label class="flex items-center cursor-pointer">
                                            <input 
                                                type="radio" 
                                                class="hidden" 
                                                name="theme" 
                                                value="{{ ucwords( str_replace('_', ' ',$theme->name) ) }} Theme" 
                                                @if($theme->status) checked @endif
                                                disabled="disabled"
                                            >
                                            <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                            <span class="text-success-500 text-sm leading-6 capitalize">
                                                {{ ucwords( str_replace('_', ' ',$theme->name) ) }} Theme
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @include('backend.setting.site_setting.include.form.__open_action')
                    <div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-5">
                        @foreach( $fields['elements'] as $key => $field)
                            @if($field['type'] == 'file')
                                <div class="input-area">
                                    <label class="form-label">
                                        <div class="flex items-center">
                                            {!! __($field['label']) !!}
                                        </div>
                                    </label>
                                    <div class="wrap-custom-file {{ $errors->has($field['name']) ? 'has-error' : '' }}">
                                        <input
                                            type="{{$field['type']}}"
                                            name="{{$field['name']}}"
                                            id="{{$field['name']}}"
                                            value="{{ oldSetting($field['name'],$section) }}"
                                            accept=".jpeg, .jpg, .png"
                                        />
                                        <label for="{{ __($field['name']) }}" class="file-ok"
                                            style="background-image: url( {{asset(oldSetting($field['name'],$section)) }} )">
                                            <img
                                                class="upload-icon"
                                                src="{{ asset('global/materials/upload.svg') }}"
                                                alt=""
                                            />
                                            <span>
                                                <div class="flex items-center">
                                                    Upload {!! __($field['label']) !!}
                                                </div>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            @else
                                <div class="md:col-span-3 col-span-1">
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
                                </div>
                            @endif
                        @endforeach
                    </div>
                @include('backend.setting.site_setting.include.form.__close_action')
            </div>
        </div>
    </div>
@endsection