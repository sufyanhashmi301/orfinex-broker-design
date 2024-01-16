<h3 class="title mb-4">
    {{ __('Deposit Approval Action') }}
</h3>
<form action="{{ route('admin.deposit.action.now') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{ $id }}">
{{--        {{ __('Total amount') }}: <strong>{{ $data->final_amount. ' '.$currency }}</strong>--}}

    <div class="site-input-groups">
        <label class="box-input-label" for="">{{ __('Deposited Amount:') }}</label>
        <div class="input-group joint-input">
            <input type="text" name="final_amount" id="amount"  value="{{$data->final_amount}}" oninput="this.value = validateDouble(this.value)"  class="form-control"/>
            <span class="input-group-text" id="currency">{{$currency}}</span>
        </div>
    </div>
    @if($data->pay_currency != $currency)
        <div class="site-input-groups">
            <label class="box-input-label" for="">{{ __('Conversion Amount:') }}</label>
            <div class="input-group joint-input">
                <input type="text" name="pay_amount" id="converted-amount" value="{{$data->pay_amount}}" oninput="this.value = validateDouble(this.value)"  class="form-control"/>
                <span class="input-group-text" id="converted-currency">{{$data->pay_currency}}</span>
            </div>
            <div class="font-Inter text-xs text-red-500 pt-2 inline-block conversion-rate"></div>

        </div>
    @endif
<br>

<ul class="list-group mb-4">
    @foreach( json_decode($data->manual_field_data) as $key => $value)
        <li class="list-group-item">
            {{ $key }}:

            @if($value != new stdClass())
                @if( file_exists('assets/'.$value))
                    <img src="{{ asset($value) }}" alt=""/>
                @else
                    <strong>{{ $value }}</strong>
                @endif
            @endif
        </li>
    @endforeach
</ul>

    <div class="site-input-groups">
        <label for="" class="box-input-label">{{ __('Details Message(Optional)') }}</label>
        <textarea name="message" class="form-textarea mb-0" placeholder="Details Message"></textarea>
    </div>

    <div class="action-btns">
        <button type="submit" name="approve" value="yes" class="site-btn-sm primary-btn me-2">
            <i icon-name="check"></i>
            {{ __('Approve') }}
        </button>
        <button type="submit" name="reject" value="yes" class="site-btn-sm red-btn">
            <i icon-name="x"></i>
            {{ __('Reject') }}
        </button>
    </div>

</form>
{{--{{dd($gateway)}}--}}
<script>
    'use strict';
    lucide.createIcons();
    let globalData = @json($gateway);

    // $('#amount').on('keyup', function (e) {
        $('body').on('keyup', '#amount', function (event) {
        "use strict"
        var amount = $(this).val();
        var currency = $("#currency").text();
            $('.conversion-rate').text('1' +' '+ currency + ' = ' + globalData.rate +' '+ globalData.currency)

        $('#converted-amount').val(parseFloat((amount * globalData.rate).toFixed(4)).toString())
    })
    $('#converted-amount').on('keyup', function (e) {
        "use strict"
        var converted_amount = $(this).val();
        var currency = $("#currency").text();
        var amount = parseFloat((converted_amount / globalData.rate).toFixed(4)).toString();
        $('#amount').val(amount);
        $('.conversion-rate').text('1' +' '+ currency + ' = ' + globalData.rate +' '+ globalData.currency)

    })

</script>



