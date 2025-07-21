<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
     id="resetPasswordModal"
     tabindex="-1"
     aria-labelledby="resetPasswordModalLabel"
     aria-hidden="true"
>
    <div class="modal-dialog modal-md top-1/2 !-translate-y-1/2 relative max-w-xl w-full pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="relative rounded-lg shadow">
                <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-700">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Reset Password for') }} <span id="resetUserName">{{ $name ?? ''}}</span>
                    </h3>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6">
                    <form action="{{route('admin.user.reset-password')}}" method="post" id="reset-password-form">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $id ?? 0 }}" id="resetUserId">
                        <div class="space-y-5">
                            <div class="input-area relative">
                                <label for="resetUserEmail" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="User's email address for reset the password">
                                        {{ __('Email') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <input
                                    type="text"
                                    name="password"
                                    id="resetUserEmail"
                                    class="form-control"
                                    readonly
                                />
                            </div>
                            <div class="site-input-area relative">
                                <label for="generatedPassword" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Generated password for the user">
                                        {{ __('Generated Password') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <input
                                    type="text"
                                    name="password"
                                    id="generatedPassword"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="input-area text-right mt-10">
                            <button type="submit" class="btn inline-flex justify-center btn-dark mr-2">
                                <span class="flex items-center">
                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                    <span>{{ __('Update Password') }}</span>
                                </span>
                            </button>
                            <a href="#" data-bs-dismiss="modal" class="btn inline-flex justify-center btn-danger">
                                <span class="flex items-center">
                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                    <span>{{ __('Close') }}</span>
                                </span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
