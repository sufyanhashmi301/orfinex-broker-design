@extends('frontend::layouts.user')
@section('title')
    {{ __('External Transfer') }}
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            @yield('title')
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-12" x-data="externalTransferForm()" x-init="init()">
        <div class="col-span-12 lg:col-span-7">
            <div class="lg:max-w-xl">
                <form @submit="validateBeforeSubmit" action="{{ route('user.send-money.now') }}" method="post">
                    @csrf
                    <input type="hidden" name="target_type" id="selectedAccountType" value="forex">
                    <input type="hidden" name="receiver_type" id="selectedReceiverAccountType" value="forex">
                    <div class="space-y-5">
                        <x-frontend::forms.select-field
                            fieldId="tradingAccount"
                            fieldLabel="{{ __('Account From') }}"
                            fieldName="target_id"
                            @change="updateAccountType($event.target)"
                            :placeholder="__('--Select Account--')"
                            fieldRequired="true">
                            @foreach($forexAccounts as $forexAccount)
                                <option value="{{ the_hash($forexAccount->login) }}" data-type="forex">
                                    {{ $forexAccount->login }} - {{ $forexAccount->account_name }} ({{ get_mt5_account_equity($forexAccount->login) }} {{$currency}})
                                </option>
                            @endforeach
                            @include('frontend::wallets.include.__all-wallets-dropdown', ['target_id_name' => 'target_id'])
                        </x-frontend::forms.select-field>

                        <x-frontend::forms.field
                            fieldId="receiverAccount"
                            fieldLabel="{{ __('Account To') }}"
                            fieldName="receiver_account"
                            x-model="receiverAccount"
                            @input="receiverAccount = validateDouble($event.target.value)"
                            :placeholder="__('--Select Account--')"
                            fieldRequired="true">
                        </x-frontend::forms.field>

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
                                {{ __('Minimum') . ' ' . setting('external_min_send','fee') . ' ' . $currency . ' ' . __('and Maximum') . ' ' . setting('external_max_send','fee') . ' ' . $currency }}
                            </div>
                        </div>
                        
                        <x-frontend::forms.textarea-field
                            fieldId="note"
                            fieldLabel="{{ __('Transfer Note (Optional)') }}"
                            fieldName="note"
                            placeholder="{{ __('Transfer Note') }}"
                        />
                    </div>
                    <div class="pt-10">
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
        function externalTransferForm() {
            return {
                // Form state
                selectedAccountType: 'forex',
                receiverAccount: '',
                amount: '',
                charge: @json(setting('external_send_charge', 'fee')),
                chargeType: @json(setting('external_send_charge_type', 'fee')),
                currency: '{{ $currency }}',
                notifyUser: '',
                
                // Derived
                get finalCharge() {
                    const amt = parseFloat(this.amount || 0);
                    if (this.chargeType === 'percentage') {
                        return ((amt * parseFloat(this.charge)) / 100).toFixed(2);
                    }
                    return parseFloat(this.charge).toFixed(2);
                },

                updateAccountType(el) {
                    const selected = el.options[el.selectedIndex];
                    this.selectedAccountType = selected.dataset.type || 'forex';
                    document.getElementById('selectedAccountType').value = this.selectedAccountType;
                },

                validateReceiverAccount() {
                    if (!this.receiverAccount) return;
                    document.querySelector('.userAccount').textContent = this.receiverAccount;

                    const url = `{{ route('user.account.exist', ':account') }}`.replace(':account', this.receiverAccount);
                    fetch(url)
                        .then(res => res.text())
                        .then(data => this.notifyUser = data)
                        .catch(() => this.notifyUser = '');
                },

                validateDouble(value) {
                    return value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                },

                init() {
                    // Initial logic (if needed)
                },

                validateBeforeSubmit(e) {
                    // Example: skip if fields are empty
                    // if (!this.amount || !this.receiverAccount) {
                    //     e.preventDefault();
                    //     alert("Please fill in all required fields");
                    // }
                }
            }
        }
    </script>

@endsection
