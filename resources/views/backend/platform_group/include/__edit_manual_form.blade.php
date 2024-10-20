<form action="{{ route('admin.groups.updateManually', $group->id) }}" method="post">
    @csrf
    @method('PUT')
    <div class="space-y-5">
        <div class="input-area relative">
            <label for="" class="form-label">
                {{ __('Group Name') }}
            </label>
            <input type="text" name="group" class="form-control" value="{{ $group->group }}">
        </div>
        <div class="input-area relative">
            <label for="" class="form-label">
                {{ __('Currency') }}
            </label>
            <select name="currency" class="form-control">
                <option value="USD" @selected($group->currency == 'USD')>{{ __('USD') }}</option>
                <option value="EUR" @selected($group->currency == 'EUR')>{{ __('EUR') }}</option>
                <option value="GBP" @selected($group->currency == 'GBP')>{{ __('GBP') }}</option>
                <option value="JPY" @selected($group->currency == 'JPY')>{{ __('JPY') }}</option>
                <option value="AUD" @selected($group->currency == 'AUD')>{{ __('AUD') }}</option>
            </select>
        </div>
        <div class="input-area relative">
            <label for="" class="form-label">
                {{ __('Currency Digits') }}
            </label>
            <select name="currencyDigits" class="form-control">
                <option value="1" @selected($group->currencyDigits == 1)>{{ __('1') }}</option>
                <option value="2" @selected($group->currencyDigits == 2)>{{ __('2 (Default)') }}</option>
                <option value="3" @selected($group->currencyDigits == 3)>{{ __('3') }}</option>
                <option value="4" @selected($group->currencyDigits == 4)>{{ __('4') }}</option>
                <option value="5" @selected($group->currencyDigits == 5)>{{ __('5') }}</option>
            </select>
        </div>
        <div class="input-area relative">
            <label for="" class="form-label">
                {{ __('Platform') }}
            </label>
            @php
                $traderTypes = [
                    \App\Enums\TraderType::MT5,
                    \App\Enums\TraderType::X9,
                    \App\Enums\TraderType::CTRADER,
                    \App\Enums\TraderType::All,
                ];
            @endphp
            <select name="trader_type" class="form-control">
                @foreach ($traderTypes as $type)
                    <option value="{{ $type }}" @selected($type == $group->trader_type)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>
        <div class="input-area relative">
            <div class="flex items-center space-x-7 flex-wrap">
                <label class="form-label !w-auto pt-0">
                    {{ __('Status:') }}
                </label>
                <div class="form-switch ps-0">
                    <input type="hidden" value="0" name="status">
                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                        <input type="checkbox" name="status" value="1" class="sr-only peer" @checked($group->status)>
                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="action-btns text-right mt-10">
        <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
            {{ __('Update Group') }}
        </button>
        <a href="#"
           class="btn btn-danger inline-flex items-center justify-center"
           data-bs-dismiss="modal"
           aria-label="Close">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
            {{ __('Close') }}
        </a>
    </div>
</form>
