<div class="p-6 pt-0 max-h-[calc(100vh-200px)] overflow-y-auto">
    <form action="{{ route('admin.discounts.update', ['discount' => $discount->id]) }}" method="POST" id="editDiscountForm">
        @csrf
        @method('PUT')

        <div class="space-y-5">
            <!-- Code Name -->
            <div class="input-area !mt-0">
                <label for="code_name" class="form-label">{{ __('Code Name') }}</label>
                <input
                    type="text"
                    name="code_name"
                    id="edit_code_name"
                    class="form-control mb-0"
                    value="{{ $discount->code_name }}"
                    placeholder="Code Name"
                    required
                />
            </div>
            <div class="input-area">
                <label for="code_name" class="form-label">{{ __('Code') }}</label>
                <input
                    type="text"
                    name="code"
                    id="edit_code"
                    class="form-control mb-0"
                    value="{{ $discount->code }}"
                    placeholder="Code"
                    required
                />
            </div>

            <!-- Type Selection -->
            <div class="input-area">
                <label for="discount_type" class="form-label">{{ __('Type') }}</label>
                <select id="edit_discount_type" name="type" class="form-control w-100">
                    <option value="fixed" {{ $discount->type === 'fixed' ? 'selected' : '' }}>{{ __('Fixed') }}</option>
                    <option value="percentage" {{ $discount->type === 'percentage' ? 'selected' : '' }}>{{ __('Percentage') }}</option>
                </select>
            </div>

            <!-- Fixed Amount or Percentage Fields -->
            <div class="input-area">
                <div id="fixed_amount_field" class="discount-type {{ $discount->type === 'fixed' ? '' : 'hidden' }}">
                    <label for="fixed_amount" class="form-label">{{ __('Fixed Amount') }}</label>
                    <input type="text" name="fixed_amount" id="edit_fixed_amount" class="form-control mb-0" value="{{ $discount->fixed_amount }}" placeholder="Fixed Amount" />
                </div>

                <div id="percentage_field" class="discount-type {{ $discount->type === 'percentage' ? '' : 'hidden' }}">
                    <label for="percentage" class="form-label">{{ __('Percentage') }}</label>
                    <input type="text" name="percentage" id="edit_percentage" class="form-control mb-0" value="{{ $discount->percentage }}" placeholder="Percentage" />
                </div>
            </div>


    {{--        <!-- Applies To -->--}}
    {{--        <div class="input-area">--}}
    {{--            <label for="edit_applies_to" class="form-label">{{ __('Applies To') }}</label>--}}
    {{--            <select name="applied_to" id="edit_applies_to" class="form-control w-100">--}}
    {{--                <option value="all account type" {{ $discount->applied_to === 'all account type' ? 'selected' : '' }}>{{ __('All Account Type') }}</option>--}}
    {{--                <option value="challenge accounts only" {{ $discount->applied_to === 'challenge accounts only' ? 'selected' : '' }}>{{ __('Challenge Accounts Only') }}</option>--}}
    {{--                <option value="direct funded accounts only" {{ $discount->applied_to === 'direct funded accounts only' ? 'selected' : '' }}>{{ __('Direct Funded Accounts Only') }}</option>--}}
    {{--            </select>--}}
    {{--        </div>--}}

            <!-- Usage Limit -->
            <div class="input-area">
                <label class="form-label" for="edit_usage_limit">{{ __('Usage Limit') }}</label>
                <select name="usage_limit" id="edit_usage_limit" class="form-control w-100">
                    <option value="1" {{ $discount->usage_limit === 'unlimited' ? 'selected' : '' }}>{{ __('Unlimited') }}</option>
                    <option value="100" {{ $discount->usage_limit == '100' ? 'selected' : '' }}>{{ __('100 Uses') }}</option>
                    <option value="500" {{ $discount->usage_limit == '500' ? 'selected' : '' }}>{{ __('500 Uses') }}</option>
                </select>
            </div>

            <!-- Expires On -->
            <div class="input-area">
                <label class="form-label" for="edit_expire_at">{{ __('Expires On') }}</label>
                <input
                    type="text"
                    name="expire_at"
                    id="edit_expire_at"
                    class="form-control flatpickr flatpickr-input mb-0"
                    value="{{ \Carbon\Carbon::parse($discount->expire_at)->format('m/d/Y') }}"
                    required
                />
            </div>

            <!-- Status -->
            <div class="flex items-center space-x-7 flex-wrap">
                <label class="form-label !w-auto mb-0">{{ __('Status') }}</label>
                <div class="form-switch ps-0" style="line-height: 0">
                    <input type="hidden" value="0" name="status">
                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                        <input type="checkbox" name="status" value="1" class="sr-only peer" {{ $discount->status == 1 ? 'checked' : '' }}>
                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="action-btns text-right mt-10">
            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                {{ __('Update') }}
            </button>
            <a href="#" class="btn btn-outline-dark inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                {{ __('Close') }}
            </a>
        </div>
    </form>
</div>
