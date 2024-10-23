<div class="flex justify-between flex-wrap items-center mb-6">
    <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
        {{ __($fields['title']) }}
    </h4>
</div>
<div class="card">
    <div class="card-body p-6">
        @include('backend.setting.site_setting.include.form.__open_action')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach( $fields['elements'] as $key => $field)
                @if($field['type'] == 'file')
                    <div class="input-area">
                        <label class="form-label">
                            {{ __($field['label']) }}
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
                            {{ __($field['label']) }}
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
                                    <span class="text-success text-sm leading-6 capitalize">
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
                                    <span class="text-success text-sm leading-6 capitalize">
                                        {{ __('Crypto') }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                @elseif($field['type'] == 'dropdown')
                    <div class="input-area">
                        <label for="" class="form-label">
                            {{ __($field['label']) }}
                        </label>
                        <select name="" class="form-control">
                            @foreach ($field['options'] as $value => $label)
                                <option @selected(oldSetting($field['name'],$section) == $value) value="{{ $value }}">
                                {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="input-area">
                        <label for="" class="form-label">
                            {{ __($field['label']) }}
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
