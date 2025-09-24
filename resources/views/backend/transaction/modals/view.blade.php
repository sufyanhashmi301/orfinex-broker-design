<div class="flex items-center justify-between p-5">
    <h3 class="text-xl font-medium dark:text-white capitalize">
        {{ __('Transaction') }}
    </h3>
    <button type="button"
        class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
        data-bs-dismiss="modal">
        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"></path>
        </svg>
        <span class="sr-only">Close modal</span>
    </button>
</div>
<div class="max-h-[calc(100vh-200px)] overflow-y-auto p-6">
    <form action="#" method="post" class="space-y-5">
        @csrf

        {{--        {{ __('Total amount') }}: <strong>{{ $data->final_amount. ' '.$currency }}</strong> --}}
        <ul
            class="divide-y divide-slate-100 dark:divide-slate-700 border border-slate-100 dark:border-slate-700 rounded mb-5">
            <li class="list-group-item dark:text-slate-300 block py-2 px-3">
                {{ __('Account:') }} <strong>{{ $data->target_id }}</strong>
            </li>
        </ul>
        <div class="input-area">
            <label class="form-label" for="">
                <span class="shift-Away inline-flex items-center gap-1"
                    data-tippy-content="The amount of the transaction">
                    {{ __('Transaction Amount') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <div class="joint-input relative">
                <input type="text" name="final_amount" id="amount" value="{{ $data->final_amount }}"
                    oninput="this.value = validateDouble(this.value)" class="form-control !pr-12" />
                <span
                    class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1"
                    id="currency">
                    {{ $currency }}
                </span>
            </div>
        </div>
        @if ($data->pay_currency != $currency)
            <div class="input-area">
                <label class="form-label" for="">
                    <span class="shift-Away inline-flex items-center gap-1"
                        data-tippy-content="The amount of the transaction after currency conversion">
                        {{ __('Conversion Amount') }}
                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                    </span>
                </label>
                <div class="joint-input relative">
                    <input type="text" name="pay_amount" id="converted-amount" value="{{ $data->pay_amount }}"
                        oninput="this.value = validateDouble(this.value)" class="form-control !pr-12" />
                    <span
                        class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-1"
                        id="converted-currency">
                        {{ $data->pay_currency }}
                    </span>
                </div>
                <!-- <div class="font-Inter text-xs text-danger pt-2 inline-block conversion-rate"></div> -->
            </div>
        @endif

        @if ($data->type->value == 'deposit' || $data->type->value == 'manual_deposit')
            <ul class="list-group border border-slate-100 dark:border-slate-700">
                @php
                    $manual_data = json_decode($data->manual_field_data);
                    if (!$manual_data) {
                        $manual_data = [];
                    }
                @endphp
                @foreach ($manual_data as $key => $value)
                    <li
                        class="list-group-item dark:text-slate-300 py-1 px-2 border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                        <label for="" class="form-label">{{ $key }}:</label>
                        @if (!empty($value) && !is_object($value) && !is_array($value))
                            @if (file_exists('assets/' . $value))
                                <img src="{{ asset($value) }}" alt="" />
                            @else
                                <strong>{{ $value }}</strong>
                            @endif
                        @elseif((is_object($value) && !empty((array) $value)) || (is_array($value) && !empty($value)))
                            <div class="bg-gray-50 dark:bg-gray-800 p-2 rounded text-sm">
                                <pre class="whitespace-pre-wrap">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        {{--    {{dd($data->type->value,App\Enums\TxnType::Withdraw->value)}} --}}
        @if (
            $data->type->value == App\Enums\TxnType::Withdraw->value ||
                $data->type->value == App\Enums\TxnType::WithdrawAuto->value)
            <ul class="list-group border border-slate-100 dark:border-slate-700">
                @php
                    $manual_field_data = json_decode($data->manual_field_data, true);
                    if (!$manual_field_data) {
                        $manual_field_data = [];
                    }
                @endphp
                @foreach ($manual_field_data as $name => $field_data)
                    <li
                        class="list-group-item dark:text-slate-300 block py-2 px-3 border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                        {{ $name }}: @if ($field_data['type'] == 'file')
                            <img src="{{ asset($field_data['value']) }}" alt="" />
                        @else
                            <strong>{{ $field_data['value'] }}</strong>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="input-area">
            <label for="" class="form-label">
                <span class="shift-Away inline-flex items-center gap-1"
                    data-tippy-content="The detail message of the transaction">
                    {{ __('Detail Message') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <textarea name="message" class="summernote form-control mb-0" rows="6" placeholder="">{{ $data->approval_cause}}</textarea>
        </div>
    </form>
</div>
