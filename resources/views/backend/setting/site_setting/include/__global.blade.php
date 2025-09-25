<div class="flex justify-between flex-wrap items-center mb-6">
    <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
        {{ __($fields['title']) }}
    </h4>
</div>
<div class="card">
    <div class="card-body p-6">
        @include('backend.setting.site_setting.include.form.__open_action')

        <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-5">
            @foreach( $fields['elements'] as $key => $field)
                @if($field['type'] == 'file')
                    <div class="input-area">
                        <label class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __($field['description']) }}">
                                {{ __($field['label']) }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
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
                                <span>{{ __('upload') .' '.__($field['label'])}} </span>
                            </label>
                        </div>
                    </div>
                @elseif($field['type'] == 'switch')
                    <div class="input-area">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __($field['description']) }}">
                                {{ __($field['label']) }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <div class="flex items-center space-x-7 flex-wrap">
                            <div class="success-radio">
                                <label class="flex items-center cursor-pointer">
                                    <input
                                        type="radio"
                                        id="active1-{{$key}}"
                                        class="hidden site-currency-type"
                                        name="{{$field['name']}}"
                                        value="fiat"
                                        @checked(oldSetting($field['name'],$section) == 'fiat')
                                    >
                                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                    <span class="text-success-500 text-sm leading-6 capitalize">
                                        {{ __('Fiat') }}
                                    </span>
                                </label>
                            </div>
                            <div class="success-radio">
                                <label class="flex items-center cursor-pointer">
                                    <input
                                        type="radio"
                                        id="disable0-{{$key}}"
                                        class="hidden site-currency-type"
                                        name="{{$field['name']}}"
                                        value="crypto"
                                        @checked(oldSetting($field['name'],$section) == 'crypto')
                                    >
                                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                    <span class="text-success-500 text-sm leading-6 capitalize">
                                        {{ __('Crypto') }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                @elseif($field['type'] == 'dropdown')
                    <div class="input-area">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __($field['description']) }}">
                                {{ __($field['label']) }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        @if($field['name'] == 'site_currency')

                            <div class="currency-fiat">
                                <select name="" class="form-control w-100 site-currency-fiat" id="">
                                    @if(setting('site_currency_type','global') == 'fiat')
                                        <option selected value="{{ oldSetting($field['name'],$section) }}"> {{ oldSetting($field['name'],$section) }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                            <div class="currency-crypto">
                                <select name="" class="form-control w-100 site-currency-crypto" id="">
                                    @if(setting('site_currency_type','global') == 'crypto')
                                        <option selected value="{{ oldSetting($field['name'],$section) }}"> {{ oldSetting($field['name'],$section) }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        @endif

                        @if($field['name'] == 'site_timezone')
                            <select name="{{$field['name']}}" class="form-control w-100 site-timezone" id="">
                                <option selected value="{{ oldSetting($field['name'],$section) }}"> {{ oldSetting($field['name'],$section) }}
                                </option>
                            </select>
                        @endif

                        @if($field['name'] == 'site_referral')
                            <select name="{{$field['name']}}" class="form-control w-100" id="">
                            @foreach(['level','target'] as $type)
                                <option @selected(oldSetting($field['name'],$section) == $type)
                                        value="{{$type}}"> {{ ucwords($type) .' '.__('Base') }}
                                </option>
                            @endforeach
                            </select>
                        @endif


                        @if($field['name'] == 'home_redirect')
                            <select name="{{$field['name']}}" class="form-control w-100" id="">
                            <option @selected(oldSetting($field['name'],$section) == '/')
                                    value="/"> {{ __('Home Page') }}
                            </option>
                            @foreach($pages as $page)
                                @if($page->status)
                                    <option @selected(oldSetting($field['name'],$section) == $page->url)
                                            value="{{$page->url}}"> {{ ucwords($page->title) .' '. __('Page')  }}
                                    </option>
                                @endif
                            @endforeach
                            </select>

                        @endif

                        @if($field['name'] == 'session_expiry')

                            @php
                                $staff = Auth::user();
                            @endphp

                            <select name="{{$field['name']}}" class="form-control w-100" id="">
                                @foreach($field['options'] as $value => $label)
                                    <option value="{{ $value }}" {{ Auth::user()->session_expiry == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>

                        @endif



                    </div>
                @elseif($field['type'] == 'checkbox')
                    <div class="input-area">
                        <label for="" class="invisible">{{ __($field['label']) }}</label>
                        <div class="flex items-center space-x-7 flex-wrap">
                            <div class="form-label !w-auto pt-0 !mb-0">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __($field['description']) }}">
                                    {{ __($field['label']) }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </div>
                            <div class="form-switch ps-0" style="line-height:0;">
                                <input class="form-check-input" type="hidden" value="0" name="{{$field['name']}}"/>
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                    <input type="checkbox" name="{{$field['name']}}" value="1" @if(oldSetting($field['name'],$section)) checked @endif class="sr-only peer">
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                        </div>
                        @if($field['name'] == 'show_global_accounts_with_ib_rebate_rules')
                            <p class="text-xs text-slate-500 mt-2">
                                After enabling this setting, manage Global account access from
                                <a href="{{ route('admin.ib-group.index') }}" target="_blank" rel="noopener" class="underline">IB Groups</a>.
                            </p>
                        @endif
                    </div>
                @else
                    <div class="input-area">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __($field['description']) }}">
                                {{ __($field['label']) }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <div class="joint-input relative">

                            <input
                                type="{{$field['type']}}"
                                name="{{$field['name']}}"
                                class=" form-control {{ $errors->has($field['name']) ? 'has-error' : '' }}"
                                value="{{ oldSetting($field['name'],$section) }}"
                            />

                            @if($field['data'] == 'double')
                                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1">
                                    {{ setting('site_currency','global') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        @include('backend.setting.site_setting.include.form.__close_action')
    </div>
</div>
