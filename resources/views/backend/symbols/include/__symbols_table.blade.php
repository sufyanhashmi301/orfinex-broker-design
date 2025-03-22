
@foreach ($mt5Symbols as $symbol)
    <tr>
        <td class="table-td"><strong>{{ $symbol->Symbol_ID }}</strong></td>
        <td class="table-td">{{ $symbol->Symbol }}</td>
        <td class="table-td">{{ $symbol->Path }}</td>
        <td class="table-td">{{ $symbol->Description }}</td>
        <td class="table-td">{{ $symbol->ContractSize }}</td>
        <td class="table-td">
            <div class="form-switch">
                <label
                    class="relative !inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-checkbox">
                    <input
                        type="checkbox"
                        class="sr-only peer symbol-toggle"
                        data-id="{{ $symbol->Symbol_ID }}"
                        @if($symbol->status === 'Enabled') checked @endif>
                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white
    after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all
    dark:border-gray-600 peer-checked:bg-black-500"></span>
                </label>
            </div>
        </td>
    </tr>
@endforeach

