@extends('backend.setting.payment.index')
@section('title')
    {{ __('Currency Settings') }}
@endsection
@section('payment-content')
    <?php
        $section = 'currency_setting';
        $fields = config('setting.currency_setting');
        //   dd($fields);
    ?>
    <div class="space-y-5">
        <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
            <div>
                <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-5">
                    {{ 'CurrencyLayer API Config (Fiat Currency)' }}
                </h4>
                <div class="card">
                    <div class="card-body p-6">
                        <form action="" class="space-y-5">
                            <div class="input-area">
                                <label for="" class="form-label">
                                    {{ __('Currency Layer Access Key') }}
                                </label>
                                <input type="text" name="" class="form-control" placeholder="Currency Layer Access Key">
                            </div>
                            <div class="input-area">
                                <label for="" class="form-label">
                                    {{ __('Select Update Time') }}
                                </label>
                                <select name="" class="select2 form-control w-full">
                                    <option value="Every Minute">{{ __('Every Minute') }}</option>
                                </select>
                            </div>
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto pt-0">
                                        {{ __('Auto Update Currency Rate') }}
                                    </label>
                                    <div class="form-switch ps-0">
                                        <input class="form-check-input" type="hidden" value="0" name="auto_update_rate">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="auto_update_rate" value="1" checked="" class="sr-only peer">
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-5">
                    {{ 'Coin MarketCap API Config (Crypto Currency)' }}
                </h4>
                <div class="card">
                    <div class="card-body p-6">
                        <form action="" class="space-y-5">
                            <div class="input-area">
                                <label for="" class="form-label">
                                    {{ __('Coin Market Capp App Key') }}
                                </label>
                                <input type="text" name="" class="form-control" placeholder="Coin Market Capp App Key">
                            </div>
                            <div class="input-area">
                                <label for="" class="form-label">
                                    {{ __('Select Update Time') }}
                                </label>
                                <select name="" class="select2 form-control w-full">
                                    <option value="Every Minute">{{ __('Every Minute') }}</option>
                                </select>
                            </div>
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto pt-0">
                                        {{ __('Auto Update Currency Rate') }}
                                    </label>
                                    <div class="form-switch ps-0">
                                        <input class="form-check-input" type="hidden" value="0" name="auto_update_rate">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="auto_update_rate" value="1" checked="" class="sr-only peer">
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-between flex-wrap items-center">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ 'Currency Settings' }}
            </h4>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="" class="btn btn-primary inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                    Add Currency
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                @include('backend.setting.site_setting.include.form.__open_action')
                    <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
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
@section('payment-script')
    <script>
        (function($) {
            'use strict';
            var currencyType ='{{ setting('site_currency_type','global') }}'

            function siteCurrency(currencyType) {
                var currencyData = JSON.parse(@json(getJsonData('currency')));
                $('.site-currency-'+currencyType).select2({
                    data: currencyData[currencyType]
                });
            }

            $('.site-currency-type').on('change',function () {
                   currencyType = $(this).val();
                   currencyShow(currencyType)
            });

            function currencyShow(currencyType){
                if (currencyType === 'fiat'){
                    $('.currency-fiat').removeClass('hidden')
                    $('.currency-crypto').addClass('hidden')

                    $('.site-currency-fiat').attr('name','site_currency');
                    $('.site-currency-crypto').attr('name','');

                }else {
                    $('.currency-crypto').removeClass('hidden')
                    $('.currency-fiat').addClass('hidden')

                    $('.site-currency-crypto').attr('name','site_currency');
                    $('.site-currency-fiat').attr('name','');
                }
            }

            siteCurrency('fiat')
            siteCurrency('crypto')
            currencyShow(currencyType)

        })(jQuery);
    </script>
@endsection
