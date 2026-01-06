@extends('backend.setting.site_setting.index')
@section('title')
    {{ __('Site Settings') }}
@endsection
@section('site-setting-content')
    <div class="space-y-5">
        @foreach(config('setting') as $section => $fields)
            @includeIf('backend.setting.site_setting.include.__'. $section)
        @endforeach
    </div>
@endsection
@push('website-script')
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

        // Initialize Select2 for timezone dropdown with search functionality
        // Options are already populated in the blade template via PHP
        $('.site-timezone').select2({
            placeholder: 'Select timezone...',
            allowClear: false,
            width: '100%',
            dropdownAutoWidth: true
        });


    })(jQuery);
    </script>
@endpush 