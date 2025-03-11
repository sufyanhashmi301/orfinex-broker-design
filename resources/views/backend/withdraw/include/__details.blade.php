<tr class="border-b border-slate-100 dark:border-slate-700">
    <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-3 py-2">
        <strong>{{ __('Withdraw Fee') }}</strong>
    </td>
    <td><span class="withdrawFee">{{ $charge }}</span> {{ $currency }}</td>
</tr>

@if($conversionRate != null)
    <tr class="border-b border-slate-100 dark:border-slate-700 conversion">
        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-3 py-2">
            <strong>{{ __('Conversion Rate') }}</strong>
        </td>
        <td class="conversion-rate"> 1 {{ $currency }} = {{ $conversionRate }}</td>
    </tr>
    <tr class="border-b border-slate-100 dark:border-slate-700 conversion">
        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-3 py-2">
            <strong>{{ __('Pay Amount') }}</strong>
        </td>
        <td class="pay-amount"></td>
    </tr>
@endif

<tr class="border-b border-slate-100 dark:border-slate-700">
    <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-3 py-2">
        <strong>{{ __('Withdraw Account') }}</strong>
    </td>
    <td>{{ $name }}</td>
</tr>

@foreach($credentials as $name => $data)
    <tr class="border-b border-slate-100 dark:border-slate-700">
        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-3 py-2">
            <strong>{{ __($name) }}</strong>
        </td>
        <td>
            @if($data['type'] == 'file')
                <div class="flex gap-3">
                    <a href="{{ asset($data['value']) }}" class="btn-link" download>{{ __('Download') }}</a>
                    <a href="{{ asset($data['value']) }}" class="btn-link" target="_blank">{{ __('View') }}</a>
                </div>
            @else
                {{ $data['value'] }}
            @endif
        </td>
    </tr>
@endforeach
