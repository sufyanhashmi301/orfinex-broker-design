<form action="{{ route('admin.ib-group.update', $ibGroup->id) }}" method="post" class="space-y-4">
    @method('PUT')
    @csrf
    <div class="input-area">
        <label class="form-label" for="">
            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter a name for the IB group">
                {{ __('Name') }}
                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
            </span>
        </label>
        <input type="text" name="name" value="{{ old('name', $ibGroup->name) }}"
               class="form-control" placeholder="IB Group Name" required/>
    </div>
    <div class="input-area">
        <label for="" class="form-label">
            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Add a description for this group">
                {{ __('Details (Optional)') }}
                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
            </span>
        </label>
        <textarea name="desc" class="form-control summernote mb-0" rows="6" placeholder="Details">{!! old('desc', $ibGroup->desc) !!}</textarea>
        <input type="hidden" name="desc" value="{{ str_replace(['<', '>'], ['{', '}'], old('desc', $ibGroup->desc)) }}">
    </div>
    <div class="input-area">
        <label for="rebate_rule_id_edit" class="form-label">
            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select rebate rules to link with this group">
                {{ __('Attach Rebate Rule(s) (Optional)') }}
                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
            </span>
        </label>
        <select name="rebate_rule_id[]" id="rebate_rule_id_edit" class="form-control w-full h-9" multiple>
            @foreach($rebateRules as $rule)
                <option value="{{ $rule->id }}"
                        @if($ibGroup->rebateRules->contains('id', $rule->id)) selected @endif>
                    {{ $rule->title }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="input-area">
        <div class="flex items-center space-x-7 flex-wrap">
            <label class="form-label !w-auto pt-0 !mb-0">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enable or disable this IB group">
                    {{ __('Status') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <div class="form-switch ps-0" style="line-height: 0;">
                <input class="form-check-input" type="hidden" value="0" name="status">
                <label class="deposit-status relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-switch">
                    <input type="checkbox" name="status" value="1" class="sr-only peer" @if($ibGroup->status) checked @endif>
                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                </label>
            </div>
            
        <label class="form-label !w-auto pt-0 !mb-0 ml-4">
            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="When enabled, this IB Group can show Global category accounts from linked rebate rules only if 'Show Global Accounts with Ib Rebate Rules' is enabled. ">
                {{ __('Global Account:') }}
                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
            </span>
        </label>
        <div class="form-switch ps-0" style="line-height: 0;">
            <input class="form-check-input" type="hidden" value="0" name="is_global_account">
            <label class="deposit-status relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-switch">
                <input type="checkbox" name="is_global_account" value="1" class="sr-only peer" 
                       @if($ibGroup->is_global_account) checked @endif>
                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
            </label>
        </div>
        @php $showGlobalWithIbRebateRules = setting('show_global_accounts_with_ib_rebate_rules', 'account_type_settings'); @endphp
        @if(!$showGlobalWithIbRebateRules)
        <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded p-2 mt-2">
            <strong>Show Global Accounts with Ib Rebate Rules setting is off in account type settings</strong> thats why if here in any ib group if global account active its not effective with that ib group no global account visible only universal globals are visible. <a href="{{ route('admin.settingsAccountType') }}" target="_blank" rel="noopener" class="underline">Open Account Type Settings</a>.
        </p>
        @endif
        </div>
    </div>
    <div class="input-area text-right">
        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
            {{ __('Save Changes') }}
        </button>
    </div>
</form>
