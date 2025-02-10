<form action="{{ route('admin.ticket.label.update',$label->id) }}" method="post">
    @method('put')
    @csrf
    <div class="space-y-5">
        <div class="input-area">
            <label class="form-label" for="">{{ __('Type Title:') }}</label>
            <input type="text" name="name" value="{{ old('name', $label->name) }}" class="form-control" required/>
        </div>
        <div class="input-area">
            <div class="flex items-center space-x-7 flex-wrap">
                <label class="form-label !w-auto pt-0 !mb-0">
                    {{ __('Visible') }}
                </label>
                <div class="form-switch ps-0" style="line-height:0;">
                    <input type="hidden" value="0" name="is_visible">
                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                        <input type="checkbox" name="is_visible" value="1" @checked(old('is_visible', true)) class="sr-only peer">
                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="action-btns text-right mt-10">
        <button type="submit" href="" class="btn btn-dark inline-flex items-center justify-center mr-2">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
            {{ __('Save Changes') }}
        </button>
        <a href="#" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
            {{ __('Close') }}
        </a>
    </div>
</form>
