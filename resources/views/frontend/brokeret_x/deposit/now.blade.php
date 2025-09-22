@extends('frontend::layouts.user')
@section('title')
    {{ __('Deposit Now') }}
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="space-y-1">
            <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
                @yield('title')
            </h2>
            <x-frontend::text-link href="{{ route('user.deposit.methods') }}" variant="primary">
                {{ __('See all payment methods') }}
            </x-frontend::text-link>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-y-12 lg:gap-y-5 lg:gap-x-12" x-data="depositComponent()" x-init="initDeposit()">
        <div class="col-span-12 lg:col-span-7">
            <div class="lg:max-w-xl">
                <form action="{{ route('user.deposit.now') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="gateway_code" value="{{ $gatewayCode }}">
                    <div class="space-y-5">
                        <x-frontend::forms.select-field
                            x-model="selectedAccount"
                            @change="handleAccountChange($event)"
                            fieldId="tradingAccount"
                            fieldName="target_id"
                            fieldLabel="{{ __('Account to Deposit') }}"
                            fieldRequired="true"
                            placeholder="--{{ __('Select Account') }}--">
                            @foreach($forexAccounts as $forexAccount)
                                <option value="{{the_hash($forexAccount->login) }}" data-type="forex" class="inline-block font-Inter font-normal text-sm text-slate-600">
                                    {{ $forexAccount->login }} - {{ $forexAccount->account_name }} ({{ get_mt5_account_equity($forexAccount->login) }} {{$currency}})
                                </option>
                            @endforeach
                            {{--mail wallet--}}
                            @include('frontend::wallets.include.__specific-wallet-dropdown', ['target_id_name' => 'target_id', 'wallet_type' => \App\Enums\AccountBalanceType::MAIN])
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
                                    @input="handleAmountChange($event)"
                                    fieldId="amount"
                                    fieldName="amount"
                                    class="pr-16"
                                />
                                <span class="absolute top-1/2 right-0 inline-flex -translate-y-1/2 cursor-pointer items-center gap-1 border-l border-gray-200 py-3 pr-3 pl-3.5 text-sm font-medium text-gray-700 dark:border-gray-800 dark:text-gray-400" id="basic-addon1">
                                    {{ $currency }}
                                </span>
                            </div>
                            <div class="font-Inter text-xs text-brand-500 pt-2 inline-block" x-text="minMaxText"></div>
                        </div>

                        <div :class="showConversion ? '' : 'hidden'">
                            <x-frontend::forms.label 
                                fieldId="converted-amount" 
                                fieldLabel="{{ __('Converted Amount') }}" 
                                fieldRequired="false"
                            />
                            <div class="relative">
                                <x-frontend::forms.input 
                                    x-model="convertedAmount"
                                    @input="handleConvertedAmountChange($event)"
                                    fieldId="converted-amount"
                                    fieldName="converted-amount"
                                    class="pr-16"
                                />
                                <span class="absolute top-1/2 right-0 inline-flex -translate-y-1/2 cursor-pointer items-center gap-1 border-l border-gray-200 py-3 pr-3 pl-3.5 text-sm font-medium text-gray-700 dark:border-gray-800 dark:text-gray-400" x-text="gatewayData.currency || '{{ $currency }}'">
                                </span>
                            </div>
                            <div class="font-Inter text-xs text-brand-500 pt-2 inline-block" x-text="conversionRateText"></div>
                        </div>
                        
                        <div class="space-y-5 manual-row" x-html="manualCredentials"></div>
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
                            <span id="display-payment-time" class="text-sm text-gray-700 dark:text-gray-400">
                                {{ __('1 hour') }}
                            </span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Fee') }}</span>
                            <span id="display-fee" class="text-sm text-gray-700 dark:text-gray-400">
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

    <div class="border-t border-gray-200 dark:border-gray-700 my-10"></div>
    @include('frontend::include.__contact_widget')

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
