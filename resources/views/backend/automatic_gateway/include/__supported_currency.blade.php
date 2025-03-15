@foreach($supportedCurrencies as $currency)
{{--    {{dd(array_search($currency, $mappedCurrencies) )}}--}}
    @if($gateway->gateway_code === 'match2pay' && in_array( $currency,  $mappedCurrencies))
        <option value="{{ array_search($currency, $mappedCurrencies, true) }}">{{ $currency }}</option>
    @else
        <option value="{{ $currency }}">{{ $currency }}</option>
    @endif
@endforeach
