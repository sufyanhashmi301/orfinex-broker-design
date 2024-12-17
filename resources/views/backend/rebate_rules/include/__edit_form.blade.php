<form id="editRebateRuleForm" method="post" action="{{ route('admin.rebate-rules.update', $rebateRule->id) }}">
    @method('put')
    @csrf
    <div class="space-y-5">
        <div class="input-area relative">
            <label for="title" class="form-label">{{ __('Title:') }}</label>
            <input type="text" name="title" class="form-control mb-0" placeholder="New York" id="title" value="{{ $rebateRule->title }}" />
        </div>
        <div class="input-area ">
            <label for="symbol_groups" class="form-label">{{ __('Select Symbol Groups') }}</label>
            <select id="symbol_groups" name="symbol_groups[]" class="select2 form-control w-full" multiple="multiple">
                @foreach($allSymbolGroups as $symbolGroup)
                    <option value="{{ $symbolGroup->id }}"
                            @selected(null != $rebateRule->symbolGroups && in_array($symbolGroup->id, $rebateRule->symbolGroups->pluck('id')->toArray()))>
                        {{ $symbolGroup->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="input-area relative">
            <label for="forex_schemas" class="form-label">{{ __('Select Forex Schemas') }}</label>
            <select name="forex_schemas[]" class="select2 form-control w-full" multiple="multiple">
                @foreach($allForexSchemas as $id => $title)
                    <option value="{{ $id }}" @selected(in_array($id, $rebateRule->forexSchemas->pluck('id')->toArray()))>
                        {{ $title }}
                    </option>
                @endforeach
            </select>
        </div>

        {{--        <div class="input-area relative">--}}
{{--            <label for="ib_groups" class="form-label">{{ __('Select IB Groups') }}</label>--}}
{{--            <select name="ib_groups[]" class="select2 form-control w-full" multiple="multiple">--}}
{{--                @foreach($allIbGroups as $id => $name)--}}
{{--                    <option value="{{ $id }}" @if(in_array($id, $rebateRule->ibGroups->pluck('id')->toArray())) selected @endif>--}}
{{--                        {{ $name }}--}}
{{--                    </option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--            <div class="invalid-feedback" id="ib-groups-error" style="display: none;"></div>--}}
{{--        </div>--}}

        <div class="input-area relative">
            <label for="rule_type_id" class="form-label">{{ __('Rule Type:') }}</label>
            <select name="rule_type_id" class="form-control">
                <option value="">----</option>
                <option value="1" {{ $rebateRule->rule_type_id == 1 ? 'selected' : '' }}>Per Lot</option>
            </select>
            <div class="invalid-feedback" id="rule-type-id-error" style="display: none;"></div>
        </div>
        <div class="input-area relative">
            <label for="rebate_amount" class="form-label">{{ __('Rebate Amount:') }}</label>
            <input type="text" name="rebate_amount" class="form-control mb-0" placeholder="$55.00" id="rebate_amount" value="{{ $rebateRule->rebate_amount }}" />
            <div class="invalid-feedback" id="rebate-amount-error" style="display: none;"></div>
        </div>
        <div class="input-area relative">
            <label for="per_lot" class="form-label">{{ __('Per Lot:') }}</label>
            <input type="text" name="per_lot" class="form-control mb-0" placeholder="1" id="per_lot" value="{{ $rebateRule->per_lot }}" />
            <div class="invalid-feedback" id="per-lot-error" style="display: none;"></div>
        </div>
        <div class="input-area relative">
            <div class="flex items-center space-x-7 flex-wrap">
                <label class="form-label !w-auto pt-0">
                    {{ __('Status:') }}
                </label>
                <div class="form-switch ps-0">
                    <input type="hidden" value="0" name="status">
                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                        <input type="checkbox" name="status" value="1" class="sr-only peer" id="status" {{ $rebateRule->status ? 'checked' : '' }}>
                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                    </label>
                </div>
                <div class="invalid-feedback" id="status-error" style="display: none;"></div>
            </div>
        </div>
    </div>
    <div class="action-btns text-right mt-10">
        <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
            {{ __('Update Rebate Rule') }}
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
