<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="addSubBal"
    tabindex="-1"
    aria-labelledby="addSubBalModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize" id="addSubBalLabel">
                    {{ __('Balance Add or Subtract') }}
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
            <div class="modal-body p-6 pt-0">
                <form action="{{ route('admin.user.balance-update',$user->id) }}" method="post" class="space-y-5">
                    @csrf
                    <input type="hidden" name="target_type" id="selectedAccountType_balance" value="" >
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
                        <select class="form-control w-100" name="target_id" id="tradingAccount_balance">
                            <option value="">Select Account</option>
                                @foreach($realForexAccounts as $forexAccount)
                                    <option value="{{$forexAccount->login}}" data-type="forex">
                                        {{ $forexAccount->login }} - {{ $forexAccount->account_name }}
                                        ({{ get_mt5_account_equity($forexAccount->login) }} {{$forexAccount->currency}})
                                    </option>
                                @endforeach
                                @if($user->ib_status == \App\Enums\IBStatus::APPROVED && isset($user->ib_login))
                                    <option value="{{ $user->ib_login }}" data-type="forex"
                                            data-type="forex">{{ $user->ib_login }}
                                        - {{ __('IB') }} ({{ $user->ib_balance }} {{$currency}})
                                    </option>
                                @endif
                        </select>

                    </div>
                    <div class="input-area">
                        <div class="joint-input relative">
                            <span class="absolute text-sm left-0 top-1/2 -translate-y-1/2 h-full border-r border-r-slate-200 dark:border-r-slate-700 dark:text-slate-300 flex items-center justify-center px-2">
                                {{ setting('site_currency','global') }}
                            </span>
                            <input type="text" name="amount" oninput="this.value = validateDouble(this.value)" class="form-control !pl-12">
                        </div>
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Comment Message') }}</label>
                        <textarea name="comment" class="form-control mb-0"
                                    placeholder="Comment Message" rows="6"></textarea>
                    </div>
                    <div class="input-area text-right">

                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Apply Now') }}
                        </button>
                    </div>
                </form>

                

            </div>
        </div>
    </div>
</div>
