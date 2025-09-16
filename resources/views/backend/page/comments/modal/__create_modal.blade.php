<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="commentCreateModal" tabindex="-1" aria-labelledby="commentCreateModal" aria-hidden="true">
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-700">
                <div>
                    <h3 class="text-xl font-medium dark:text-white capitalize mb-1">{{ __('Add Comment') }}</h3>
                    <p class="text-slate-600 dark:text-slate-200">{{ __('Create a new comment entry') }}</p>
                </div>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">{{ __('Close modal') }}</span>
                </button>
            </div>
            <div class="modal-body p-6">
                <form action="{{ route('admin.page.comments.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="input-area">
                            <label class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __('A short label to identify this comment template') }}">
                                    {{ __('Title') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" name="title" class="form-control h-[42px]" placeholder="{{ __('Enter title') }}" required>
                        </div>
                        <div class="input-area">
                            <label class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __('Where this comment will be available (e.g., Withdraw Amount, KYC)') }}">
                                    {{ __('Type') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <select name="type" class="form-control h-[42px]" required>
                                @foreach(\App\Enums\CommentType::cases() as $type)
                                    <option value="{{ $type->value }}">{{ __($type->label()) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area md:col-span-2">
                            <label class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __('The text inserted when this title is selected in a modal') }}">
                                    {{ __('Description') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <textarea name="description" class="form-control" rows="5" placeholder="{{ __('Enter description') }}"></textarea>
                            <p class="text-xs text-slate-400 mt-1">{{ __('This text will be inserted automatically when the title is selected in supported modals. You can still edit it before submitting.') }}</p>
                        </div>
                        <div class="input-area md:col-span-2 border border-slate-100 dark:border-slate-700 rounded px-3 py-2">
                            <input class="form-check-input" type="hidden" value="0" name="status"/>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center gap-1 text-slate-700 dark:text-slate-300 shift-Away" data-tippy-content="{{ __('Enable to use this comment; disable to hide it') }}">
                                    <span class="font-medium">{{ __('Status') }}</span>
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                    <input type="checkbox" name="status" value="1" class="sr-only peer" checked>
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="action-btns text-right mt-10">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Create') }}
                        </button>
                        <button type="button" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


