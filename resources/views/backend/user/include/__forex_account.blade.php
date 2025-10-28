{{-- Modal for add Forex Account --}}
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
     id="addForexAccount"
     tabindex="-1"
     aria-labelledby="addForexAccountModalLabel"
     aria-hidden="true"
>
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative max-w-xl w-full pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize" id="addForexAccountLabel">
                    {{ __('Add New Account') }}
                </h3>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body p-6 pt-0">
                <form action="{{route('admin.forex-account-create')}}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    @csrf
                    <input type="hidden" name="account_type" id="account-type" value="real">
                    <div class="space-y-5">
                        <div class="input-area">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the account type to create">
                                    {{ __('Select Account Type') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <select class="form-control py-2 h-[48px] select2" aria-label="Default select example" id="select-schema" name="schema_id" required>
                                <option value="">
                                    {{__('Select')}}
                                </option>
                                @foreach($schemas as $plan)
                                    <option value="{{$plan->id}}"
                                            data-is-real-islamic="{{$plan->is_real_islamic}}"
                                            data-is-demo-islamic="{{$plan->is_demo_islamic}}"
                                            data-is-update-investor-password="{{$plan->is_update_investor_password}}"
                                            data-first-min-deposit="{{$plan->first_min_deposit}}"
                                            data-leverage="{{$plan->leverage}}">
                                        {{$plan->title}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area">
                            <div class="flex items-center space-x-5 flex-wrap">
                                <div class="form-switch ps-0" style="line-height:0;">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-checkbox" data-target="#live-islamic-group">
                                        <input type="checkbox" name="is_islamic" value="1" class="sr-only peer" id="islamic-checkbox">
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                                <label class="form-label pt-0 !mb-0" style="width:auto">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Request swap-free option for Islamic account">
                                        {{ __('Request Swap-Free Option (Islamic Account)') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the leverage for the account">
                                    {{ __('Select Leverage') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <select class="form-control py-2 h-[48px]" aria-label="Default select example" id="select-leverage" name="leverage" required>
                                <option value="">{{ __('Select Leverage') }}</option>
                            </select>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the nickname for the account">
                                    {{ __('Account Nickname') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control py-2 h-[48px]" placeholder="{{ __('Enter Nickname') }}" aria-label="Nickname" name="account_name" aria-describedby="basic-addon1" required>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the main password for the account">
                                    {{ __('Main Password') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control py-2 h-[48px]" placeholder="{{ __('Enter Main Password') }}" aria-label="Main Password" name="main_password" id="enter-main-password" aria-describedby="basic-addon1" required>
                            <ul>
                                <li class="text-xs font-Inter font-normal text-danger mt-2" id="length-check-admin-main">
                                    {{ __('Use from 8 to 20 characters') }}
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger mt-1" id="letters-check-admin-main">
                                    {{ __('Use both uppercase and lowercase letters') }}
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger mt-1" id="number-check-admin-main">
                                    {{ __('At least one number') }}
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger mt-1" id="special-check-admin-main">
                                    {{ __('At least one special character (!@#$%^&*(),-.?":{}|<>)') }}
                                </li>
                            </ul>
                        </div>
                        
                        <div class="input-area" id="investor-password-wrapper" style="display:none;">
                            <label class="form-label" for="enter-investor-password">
                                {{ __('Investor Password:') }}
                            </label>
                            <input type="text" class="form-control py-2 h-[48px]"
                                   placeholder="{{ __('Enter Investor Password') }}" aria-label="{{ __('Investor Password') }}"
                                   name="investor_password" id="enter-investor-password" aria-describedby="basic-addon1">
                            <ul>
                                <li class="text-xs font-Inter font-normal text-danger mt-2" id="length-check-admin-invest">
                                    {{ __('Use from 8 to 20 characters') }}
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger mt-1" id="letters-check-admin-invest">
                                    {{ __('Use both uppercase and lowercase letters') }}
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger mt-1" id="number-check-admin-invest">
                                    {{ __('At least one number') }}
                                </li>
                                <li class="text-xs font-Inter font-normal text-danger mt-1" id="special-check-admin-invest">
                                    {{ __('At least one special character(!@#$%&*():{}|<>)') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="text-right mt-10">
                        <button type="submit" class="btn inline-flex justify-center btn-dark mr-2" id="create-forex-account">
                            <span class="flex items-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Create Account') }}
                            </span>
                        </button>
                        <button type="button" class="btn inline-flex justify-center btn-danger" data-bs-dismiss="modal">
                            <span class="flex items-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Cancel') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
