<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="newWithdrawAccountModal" tabindex="-1" aria-labelledby="newWithdrawAccountModal">
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-center justify-between p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Create Withdraw Account') }}
                    </h3>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="max-h-[calc(100vh-200px)] overflow-y-auto p-6 pt-0">
                    <form action="{{ route('admin.withdraw.account.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="userId__input" name="user_id">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5 selectMethodRow">
                            <div class="input-area relative md:col-span-2 lg:col-span-2 selectMethodCol">
                                <label for="exampleFormControlInput1" class="form-label">
                                    {{ __('Choice Method:') }}
                                </label>
                                <div class="input-area">
                                    <select name="withdraw_method_id" id="selectMethod" class="select2 form-control">
                                        <option selected>{{ __('Select Method') }}</option>
                                        @foreach($withdrawMethods as $raw)
                                            <option value="{{ $raw->id }}">{{ $raw->name }}
                                                ({{ ucwords($raw->type) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Create Account') }}
                            </button>
                            <a href="#" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Close') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
