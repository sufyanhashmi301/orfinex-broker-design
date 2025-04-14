@extends('backend.setting.payment.withdraw.index')
@section('title')
    {{ __('Misc Settings') }}
@endsection
@section('page-title')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
@endsection
@section('withdraw-content')
    <?php
        $section = 'withdraw_settings';
        $fields = config('setting.withdraw_settings');
        //   dd($fields);
    ?>
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-body p-6">
                @include('backend.setting.site_setting.include.form.__open_action')
                <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-5">
                    @foreach( $fields['elements'] as $key => $field)
                        @if($field['type'] == 'switch')
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
                        @elseif($field['type'] == 'checkbox')
                            <div class="input-area">
                                <label for="" class="form-label invisible">
                                    {{ __($field['label']) }}
                                </label>
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
                        @elseif($field['type'] == 'dropdown')
                            <div class="input-area">
                                <label for="" class="form-label">
                                    {{ __($field['label']) }}
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
                            </div>
                        @else
                            <div class="input-area">
                                <label for="" class="form-label">
                                    {{ __($field['label']) }}
                                </label>
                                <input
                                    type="{{$field['type']}}"
                                    name="{{$field['name']}}"
                                    @if($field['data'] == 'int' || $field['data'] == 'double') oninput="this.value = validateDouble(this.value)" @endif
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
    </div>
@endsection
