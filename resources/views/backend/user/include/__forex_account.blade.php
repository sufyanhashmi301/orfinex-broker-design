<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="addForexAccount"
    tabindex="-1"
    aria-labelledby="addForexAccountModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize" id="addForexAccountLabel">
                    {{ __('Add New Account') }}
                </h3>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body p-6 pt-0">
                <form action="" method="post">
                    <div class="space-y-5">
                        <div class="input-area">
                            <label for="" class="form-label">
                                {{ __('Choose Leverage') }}
                            </label>
                            <select name="" class="select2 form-control w-full">
                                <option value="100">{{ __('100') }}</option>
                            </select>
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                {{ __('Nickname for Account' )}}
                            </label>
                            <input type="text" name="" class="form-control" placeholder="Enter Nickname">
                        </div>
                        <div class="input-area">
                            <div class="flex items-center space-x-5 flex-wrap">
                                <div class="form-switch ps-0" style="line-height:0;">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-checkbox" data-target="#live-islamic-group">
                                        <input type="checkbox" name="" value="1" class="sr-only peer">
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                                <label class="form-label pt-0 !mb-0" style="width:auto">
                                    {{ __('Request Swap-Free Option (Islamic Account)') }}
                                </label>
                            </div>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                {{ __('Main Password:') }}
                            </label>
                            <input type="text" class="form-control" placeholder="Enter Main Password" aria-label="Main Password" name="main_password" id="enter-main-password" aria-describedby="basic-addon1" required>
                            <ul>
                                <li class="text-xs font-Inter font-normal text-danger-500 mt-2" id="length-check-main">
                                    Use from 8 to 15 characters
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger-500 mt-1" id="letters-check-main">
                                    Use both uppercase and lowercase letters
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger-500 mt-1" id="number-check-main">
                                    At least one number
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger-500 mt-1" id="special-check-main">
                                    At least one special character(!@#$%^&*(),-.?":{}|<>)
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="action-btns mt-10">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Create Account') }}
                        </button>
                        <button type="button" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            Cancel
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
