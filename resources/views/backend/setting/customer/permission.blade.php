@extends('backend.setting.customer.index')
@section('title')
    {{ __('Customer Permissions') }}
@endsection
@section('customer-content')
    <?php
        $section = 'customer_permission';
        $fields = config('setting.customer_permission');
        //   dd($fields);
    ?>
    <div class="card">
        <div class="card-body p-6">
            @include('backend.setting.site_setting.include.form.__open_action')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($fields['elements'] as $key => $field)
                    @if($field['type'] == 'checkbox')
                        <div class="input-area flex items-center justify-between border border-slate-100 dark:border-slate-700 rounded px-3 py-2">
                            <label class="form-label !mb-0">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __($field['description']) }}">
                                    {{ __($field['label']) }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <div class="form-switch ps-0 leading-[0]">
                                <input class="form-check-input" type="hidden" value="0" name="{{$field['name']}}"/>
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                    <input type="checkbox" name="{{$field['name']}}" value="1" @if(oldSetting($field['name'],$section)) checked @endif class="sr-only peer">
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                        </div>
                    @else
                        <div class="input-area">
                            <label class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __($field['description']) }}">
                                    {{ __($field['label']) }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <div class="relative">
                                <input type="{{$field['type']}}" name="{{ $field['name'] }}"
                                   class="form-control @if($errors->has($field['name'])) has-error @endif"
                                   value="{{oldSetting($field['name'],$section)}}"
                                   required="">
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>

@endsection
