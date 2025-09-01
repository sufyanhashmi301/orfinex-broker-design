@extends('frontend::send_money.index-internal')
@section('send_money_content_internal')

    <div class="progress-steps-form">
        <form x-data="sendMoneyForm()" x-init="init()" @submit="validateBeforeSubmit($event)" action="{{ route('user.send-money.internal-now') }}" method="post">
            @csrf
            <input type="hidden" name="target_type" id="selectedAccountType" value="">
            <input type="hidden" name="receiver_type" id="selectedReceiverAccountType" value="">

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
                                <select id="tradingAccount" name="target_id" @change="handleFromChange($event.target)" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                    <option selected disabled>--{{ __('Select Account') }}--</option>
                                    @foreach($forexAccounts as $forexAccount)
                                        <option value="{{ the_hash($forexAccount->login) }}" data-type="forex">{{ $forexAccount->login }} - {{ $forexAccount->account_name }} ({{ get_mt5_account_equity($forexAccount->login) }} {{$currency}})</option>
                                    @endforeach
                                    @include('frontend::wallets.include.__all-wallets-dropdown', ['target_id_name' => 'target_id'])

                                </select>
                            </div>

                            <div class="input-area relative">
                                <label for="exampleFormControlInput1" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    {{ __('Account To:') }}
                                </label>
                                <select id="receiverTradingAccount" name="receiver_account" @change="handleToChange($event.target)" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                    <option selected disabled>--{{ __('Select Account') }}--</option>
                                    @foreach($forexAccounts as $forexAccount)
                                        <option value="{{ the_hash($forexAccount->login) }}" data-type="forex">{{ $forexAccount->login }} - {{ $forexAccount->account_name }}({{ get_mt5_account_equity($forexAccount->login) }} {{$currency}})</option>
                                    @endforeach
                                    @include('frontend::wallets.include.__specific-wallet-dropdown', ['target_id_name' => 'receiver_account', 'wallet_type' => \App\Enums\AccountBalanceType::MAIN])
                                </select>
                            </div>

                            <div class="input-area relative">
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
                                    {{ __('Minimum '). setting('internal_min_send','transfer_internal').' '.$currency.__(' and Maximum '). setting('internal_max_send','transfer_internal').' '.$currency }}
                                </div>
                            </div>

                            <div class="input-area relative col-span-12">
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
                                                <td class="text-slate-900 dark:text-slate-300 text-sm font-normal px-6 py-4">
                                                    <strong>{{ __('Account From') }}</strong>
                                                </td>
                                                <td class="dark:text-slate-300" x-text="accountFromText"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-slate-900 dark:text-slate-300 text-sm font-normal px-6 py-4">
                                                    <strong>{{ __('Account To') }}</strong>
                                                </td>
                                                <td class="dark:text-slate-300" x-text="accountToText"></td>
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
