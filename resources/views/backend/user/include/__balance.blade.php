<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="addSubBal"
    tabindex="-1"
    aria-labelledby="addSubBalModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative max-w-xl w-full pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <div>
                    <h3 class="text-xl font-medium dark:text-white capitalize" id="addSubBonusLabel">
                    {{ __('Balance Add or Subtract') }}
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400">
                        {{ __('Add or subtract balance from the user') }}
                    </p>
                </div>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body p-6 pt-0">
                <form action="{{ route('admin.user.balance-update',$user->id) }}" method="post">
                    @csrf
                    <input type="hidden" name="target_type" id="selectedAccountType_balance" value="" >
                    <div class="space-y-5">
                        <div class="input-area">
                            <div class="switch-field flex overflow-hidden">
                                <input
                                    type="radio"
                                    id="addMon"
                                    name="type"
                                    value="add"
                                    checked
                                />
                                <label for="addMon" class="dark:text-white">{{ __('Add') }}</label>
                                <input
                                    type="radio"
                                    id="addMon2"
                                    name="type"
                                    value="subtract"
                                />
                                <label for="addMon2" class="dark:text-white">{{ __('Subtract') }}</label>
                            </div>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the account to add or subtract balance from">
                                    {{ __('Select Account') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <select class="select2 form-control w-100" name="target_id" id="tradingAccount_balance">
                                <option value="">Select Account</option>
                                @foreach($realForexAccounts as $forexAccount)
                                    <option value="{{ $forexAccount->login }}" data-type="forex">
                                        {{ $forexAccount->login }} - {{ $forexAccount->account_name }} ({{ get_mt5_account_equity($forexAccount->login) }} {{ $forexAccount->currency }})
                                    </option>
                                @endforeach
                                @foreach(get_all_wallets($user->id) as $wallet)
                                    <option value="{{ $wallet->wallet_id }}" data-type="wallet">
                                        {{ $wallet->wallet_id }} - {{ w2n($wallet->balance) }} ({{ $wallet->amount }} {{$currency}})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the amount to add or subtract from the account">
                                    {{ __('Amount') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <div class="joint-input relative">
                                <span class="absolute text-sm left-0 top-1/2 -translate-y-1/2 h-full border-r border-r-slate-200 dark:border-r-slate-700 dark:text-slate-300 flex items-center justify-center px-2">
                                    {{ setting('site_currency','global') }}
                                </span>
                                <input type="text" name="amount" oninput="this.value = validateDouble(this.value)" class="form-control !pl-12">
                            </div>
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter a comment message for the transaction">
                                    {{ __('Comment Message') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <textarea name="comment" class="form-control mb-0"
                                        placeholder="Comment Message" rows="6"></textarea>
                        </div>
                    </div>
                    <div class="input-area text-right mt-10">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Apply Now') }}
                        </button>
                        <a href="#" data-bs-dismiss="modal" class="btn inline-flex justify-center btn-danger">
                            <span class="flex items-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Cancel') }}
                            </span>
                        </a>
                    </div>
                </form>



            </div>
        </div>
    </div>
</div>
