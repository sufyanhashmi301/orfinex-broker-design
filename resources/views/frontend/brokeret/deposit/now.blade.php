@extends('frontend::deposit.index')
@section('deposit_content')
    <div x-data="depositComponent()" x-init="initDeposit()" class="progress-steps-form mb-6">
        <form action="{{ route('user.deposit.now') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="gateway_code" value="{{ $gatewayCode }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
                <div>
                    <h4 class="text-xl font-semibold text-gray-800 dark:text-white/90 mb-3">
                        {{ __('Enter your deposit details:') }}
                    </h4>

                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                        <div class="space-y-5">
                            <div class="input-area">
                                <label for="" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    {{ __('Account to Deposit:') }}
                                </label>
                                <select 
                                    id="tradingAccount" 
                                    name="target_id" 
                                    x-model="selectedAccount"
                                    @change="handleAccountChange($event)"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                    <option value="" disabled>--{{ __('Select Account') }}--</option>
                                    @foreach($forexAccounts as $forexAccount)
                                        <option value="{{the_hash($forexAccount->login) }}" data-type="forex" class="inline-block font-Inter font-normal text-sm text-slate-600">{{ $forexAccount->login }} - {{ $forexAccount->account_name }} ({{ get_mt5_account_equity($forexAccount->login) }} {{$currency}})</option>
                                    @endforeach
                                    {{--mail wallet--}}
                                    @include('frontend::wallet.include.__specific-wallet-dropdown', ['target_id_name' => 'target_id', 'wallet_type' => \App\Enums\AccountBalanceType::MAIN])

                                </select>
                            </div>
                            <div class="input-area relative">
                                <label for="" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    {{ __('Enter Amount:') }}
                                </label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        name="amount" 
                                        id="amount"
                                        x-model="amount"
                                        @input="handleAmountChange($event)"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-3 pr-[90px] pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        aria-label="Amount"
                                        aria-describedby="basic-addon1">
                                    <span class="absolute top-1/2 right-0 inline-flex -translate-y-1/2 cursor-pointer items-center gap-1 border-l border-gray-200 py-3 pr-3 pl-3.5 text-sm font-medium text-gray-700 dark:border-gray-800 dark:text-gray-400" id="basic-addon1">
                                        {{ $currency }}
                                    </span>
                                </div>
                                <div class="font-Inter text-xs text-danger pt-2 inline-block" x-text="minMaxText"></div>
                            </div>
                            <div class="input-area relative" :class="showConversion ? '' : 'hidden'">
                                <label for="" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    {{ __('Enter Amount:') }}
                                </label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        id="converted-amount"
                                        x-model="convertedAmount"
                                        @input="handleConvertedAmountChange($event)"
                                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-3 pr-[90px] pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                        aria-label="Amount" 
                                        aria-describedby="basic-addon2">
                                    <span class="absolute top-1/2 right-0 inline-flex -translate-y-1/2 cursor-pointer items-center gap-1 border-l border-gray-200 py-3 pr-3 pl-3.5 text-sm font-medium text-gray-700 dark:border-gray-800 dark:text-gray-400" x-text="gatewayData.currency || '{{ $currency }}'">
                                        {{ $currency }}
                                    </span>
                                </div>
                                <div class="font-Inter text-xs text-danger pt-2 inline-block" x-text="conversionRateText"></div>
                            </div>
                            <div class="space-y-5 manual-row" x-html="manualCredentials"></div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-xl font-semibold text-gray-800 dark:text-white/90 mb-3">
                        {{ __('Review Details:') }}
                    </h4>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6 transaction-list">
                        <div class="">
                            <table class="table w-full border-collapse table-fixed dark:border-slate-700 dark:border">
                                <tbody>
                                    <tr>
                                        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                            <strong>{{ __('Amount') }}</strong>
                                        </td>
                                        <td class="dark:text-slate-300">
                                            <span x-text="displayAmount"></span>
                                            <span x-text="baseCurrency"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                            <strong>{{ __('Charge') }}</strong>
                                        </td>
                                        <td class="dark:text-slate-300" x-text="chargeText"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                            <strong>{{ __('Payment Method') }}</strong>
                                        </td>
                                        <td>
                                            <img x-show="gatewayData.logo" :src="assetPath + gatewayData.logo" class="payment-method h-12" alt="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                            <strong>{{ __('Total') }}</strong>
                                        </td>
                                        <td class="dark:text-slate-300" x-text="totalText"></td>
                                    </tr>
                                    <tr x-show="showConversion">
                                        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                            <strong>{{ __('Conversion Rate') }}</strong>
                                        </td>
                                        <td class="dark:text-slate-300" x-text="conversionRateText"></td>
                                    </tr>
                                    <tr x-show="showConversion">
                                        <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                            <strong>{{ __('Pay Amount') }}</strong>
                                        </td>
                                        <td class="dark:text-slate-300" x-text="payAmountText"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="buttons border-t border-slate-100 dark:border-slate-700 mt-4 pt-4">
                                <x-forms.button type="submit" class="w-full mt-auto" size="lg" variant="primary" icon="arrow-right" icon-position="right">
                                    {{ __('Proceed to Payment') }}
                                </x-forms.button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div x-data="{ expanded: false }" class="rounded-xl border border-brand-500 bg-brand-50 p-4 dark:border-brand-500/30 dark:bg-brand-500/15">
        <div class="flex items-start gap-3 cursor-pointer" @click="expanded = !expanded">
            <div class="-mt-0.5 text-brand-500">
                <i data-lucide="info" class="w-4"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-semibold text-gray-800 dark:text-white/90">
                    {{ __('Stay safe online') }}
                </h4>

                <div x-show="expanded" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-screen"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 max-h-screen"
                     x-transition:leave-end="opacity-0 max-h-0"
                     class="overflow-hidden">
                    <p class="text-sm text-gray-500 dark:text-gray-400 pt-2">
                        {{ __('Protect your security by never sharing your personal or credit card information over the phone, by email, or chat.') }}
                        <a href="" class="text-warning-500">{{ __('Learn more') }}</a>
                    </p>
                </div>
            </div>
            <span class="ms-auto text-gray-800 dark:text-white/90 transition-transform duration-300" 
                  :class="{ 'rotate-180': expanded }">
                <i data-lucide="chevron-down" class="w-4"></i>
            </span>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function depositComponent() {
            return {
                // State
                selectedAccount: '',
                selectedAccountType: '',
                amount: '',
                convertedAmount: '',
                gatewayData: {},
                manualCredentials: '',
                loading: false,
                
                // Constants
                assetPath: '{{ asset('') }}/',
                baseCurrency: '{{ $currency }}',
                gatewayCode: '{{ $gatewayCode }}',
                
                // Computed properties
                get showConversion() {
                    return this.gatewayData.currency && this.gatewayData.currency !== this.baseCurrency;
                },
                
                get displayAmount() {
                    return this.amount ? Number(this.amount) : 0;
                },
                
                get charge() {
                    if (!this.gatewayData.charge_type || !this.amount) return 0;
                    return this.gatewayData.charge_type === 'percentage' 
                        ? this.calculatePercentage(this.amount, this.gatewayData.charge)
                        : this.gatewayData.charge;
                },
                
                get total() {
                    return Number(this.amount || 0) + Number(this.charge);
                },
                
                get chargeText() {
                    return this.charge + ' ' + this.baseCurrency;
                },
                
                get totalText() {
                    return this.total + ' ' + this.baseCurrency;
                },
                
                get conversionRateText() {
                    if (!this.gatewayData.rate) return '';
                    return '1 ' + this.baseCurrency + ' = ' + this.gatewayData.rate + ' ' + this.gatewayData.currency;
                },
                
                get payAmountText() {
                    if (!this.gatewayData.rate) return '';
                    return parseFloat((this.total * this.gatewayData.rate).toFixed(4)) + ' ' + this.gatewayData.currency;
                },
                
                get minMaxText() {
                    if (!this.gatewayData.minimum_deposit) return '';
                    return 'Minimum ' + this.gatewayData.minimum_deposit + ' ' + this.baseCurrency + 
                           ' and Maximum ' + this.gatewayData.maximum_deposit + ' ' + this.baseCurrency;
                },
                
                // Methods
                async initDeposit() {
                    this.loading = true;
                    try {
                        const url = '{{ route("user.deposit.gateway", ":code") }}'.replace(':code', this.gatewayCode);
                        const response = await fetch(url);
                        const data = await response.json();
                        
                        this.gatewayData = data;
                        
                        if (data.credentials !== undefined) {
                            this.manualCredentials = data.credentials;
                            // Trigger image preview if needed
                            this.$nextTick(() => {
                                if (typeof imagePreview === 'function') {
                                    imagePreview();
                                }
                            });
                        }
                        
                        // Update converted amount if there's an initial amount
                        if (this.amount && Number(this.amount) > 0) {
                            this.updateConvertedAmount();
                        }
                        
                    } catch (error) {
                        console.error('Error loading gateway data:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                handleAccountChange(event) {
                    const selectedOption = event.target.options[event.target.selectedIndex];
                    this.selectedAccountType = selectedOption.dataset.type || '';
                    
                    // Set hidden input if exists
                    const hiddenInput = document.getElementById('selectedAccountType');
                    if (hiddenInput) {
                        hiddenInput.value = this.selectedAccountType;
                    }
                },
                
                handleAmountChange(event) {
                    // Validate double input
                    this.amount = this.validateDouble(event.target.value);
                    this.updateConvertedAmount();
                },
                
                handleConvertedAmountChange(event) {
                    // Validate double input
                    this.convertedAmount = this.validateDouble(event.target.value);
                    this.updateAmountFromConverted();
                },
                
                updateConvertedAmount() {
                    if (this.gatewayData.rate && this.total > 0) {
                        this.convertedAmount = parseFloat((this.total * this.gatewayData.rate).toFixed(4)).toString();
                    }
                },
                
                updateAmountFromConverted() {
                    if (this.gatewayData.rate && this.convertedAmount) {
                        this.amount = parseFloat((this.convertedAmount / this.gatewayData.rate).toFixed(4)).toString();
                    }
                },
                
                calculatePercentage(amount, percentage) {
                    return (Number(amount) * Number(percentage)) / 100;
                },
                
                validateDouble(value) {
                    // Remove any non-numeric characters except decimal point
                    return value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                }
            }
        }

        // Global function for backward compatibility
        function validateDouble(value) {
            return value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
        }

        // Global function for percentage calculation
        function calPercentage(amount, percentage) {
            return (Number(amount) * Number(percentage)) / 100;
        }
    </script>
@endsection
