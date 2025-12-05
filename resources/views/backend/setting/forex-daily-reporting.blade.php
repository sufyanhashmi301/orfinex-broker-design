@extends('backend.setting.misc.index')
@section('title')
    {{ __('Forex Daily Reporting') }}
@endsection
@section('misc-content')
    <?php
        $section = 'forex_daily_reporting';
        $fields = config("setting.$section");
    ?>

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="section" value="{{$section}}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach( $fields['elements'] as $key => $field)
                        @if($field['type'] == 'checkbox')
                            <div class="input-area @if($field['name'] == 'daily_statement_enabled') col-span-2 @endif">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <div class="form-switch ps-0" style="line-height: 0">
                                        <input class="form-check-input" type="hidden" value="0" name="{{ $field['name'] }}">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" 
                                                id="{{ $field['name'] }}" 
                                                name="{{ $field['name'] }}" 
                                                value="1" 
                                                @if(setting($field['name'], $section, $field['value'])) checked @endif 
                                                class="sr-only peer">
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                    <label class="form-label !w-auto !mb-0">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __($field['description']) }}">
                                            {{ __($field['label']) }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        @elseif($field['type'] == 'select')
                            <div class="input-area">
                                <label for="" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __($field['description']) }}">
                                        {{ __($field['label']) }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <select class="form-control" id="{{ $field['name'] }}" name="{{ $field['name'] }}">
                                    @foreach($field['options'] as $key => $label)
                                        <option value="{{ $key }}" @if(setting($field['name'], $section, $field['value']) == $key) selected @endif>
                                            {{ __($label) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="input-area">
                                <label for="" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __($field['description']) }}">
                                        {{ __($field['label']) }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <input type="{{ $field['type'] }}" class="form-control" id="{{ $field['name'] }}" name="{{ $field['name'] }}" value="{{ setting($field['name'], $section, $field['value']) }}" required>
                            </div>
                        @endif

                    @endforeach
                </div>
                <div class="mt-10">
                    <button type="submit" class="btn btn-dark inline-flex">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

