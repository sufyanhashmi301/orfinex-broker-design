@extends('frontend::layouts.user')
@section('title')
    {{ __('Internal Transfer') }}
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            @yield('title')
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-y-12 lg:gap-y-5 lg:gap-x-12" x-data="sendMoneyForm()" x-init="init()">
        <div class="col-span-12 lg:col-span-7">
            <div class="lg:max-w-xl">
                <form @submit="validateBeforeSubmit($event)" action="{{ route('user.send-money.internal-now') }}" method="post">
                    @csrf
                    <input type="hidden" name="target_type" id="selectedAccountType" value="">
                    <input type="hidden" name="receiver_type" id="selectedReceiverAccountType" value="">
                    
                    <div class="space-y-5">
                        <x-frontend::forms.select-field
                            fieldId="tradingAccount"
                            fieldLabel="{{ __('Account From') }}"
                            fieldName="target_id"
                            @change="handleFromChange($event.target)"
                            :placeholder="__('--Select Account--')"
                            fieldRequired="true">
                            @foreach($forexAccounts as $forexAccount)
                                <option value="{{ the_hash($forexAccount->login) }}" data-type="forex">
                                    {{ $forexAccount->login }} - {{ $forexAccount->account_name }} ({{ get_mt5_account_equity($forexAccount->login) }} {{$currency}})
                                </option>
                            @endforeach
                            @include('frontend::wallets.include.__all-wallets-dropdown', ['target_id_name' => 'target_id'])
                        </x-frontend::forms.select-field>
                        
                        <x-frontend::forms.select-field
                            fieldId="receiverTradingAccount"
                            fieldLabel="{{ __('Account To') }}"
                            fieldName="receiver_account"
                            @change="handleToChange($event.target)"
                            :placeholder="__('--Select Account--')"
                            fieldRequired="true">
                            @foreach($forexAccounts as $forexAccount)
                                <option value="{{ the_hash($forexAccount->login) }}" data-type="forex">
                                    {{ $forexAccount->login }} - {{ $forexAccount->account_name }}({{ get_mt5_account_equity($forexAccount->login) }} {{$currency}})
                                </option>
                            @endforeach
                            @include('frontend::wallets.include.__specific-wallet-dropdown', ['target_id_name' => 'receiver_account', 'wallet_type' => \App\Enums\AccountBalanceType::MAIN])
                        </x-frontend::forms.select-field>
                        
                        <div class="input-area">
                            <x-frontend::forms.label 
                                fieldId="amount" 
                                fieldLabel="{{ __('Enter Amount') }}" 
                                fieldRequired="true"
                            />
                            <div class="relative">
                                <x-frontend::forms.input 
                                    x-model="amount"
                                    @input="amount = validateDouble($event.target.value)"
                                    fieldId="amount"
                                    fieldName="amount"
                                    required
                                    class="pr-16"
                                />
                                <span class="absolute top-1/2 right-0 inline-flex -translate-y-1/2 cursor-pointer items-center gap-1 border-l border-gray-200 py-3 pr-3 pl-3.5 text-sm font-medium text-gray-700 dark:border-gray-800 dark:text-gray-400" id="basic-addon1">
                                    {{ $currency }}
                                </span>
                            </div>
                            <div class="font-Inter text-xs text-brand-500 pt-2 inline-block">
                                {{ __('Minimum '). setting('internal_min_send','transfer_internal').' '.$currency.__(' and Maximum '). setting('internal_max_send','transfer_internal').' '.$currency }}
                            </div>
                        </div>

                        <x-frontend::forms.textarea-field
                            fieldId="note"
                            fieldLabel="{{ __('Transfer Note (Optional)') }}"
                            fieldName="note"
                            placeholder="{{ __('Transfer Note') }}"
                        />
                    </div>
                    <div class="mt-10">
                        <x-frontend::forms.button type="submit" class="w-full" size="md" variant="primary" icon="arrow-right" icon-position="right">
                            {{ __('Proceed to Payment') }}
                        </x-frontend::forms.button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-5">
            <div class="lg:border-l border-gray-200 dark:border-gray-800 lg:ps-5">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white/90 mb-3">
                        {{ __('Terms') }}
                    </h3>
                    <ul class="space-y-3.5">
                        <li class="flex gap-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Average payment time') }}</span>
                            <span id="" class="text-sm text-gray-700 dark:text-gray-400">
                                {{ __('1 hour') }}
                            </span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Fee') }}</span>
                            <span id="internal_send_charge" class="text-sm text-gray-700 dark:text-gray-400">
                                {{ __('No Fee') }}
                            </span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white/90 mb-3">
                        {{ __('FAQ') }}
                    </h3>
                    <ul class="space-y-1">
                        <li>
                            <x-frontend::text-link href="javascript:void(0)">
                                {{ __('How do I verify my account?') }}
                            </x-frontend::text-link>
                        </li>
                        <li>
                            <x-frontend::text-link href="javascript:void(0)">
                                {{ __('How do I deposit with EasyPaisa?') }}
                            </x-frontend::text-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')

    <script>
        function sendMoneyForm() {
            return {
                // Data bindings
                targetId: '',
                receiverId: '',
                amount: '',
                accountFromText: '',
                accountToText: '',
                accountFromType: '',
                accountToType: '',

                // Settings from backend
                charge: @json(setting('internal_send_charge','fee')),
                chargeType: @json(setting('internal_send_charge_type','fee')),
                currency: '{{ $currency }}',

                // Derived value
                get finalCharge() {
                    const amount = parseFloat(this.amount || 0);
                    if (this.chargeType === 'percentage') {
                        return ((amount * parseFloat(this.charge)) / 100).toFixed(2);
                    }
                    return parseFloat(this.charge).toFixed(2);
                },

                // On selecting "Account From"
                handleFromChange(el) {
                    const selected = el.options[el.selectedIndex];
                    this.accountFromText = selected.text.split(" - ")[0].trim();
                    this.accountFromType = selected.dataset.type || '';
                    this.targetId = selected.value;
                    document.getElementById('selectedAccountType').value = this.accountFromType;
                },

                // On selecting "Account To"
                handleToChange(el) {
                    const selected = el.options[el.selectedIndex];
                    this.accountToText = selected.text.split(" - ")[0].trim();
                    this.accountToType = selected.dataset.type || '';
                    this.receiverId = selected.value;
                    document.getElementById('selectedReceiverAccountType').value = this.accountToType;
                },

                validateDouble(value) {
                    return value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                },

                // Basic init if needed later
                init() {
                    // console.log('Alpine sendMoneyForm initialized')
                },

                // Optional: basic validation on submit
                validateBeforeSubmit(event) {
                    // if (!this.amount || !this.targetId || !this.receiverId) {
                    //     event.preventDefault();
                    //     alert("Please fill out all required fields.");
                    // }
                }
            }
        }
    </script>
@endsection
