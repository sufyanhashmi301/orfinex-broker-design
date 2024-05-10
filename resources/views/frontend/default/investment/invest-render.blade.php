@foreach ($plans as $plan)

    {{--{{dd($plan->is_discount,$plan->swap_amount)}}--}}

    <div class="warning-radio">
        <label class="flex items-center cursor-pointer p-3 rounded border dark:border-slate-700">
            <input type="radio" class="hidden priceInput" name="scheme" value="{{ the_hash(data_get($plan, 'id')) }}" id="btnRadio{{ data_get($plan, 'id') }}" data-price="@if($plan->is_discount == 1 && $plan->discount_price > 0) {{$plan->discount_price}} @else {{amount_format(data_get($plan, 'amount'), [2])}} @endif"
            @if ($plan->id == $invest->id) checked @endif>
            <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
            <span class="flex-1 inline-flex justify-between items-center">
                <span class="dark:text-white">${{ amount_format(data_get($plan, 'amount_allotted'), [2]) }}</span>
                @if($plan->is_discount == 1 && $plan->discount_price > 0)
                    <span class="badge bg-slate-900 text-white capitalize">
                        <strike>$ {{amount_format(data_get($plan, 'amount'), [2])}}</strike> / $ {{amount_format(data_get($plan, 'discount_price'), [2])}} 
                    </span>
                @else
                    <span class="badge bg-slate-900 text-white capitalize">$ {{amount_format(data_get($plan, 'amount'), [2])}} </span>
                @endif
            </span>
        </label>
    </div>
@endforeach
