<div class="flex items-center justify-between mb-5">
    <h3 class="text-xl font-medium dark:text-white capitalize">
        {{ __('Transaction') }}
    </h3>
    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
        <span class="sr-only">Close modal</span>
    </button>
</div>
<form action="#" method="post" class="space-y-5">
    @csrf

{{--        {{ __('Total amount') }}: <strong>{{ $data->final_amount. ' '.$currency }}</strong>--}}

    <div class="input-area">
        <label class="form-label" for="">{{ __('Transaction Amount:') }}</label>
        <div class="joint-input relative">
            <input type="text" name="final_amount" id="amount"  value="{{$data->final_amount}}" oninput="this.value = validateDouble(this.value)"  class="form-control"/>
            <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1" id="currency">
                {{$currency}}
            </span>
        </div>
    </div>
    @if($data->pay_currency != $currency)
        <div class="input-area">
            <label class="form-label" for="">{{ __('Conversion Amount:') }}</label>
            <div class="joint-input relative">
                <input type="text" name="pay_amount" id="converted-amount" value="{{$data->pay_amount}}" oninput="this.value = validateDouble(this.value)"  class="form-control"/>
                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1" id="converted-currency">
                    {{$data->pay_currency}}
                </span>
            </div>
            <div class="font-Inter text-xs text-red-500 pt-2 inline-block conversion-rate"></div>
        </div>
    @endif
    <br>

    <ul class="list-group mb-4">

    @if($data->type->value=='deposit' || $data->type->value=='manual_deposit')
        @foreach( json_decode($data->manual_field_data) as $key => $value)
            <li class="list-group-item dark:text-slate-300 py-1 px-2 rounded border">
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
        @endif
    </ul>

    <div class="input-area">
        <label for="" class="form-label">{{ __('Details Message(Optional)') }}</label>
        <textarea name="message" class="form-control mb-0" rows="6" placeholder="Details Message"></textarea>
    </div>
</form>
