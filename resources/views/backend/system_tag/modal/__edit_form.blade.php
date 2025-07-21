<form action="{{ route('admin.system-tag.update', $systemTag->id) }}" method="post">
    @method('PUT')
    @csrf
    <div class="space-y-5">
        <div class="input-area">
            <label class="form-label" for="">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the system tag name">
                    {{ __('Name') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <input type="text" name="name" value="{{ old('name', $systemTag->name) }}"
                class="form-control" placeholder="System Tag Name" required/>
        </div>
        <div class="input-area">
            <label for="" class="form-label">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Optional description for the tag">
                    {{ __('Details (Optional)') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <textarea name="desc" class="form-control mb-0" rows="6" placeholder="Details">{{ old('desc', $systemTag->desc) }}</textarea>
        </div>
        <div class="input-area">
            <div class="flex items-center space-x-7 flex-wrap">
                <label class="form-label !w-auto pt-0 !mb-0">
                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enable or disable this tag">
                        {{ __('Status') }}
                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                    </span>
                </label>
                <div class="form-switch ps-0" style="line-height: 0;">
                    <input class="form-check-input" type="hidden" value="0" name="status">
                    <label class="deposit-status relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-switch">
                        <input type="checkbox" name="status" value="1" class="sr-only peer" @if($systemTag->status) checked @endif>
                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="input-area text-right mt-10">
        <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
            {{ __('Save Changes') }}
        </button>
        <a href="" class="btn btn-danger inline-flex items-center justify-center" type="button" data-bs-dismiss="modal" aria-label="Close">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
            {{ __('Cancel') }}
        </a>
    </div>
</form>
