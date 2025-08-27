@extends('frontend::send_money.index')
@section('send_money_content')

    <div class="progress-steps-form">
        <form x-data="externalTransferForm()" x-init="init()" @submit="validateBeforeSubmit" action="{{ route('user.send-money.now') }}" method="post">
            @csrf
            <input type="hidden" name="target_type" id="selectedAccountType" value="forex"> <!-- Default to forex -->
            <input type="hidden" name="receiver_type" id="selectedReceiverAccountType" value="forex"> <!-- Default to forex -->

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
                <div>
                    <h4 class="text-xl font-semibold text-gray-800 dark:text-white/90 mb-3">
                        {{ __('Enter your transfer details.') }}
                    </h4>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                        <div class="space-y-5">
                            <div class="input-area">
                                <label for="exampleFormControlInput1" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    {{ __('Account From:') }}
                                </label>
                                <select id="tradingAccount" name="target_id" @change="updateAccountType($event.target)" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                    <option selected disabled>--{{ __('Select Account') }}--</option>
                                    <!-- Forex Accounts -->
                                    @foreach($forexAccounts as $forexAccount)
                                        <option value="{{ the_hash($forexAccount->login) }}" data-type="forex">
                                            {{ $forexAccount->login }} - {{ $forexAccount->account_name }} ({{ get_mt5_account_equity($forexAccount->login) }} {{$currency}})
                                        </option>
                                    @endforeach

                                    <!-- Wallet Accounts -->
                                    @include('frontend::wallet.include.__all-wallets-dropdown', ['target_id_name' => 'target_id'])
                                </select>
                            </div>

                            <div class="input-area">
                                <label for="exampleFormControlInput1" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    {{ __('Account To:') }}
                                </label>
                                <input type="text" name="receiver_account" x-model="receiverAccount" @input="receiverAccount = validateDouble($event.target.value)" required class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 userAccountCheck" placeholder="{{ __('Receiver Account') }}">
                                <div class="font-Inter text-xs text-danger pt-2 inline-block min-max notifyUser"></div>
                            </div>

                            <div class="input-area">
                                <label for="exampleFormControlInput1" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    {{ __('Enter Amount') }}
                                </label>
                                <div class="relative">
                                    <input type="text" x-model="amount" @input="amount = validateDouble($event.target.value)" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-3 pr-[90px] pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 sendAmount" name="amount" required
                                           placeholder="{{ __('Enter Amount') }}">
                                    <span class="absolute top-1/2 right-0 inline-flex -translate-y-1/2 cursor-pointer items-center gap-1 border-l border-gray-200 py-3 pr-3 pl-3.5 text-sm font-medium text-gray-700 dark:border-gray-800 dark:text-gray-400" id="basic-addon1">
                                    {{ $currency }}
                                </span>
                                </div>
                                <div class="text-theme-xs text-error-500 mt-1 min-max">
                                    {{ __('Minimum') . ' ' . setting('external_min_send','fee') . ' ' . $currency . ' ' . __('and Maximum') . ' ' . setting('external_max_send','fee') . ' ' . $currency }}
                                </div>
                            </div>

                            <div class="input-area relative">
                                <label for="exampleFormControlInput1" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    {{ __('Transfer Note (Optional)') }}
                                </label>
                                <textarea class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" rows="5" placeholder="{{ __('Transfer Note') }}" name="note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-xl font-semibold text-gray-800 dark:text-white/90 mb-3">
                        {{ __('Review Details:') }}
                    </h4>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                        <div class="">
                            <div class="transaction-list">
                                <div class="max-w-[1005px] mx-auto rounded-md overflow-x-auto">
                                    <table class="table w-full border-collapse table-fixed dark:border-slate-700 dark:border">
                                        <tbody>
                                        <tr>
                                            <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                                <strong>{{ __('Payment Amount') }}</strong>
                                            </td>
                                            <td class="dark:text-slate-300">
                                                <span x-text="amount"></span> {{ $currency }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                                <strong>{{ __('Charge') }}</strong>
                                            </td>
                                            <td class="dark:text-slate-300">
                                                <span x-text="finalCharge"></span> {{ $currency }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                                <strong>{{ __('User Account') }}</strong>
                                            </td>
                                            <td class="dark:text-slate-300" x-text="receiverAccount"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="buttons border-t border-slate-100 dark:border-slate-700 mt-4 pt-4">
                                        <x-forms.button type="submit" class="w-full mt-auto" size="lg" variant="primary" icon="arrow-right" icon-position="right">
                                            {{ __('Transfer Now') }}
                                        </x-forms.button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
