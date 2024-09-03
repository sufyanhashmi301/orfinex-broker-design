@extends('backend.links.index')
@section('title')
    {{ __('Document Links') }}
@endsection
@section('links-content')
    <?php
        $section = 'document_links';
        $fields = config('setting.document_links');
        //   dd($fields);
    ?>
    <div class="card">
        <div class="card-body p-6">
            @include('backend.setting.site_setting.include.form.__open_action')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($fields['elements'] as $key => $field)
                    @if($key % 2 == 0)
                        <div class="input-area">
                            <label for="" class="form-label">{{ __($field['label']) }}</label>
                            <div class="relative">
                    @endif
                    @if($field['type'] == 'checkbox')
                        <input type="hidden" name="{{$field['name']}}" value="0">
                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full flex items-center justify-center">
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input type="checkbox" name="{{$field['name']}}" value="1" @if(oldSetting($field['name'],$section)) checked @endif class="sr-only peer">
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </span>
                    @elseif($field['type'] == 'url')
                        <input
                            type="{{$field['type']}}"
                            name="{{ $field['name'] }}"
                            class="form-control !pl-16.5 {{ $errors->has($field['name']) ? 'has-error' : '' }}"
                            value="{{oldSetting($field['name'],$section)}}"
                            placeholder="URL"
                        />
                        <span class="absolute left-0 top-1/2 px-3 -translate-y-1/2 h-full border-r border-r-slate-200 dark:border-r-slate-700 flex items-center justify-center">
                            {{ __('URL') }}
                        </span>
                    @else
                        <input type="{{$field['type']}}" name="{{ $field['name'] }}" class="form-control !pr-24 @if($errors->has($field['name'])) has-error @endif" placeholder="URL" value="{{oldSetting($field['name'],$section)}}">
                    @endif
                    @if($key % 2 == 1)
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>
@endsection
