@php
    // Determine if the switch should be checked initially
    $checkRecord = \App\Models\Symbol::where('symbol',$Symbol)->first();
    $checked = in_array($Symbol, $existingSymbols) && $checkRecord->status==1 ? 'checked' : '';
@endphp
<div class="form-switch">
    <label class="relative !inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-checkbox">
        <input
            type="checkbox"
            name=""
            value="1"
            class="sr-only peer"
            data-id="{{ $Symbol_ID }}"
            data-previous-state="{{ $checked ? 'true' : 'false' }}"
            onchange="insertRecord({{ $Symbol_ID }}, $(this).data('previous-state'))"
            {{ $checked }}>
        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
    </label>
</div>
