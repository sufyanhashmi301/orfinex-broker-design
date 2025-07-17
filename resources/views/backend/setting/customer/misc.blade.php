@extends('backend.setting.customer.index')
@section('title')
    {{ __('Customer Misc Settings') }}
@endsection
@section('customer-content')
    <?php
        $section = 'customer_misc';
        $fields = config('setting.customer_misc');
    ?>
    <div class="card">
        <div class="card-body p-6">
            @include('backend.setting.site_setting.include.form.__open_action')
            
            <!-- Grace Period Settings Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-4">
                <!-- Grace Period Toggle -->
                <div class="input-area">
                    <label class="form-label invisible" for="">
                        {{ __('Grace Period') }}
                    </label>
                    <div class="flex items-center space-x-7 flex-wrap">
                        <div class="form-label !w-auto !mb-0">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enable temporary holding for unverified users">
                                {{ __('Grace Period') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </div>
                        <div class="form-switch leading-none ps-0">
                            <input type="hidden" value="0" name="grace_period"/>
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input type="checkbox" name="grace_period" value="1" @if(oldSetting('grace_period', $section)) checked @endif class="sr-only peer">
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Grace Period Days Setting -->
                <div class="input-area">
                    <label class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Days before unverified users are removed">
                            {{ __('User Removal Grace Period (Days)') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <div class="relative">
                        <input type="number" name="user_removal_grace_period" 
                            class="form-control @if($errors->has('user_removal_grace_period')) has-error @endif"
                            min="1" max="365"
                            value="{{ oldSetting('user_removal_grace_period', $section) }}"
                            required>
                    </div>
                </div>
            </div>

            <!-- All Other Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($fields['elements'] as $key => $field)
                    @if($field['name'] != 'grace_period' && $field['name'] != 'user_removal_grace_period')
                        @if($field['type'] == 'checkbox')
                            <div class="input-area flex items-center justify-between border border-slate-100 dark:border-slate-700 rounded px-3 py-2">
                                <label class="form-label !mb-0">
                                    {{ __($field['label']) }}
                                </label>
                                <div class="form-switch ps-0 leading-[0]">
                                    <input type="hidden" name="{{$field['name']}}" value="0"/>
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="{{$field['name']}}" value="1" @if(oldSetting($field['name'],$section)) checked @endif class="sr-only peer">
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </div>
                        @else
                            <div class="input-area">
                                <label class="form-label">
                                    {{ __($field['label']) }}
                                </label>
                                <div class="relative">
                                    <input type="{{$field['type']}}" name="{{ $field['name'] }}"
                                       class="form-control @if($errors->has($field['name'])) has-error @endif"
                                       value="{{oldSetting($field['name'],$section)}}"
                                       @if($field['name'] == 'user_removal_grace_period') min="1" max="365" @endif
                                       required="">
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>

            <!-- Consolidated Professional Note -->
            <div class="mt-6 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                <h4 class="text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">{{ __('Note:') }}</h4>
                <ul class="text-xs text-slate-600 dark:text-slate-300 space-y-1 list-disc list-inside pl-5">
                    <li>{{ __('When enabled, new unverified users will be placed in grace period') }}</li>
                    <li>{{ __('Users automatically exit grace period upon email verification or activity') }}</li>
                    <li>{{ __('Removal grace period defines how many days grace period users remain before deletion') }}</li>
                </ul>
            </div>
            
            @include('backend.setting.site_setting.include.form.__close_action')
        </div>
    </div>
@endsection