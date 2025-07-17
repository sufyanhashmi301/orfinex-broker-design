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
                    <div class="site-input-groups row">
                        <label class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __($field['description']) }}">
                                {{ __($field['label']) }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        {{--{{dd($field['name'])}}--}}
                        @if($field['name'] == 'withdraw_deduction')
                        <div class="input-area">
                            <input class="form-check-input" type="hidden" value="0" name="{{$field['name']}}"/>
                            <div class="flex items-center gap-3 md:gap-7 flex-wrap">
                                <div class="success-radio">
                                    <label class="flex items-center cursor-pointer">
                                        <input
                                            type="radio"
                                            id="withdraw-active-{{$key}}"
                                            class="hidden"
                                            name="{{$field['name']}}"
                                            value="1"
                                            @if(oldSetting($field['name'],$section)) checked @endif
                                        >
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="text-success text-sm leading-6 capitalize">
                                            {{ __('On Request') }}
                                        </span>
                                    </label>
                                </div>
                                <div class="success-radio">
                                    <label class="flex items-center cursor-pointer">
                                        <input
                                            type="radio"
                                            id="withdraw-disable-{{$key}}"
                                            class="hidden"
                                            name="{{$field['name']}}"
                                            value="0"
                                            @if(!oldSetting($field['name'],$section)) checked @endif
                                        >
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="text-success text-sm leading-6 capitalize">
                                            {{ __('On Approval') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @elseif($field['name'] == 'copy_trading')
                        <div class="input-area">
                            <input class="form-check-input" type="hidden" value="0" name="{{$field['name']}}"/>
                            <div class="flex items-center flex-wrap gap-3 md:gap-7">
                                <div class="success-radio">
                                    <label class="flex items-center cursor-pointer">
                                        <input
                                            type="radio"
                                            id="copy-trading-active-{{$key}}"
                                            class="hidden"
                                            name="{{$field['name']}}"
                                            value="1"
                                            @if(oldSetting($field['name'],$section)) checked @endif
                                        >
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="text-success text-sm leading-6 capitalize">
                                            {{ __('Show') }}
                                        </span>
                                    </label>
                                </div>
                                <div class="success-radio">
                                    <label class="flex items-center cursor-pointer">
                                        <input
                                            type="radio"
                                            id="copy-trading-disable-{{$key}}"
                                            class="hidden"
                                            name="{{$field['name']}}"
                                            value="0"
                                            @if(!oldSetting($field['name'],$section)) checked @endif
                                        >
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="text-success text-sm leading-6 capitalize">
                                            {{ __('Hide') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @elseif ($field['type'] === 'radio')
                            <div class="flex items-center flex-wrap gap-3 md:gap-7">
                                @foreach ($field['options'] as $value => $label)
                                    <div class="success-radio">
                                        <label class="flex items-center cursor-pointer">
                                            <input
                                                type="radio"
                                                class="hidden"
                                                name="{{$field['name']}}"
                                                value="{{ $value }}"
                                                {{ oldSetting($field['name'],$section) === $value ? 'checked' : '' }}>
                                            <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                <span class="text-success text-sm leading-6 capitalize">
                                                {{ $label }}
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                        <div class="input-area">
                            <input class="form-check-input" type="hidden" value="0" name="{{$field['name']}}"/>
                            <div class="flex items-center flex-wrap gap-3 md:gap-7">
                                <div class="success-radio">
                                    <label class="flex items-center cursor-pointer">
                                        <input
                                            type="radio"
                                            id="features-active-{{$key}}"
                                            class="hidden"
                                            name="{{$field['name']}}"
                                            value="1"
                                            @if(oldSetting($field['name'],$section)) checked @endif
                                        >
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="text-success text-sm leading-6 capitalize">
                                            {{ __('Enable') }}
                                        </span>
                                    </label>
                                </div>
                                <div class="success-radio">
                                    <label class="flex items-center cursor-pointer">
                                        <input
                                            type="radio"
                                            id="features-deduction-{{$key}}"
                                            class="hidden"
                                            name="{{$field['name']}}"
                                            value="0"
                                            @if(!oldSetting($field['name'],$section)) checked @endif
                                        >
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="text-success text-sm leading-6 capitalize">
                                            {{ __('Disabled') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @include('backend.setting.site_setting.include.form.__close_action')
    </div>
</div>
