<div class="profiel-wrap px-[35px] pb-10 pt-10 rounded-lg bg-white dark:bg-secondary lg:space-y-0 space-y-6 relative z-[1]">
    <div class="customer-profile-cover absolute left-0 top-0 h-[115px] w-full z-[-1] rounded-t-lg"
         style="background-image: url('https://cdn.brokeret.com/crm-assets/staff-image/h1.png')">
    </div>
    <div class="profile-box">
        <div
            class="h-[140px] w-[140px] ml-auto mr-auto mb-4 rounded-full ring-4 ring-slate-100 dark:ring-slate-100 relative bg-slate-300 dark:bg-body dark:text-white text-slate-900 flex flex-col items-center justify-center">
            @if(null != $user->avatar)
                <img
                    class="w-full h-full object-cover rounded-full"
                    src="{{asset($user->avatar)}}"
                    alt="{{$user->first_name}}"
                />
            @else
                <span class="text-4xl">{{$user->first_name[0] .$user->last_name[0] }}</span>
            @endif
        </div>
        <div class="text-center">
            <div class="text-2xl font-medium text-slate-900 dark:text-slate-200 mb-[3px]">
                {{$user->first_name .' '. $user->last_name}}
            </div>
            <div class="text-sm font-light text-slate-600 dark:text-slate-400">
                {{ucwords($user->city)}}@if($user->city != ''), @endif{{ $user->country }}
            </div>
            <div class="text-sm font-light text-slate-600 dark:text-slate-400 my-5">
                <span class="font-medium">
                    {{ __('Member since: ') }}
                </span>
                {{ carbonInstance($user->created_at)->toDayDateTimeString() }}
            </div>
        </div>
        <div class="flex justify-center space-x-3 rtl:space-x-reverse mb-5">
            @can('customer-mail-send')
                <span type="button" data-bs-toggle="modal" data-bs-target="#sendEmail">
                    <a href="javascript:void(0);" class="toolTip onTop action-btn dark:text-slate-300"
                       data-tippy-theme="dark" data-tippy-content="Send Email">
                        <iconify-icon icon="lucide:mail"></iconify-icon>
                    </a>
                </span>
            @endcan
            @can('customer-login')
                <a href="{{ route('admin.user.login',$user->id) }}" target="_blank"
                   class="toolTip onTop action-btn dark:text-slate-300" data-tippy-theme="dark" data-tippy-content="Login As User">
                    <iconify-icon icon="lucide:user-plus"></iconify-icon>
                </a>
            @endcan
            @can('customer-balance-add-or-subtract')
                <span data-bs-toggle="modal" data-bs-target="#addSubBal">
                    <a href="javascript:void(0);" type="button" class="toolTip onTop action-btn dark:text-slate-300"
                       data-tippy-theme="dark" data-tippy-content="Add Funds">
                        <iconify-icon icon="lucide:wallet"></iconify-icon>
                    </a>
                </span>
            @endcan
            <span data-bs-toggle="modal" data-bs-target="#addSubBonus">
                <a href="javascript:void(0);" type="button" class="toolTip onTop action-btn dark:text-slate-300"
                   data-tippy-theme="dark" data-tippy-content="Add Bonus">
                    <iconify-icon icon="lucide:credit-card"></iconify-icon>
                </a>
            </span>
            {{--@can('Delete User')--}}
            <span data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal">
                <a href="javascript:void(0);" type="button" class="toolTip onTop action-btn dark:text-slate-300"
                   data-tippy-theme="dark" data-tippy-content="Delete User">
                    <iconify-icon icon="lucide:user-minus"></iconify-icon>
                </a>
            </span>
        </div>
        <ul class="space-y-5 mb-4">
            <li class="flex justify-between text-xs text-slate-600 dark:text-slate-300">
                <span>{{ __('Customer Group: ') }}</span>
                @if($user->customerGroups->isNotEmpty())
                    @foreach($user->customerGroups as $group)
                        <span>{{ $group->name }}</span>
                    @endforeach
                @else
                    <span>{{ 'N/A' }}</span>
                @endif
            </li>
            <li class="flex justify-between text-xs text-slate-600 dark:text-slate-300">
                <span>{{ __('Risk Profile:') }}</span> <!-- Added colon here -->
                <span class="flex items-center gap-2">
                    @if($user->riskProfileTags->isEmpty())
                        {{ __('N/A') }}
                    @else
                        {{ $user->riskProfileTags->pluck('name')->implode(', ') }}
                    @endif
                </span>
            </li>

            <li class="flex justify-between text-xs text-slate-600 dark:text-slate-300">
                <span>{{ __('KYC Level:') }}</span>
                <span>
        @php
            $displayName = 'N/A';

            if(isset($user->kyc) && $user->kyc > 0) {
                // Determine the appropriate KycLevel based on the user's KYC status
                if ($user->kyc == 1) {
                    // If KYC is 1, fetch the name from KycLevel where id == 1
                    $kycLevel = \App\Models\KycLevel::where('id', 1)->first();
                } elseif (in_array($user->kyc, [2, 3, 4])) {
                    // If KYC is 2, 3, or 4, fetch the name from KycLevel where id == 2
                    $kycLevel = \App\Models\KycLevel::where('id', 2)->first();
                } elseif (in_array($user->kyc, [5, 6, 7])) {
                    // If KYC is 5, 6, or 7, fetch the name from KycLevel where id == 3
                    $kycLevel = \App\Models\KycLevel::where('id', 3)->first();
                }

                // If we found a matching KycLevel
                if (isset($kycLevel)) {
                    if (in_array($user->kyc, [1, 4, 7])) {
                        // Only show the KycLevel->name for kyc == 1, 4, or 7
                        $displayName = $kycLevel->name;
                    } else {
                        // Get the KYCStatus enum name
                        $kycStatus = App\Enums\KYCStatus::from($user->kyc)->name;
                        $kycStatusFormatted = ucwords(str_replace('_', ' ', strtolower($kycStatus)));
                        // Show both KycLevel->name and KYCStatus for other values
                        $displayName = $kycLevel->name . ' - ' . $kycStatusFormatted;
                    }
                } else {
                    // Fallback to just showing the KYCStatus if no KycLevel is found
                    $kycStatus = App\Enums\KYCStatus::from($user->kyc)->name;
                    $displayName = ucwords(str_replace('_', ' ', strtolower($kycStatus)));
                }
            }
        @endphp
                    {{ $displayName }}
    </span>
            </li>

        </ul>
        <div
            class="flex items-center justify-around border-t border-b border-slate-100 dark:border-slate-700 py-4 mb-5">
            <div class="text-center">
                <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                    {{ __('Current Balance') }}
                </div>
                <div class="text-slate-900 dark:text-white text-xl font-medium">
                    {{ setting('currency_symbol','global') . $user->totalForexBalance() }}
                </div>
            </div>
            <div class="text-center">
                <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                    {{ __('Current Equity') }}
                </div>
                <div class="text-slate-900 dark:text-white text-xl font-medium">
                    {{ setting('currency_symbol','global') . $user->totalForexEquity() }}
                </div>
            </div>
        </div>
        <form action="{{route('admin.user.status-update',$user->id)}}" method="post" class="space-y-5">
            @csrf
            <div class="input-area flex items-center justify-between">
                <h5 class="form-label">{{ __('Account Status') }}</h5>
                <div class="form-switch ps-0">
                    <input
                        class="form-check-input"
                        type="hidden"
                        value="0"
                        name="status"
                    />
                    <label
                        class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                        <input
                            type="checkbox"
                            name="status"
                            value="1"
                            @if($user->status) checked @endif
                            class="sr-only peer"
                        />
                        <span
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                    </label>
                </div>
            </div>
            <div class="input-area flex items-center justify-between">
                <h5 class="form-label">{{ __('Multi IB') }}</h5>
                <div class="form-switch ps-0">
                    <input
                        class="form-check-input"
                        type="hidden"
                        value="0"
                        name="is_multi_ib"
                    />
                    <label
                        class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                        <input
                            type="checkbox"
                            name="is_multi_ib"
                            value="1"
                            @if($user->is_multi_ib) checked @endif
                            class="sr-only peer"
                        />
                        <span
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                    </label>
                </div>
            </div>
            <div class="input-area flex items-center justify-between">
                <h5 class="form-label">{{ __('Email Verification') }}</h5>
                <div class="form-switch ps-0">
                    <input
                        class="form-check-input"
                        type="hidden"
                        value="0"
                        name="email_verified"
                    />
                    <label
                        class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                        <input
                            type="checkbox"
                            name="email_verified"
                            value="1"
                            @if($user->email_verified_at != null) checked @endif
                            class="sr-only peer"
                        />
                        <span
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                    </label>
                </div>
            </div>
            <div class="input-area flex items-center justify-between">
                <h5 class="form-label">{{ __('2FA Verification') }}</h5>
                <div class="form-switch ps-0">
                    <input
                        class="form-check-input"
                        type="hidden"
                        value="0"
                        name="two_fa"
                    />
                    <label
                        class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                        <input
                            type="checkbox"
                            name="two_fa"
                            value="1"
                            @if($user->two_fa) checked @endif
                            class="sr-only peer"
                        />
                        <span
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                    </label>
                </div>
            </div>
            <div class="input-area flex items-center justify-between">
                <h5 class="form-label">{{ __('Fund Deposit') }}</h5>
                <div class="form-switch ps-0">
                    <input
                        class="form-check-input"
                        type="hidden"
                        value="0"
                        name="deposit_status"
                    />
                    <label
                        class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                        <input
                            type="checkbox"
                            name="deposit_status"
                            value="1"
                            @if($user->deposit_status) checked @endif
                            class="sr-only peer"
                        />
                        <span
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                    </label>
                </div>
            </div>
            <div class="input-area flex items-center justify-between">
                <h5 class="form-label">{{ __('Fund Withdraw') }}</h5>
                <div class="form-switch ps-0">
                    <input
                        class="form-check-input"
                        type="hidden"
                        value="0"
                        name="withdraw_status"
                    />
                    <label
                        class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                        <input
                            type="checkbox"
                            name="withdraw_status"
                            value="1"
                            @if($user->withdraw_status) checked @endif
                            class="sr-only peer"
                        />
                        <span
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                    </label>
                </div>
            </div>
            <div class="input-area flex items-center justify-between">
                <h5 class="form-label">{{ __('Fund Transfer') }}</h5>
                <div class="form-switch ps-0">
                    <input
                        class="form-check-input"
                        id="trans1"
                        type="hidden"
                        value="0"
                        name="transfer_status"
                    />
                    <label
                        class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                        <input
                            type="checkbox"
                            name="transfer_status"
                            value="1"
                            @if($user->transfer_status) checked @endif
                            class="sr-only peer"
                        />
                        <span
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                    </label>
                </div>
            </div>
            <div class="input-area profile-card-single">
                <h5 class="form-label">{{ __('Account Limit (Max)') }}</h5>
                <input type="text" name="account_limit" value="{{$user->account_limit}}"
                       oninput="this.value = validateDouble(this.value)"
                       class="form-control">
            </div>

            <div class="input-area">
                <button type="submit" class="btn btn-dark inline-flex items-center justify-center w-full">
                    {{ __('Save Changes') }}
                </button>
            </div>
        </form>
    </div>
</div>
