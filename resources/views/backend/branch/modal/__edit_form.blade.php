<form action="{{ route('admin.branches.update', $branch->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body relative p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Branch Name -->
            <div class="input-area">
                <label for="name" class="form-label">{{ __('Branch Name') }} <span
                        class="text-red-500">*</span></label>
                <input name="name" type="text" class="form-control" value="{{ $branch->name }}"
                    placeholder="{{ __('Enter branch name') }}" required>
            </div>

            <!-- Branch Code -->
            <div class="input-area">
                <label for="code" class="form-label">{{ __('Branch Code') }} <span
                        class="text-red-500">*</span></label>
                <input name="code" type="text" class="form-control" value="{{ $branch->code }}"
                    placeholder="{{ __('Enter branch code (e.g., UAE, USA)') }}" maxlength="10" required>
                <small class="text-slate-500">{{ __('Unique identifier for the branch (max 10 characters)') }}</small>
            </div>

            <!-- Status -->
            <div class="input-area md:col-span-2">
                <div class="flex items-center space-x-7 flex-wrap">
                    <label class="form-label !w-auto pt-0">
                        {{ __('Status') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="form-switch ps-0">
                        <input type="hidden" value="0" name="status">
                        <label
                            class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                            <input type="checkbox" name="status" value="1" class="sr-only peer form-toggle"
                                {{ $branch->status ? 'checked' : '' }}>
                            <span
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div
        class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-t-slate-200 rounded-b-md">
        <button type="button"
            class="btn inline-flex justify-center text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 btn-sm"
            data-bs-dismiss="modal">
            {{ __('Cancel') }}
        </button>
        <button type="submit" class="btn inline-flex justify-center btn-dark btn-sm">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
            {{ __('Update Branch') }}
        </button>
    </div>
</form>
