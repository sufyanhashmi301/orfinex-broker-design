@extends('backend.setting.index')
@section('setting-title')
    {{ __('Site Settings') }}
@endsection
@section('title')
    {{ __('Site Settings') }}
@endsection
@section('setting-content')

    @foreach(config('setting') as $section => $fields)

        @includeIf('backend.setting.site_setting.include.__'. $section)

    @endforeach
@endsection
@push('single-script')
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

        var timezoneData = JSON.parse(@json(getJsonData('timeZone')));
        const convertedData = timezoneData.map(item => ({
            id: item.name,
            text: `${item.description} (${item.name})`
        }));

        $('.site-timezone').select2({
            data: convertedData
        });


    })(jQuery);
    </script>
@endpush
