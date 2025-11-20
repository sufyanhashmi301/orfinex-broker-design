@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Now') }}
@endsection
@push('style')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="space-y-1">
            <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
                @yield('title')
            </h2>
            <x-frontend::text-link href="{{ route('user.withdraw.methods') }}" variant="primary">
                {{ __('See all payment methods') }}
            </x-frontend::text-link>
        </div>
    </div>

    <div x-data="withdrawVerification()">
        <div class="grid grid-cols-12 gap-y-12 lg:gap-y-5 lg:gap-x-12">
            <div class="col-span-12 lg:col-span-7">
                <div class="lg:max-w-xl">
                    <form action="{{ route('user.withdraw.now') }}" method="post" id="withdrawForm" @submit.prevent="startVerification">
                        @csrf
                        <input type="hidden" name="account_type" x-model="accountType">
                        <div class="space-y-5">
                            <x-frontend::forms.select-field
                                fieldId="tradingAccount"
                                fieldLabel="{{ __('Account to withdraw') }}"
                                fieldName="target_id"
                                x-model="selectedTargetId"
                                @change="handleTradingAccountChange"
                                placeholder="--{{ __('Select Account') }}--"
                                fieldRequired="true">
                                @foreach($forexAccounts as $forexAccount)
                                    <option value="{{ the_hash($forexAccount->login) }}"
                                            data-type="forex"
                                            {{ old('target_id') == the_hash($forexAccount->login) ? 'selected' : '' }}
                                            class="inline-block font-Inter font-normal text-sm text-slate-600">
                                        {{ $forexAccount->login }} - {{ $forexAccount->account_name }}
                                        ({{ get_mt5_account_equity($forexAccount->login) }} {{ $currency }})
                                    </option>
                                @endforeach
                                @include('frontend::wallets.include.__all-wallets-dropdown', ['target_id_name' => 'target_id'])
                            </x-frontend::forms.select-field>
                            
                            <div class="input-area">
                                <x-frontend::forms.label 
                                    fieldId="withdrawAccount"
                                    fieldLabel="{{ __('Withdraw Account') }}"
                                    fieldRequired="true"
                                />
                                <x-frontend::forms.select
                                    fieldId="withdrawAccount"
                                    fieldLabel="{{ __('Withdraw Account') }}"
                                    fieldName="withdraw_account"
                                    x-model="selectedWithdrawAccount"
                                    @change="handleWithdrawAccountChange"
                                    fieldRequired="true"
                                    x-init="selectedWithdrawAccount = '{{ $selectedAccount }}'">
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">
                                            {{ $account->method_name }}
                                        </option>
                                    @endforeach
                                </x-frontend::forms.select>
                            </div>

                            <div class="input-area">
                                <x-frontend::forms.label 
                                    fieldId="amount"
                                    fieldLabel="{{ __('Enter Amount') }}"
                                    fieldRequired="true"
                                />
                                <div class="relative">
                                    <x-frontend::forms.input 
                                        x-model="amount"
                                        @input="handleAmountChange"
                                        fieldId="amount"
                                        fieldName="amount"
                                        required
                                        class="pr-16"
                                    />
                                    <span  x-text="info.pay_currency || '{{ $currency }}'" 
                                        class="absolute top-1/2 right-0 inline-flex -translate-y-1/2 cursor-pointer items-center gap-1 border-l border-gray-200 py-2.5 pr-3 pl-3.5 text-sm font-medium text-gray-700 dark:border-gray-800 dark:text-gray-400" id="basic-addon1">
                                    </span>
                                </div>
                                <div class="font-Inter text-xs text-brand-500 pt-2 inline-block" x-text="conversionRate"></div>
                            </div>
                            
                            <div class="input-area relative conversion" :class="{ 'hidden': isConversionHidden }">
                                <x-frontend::forms.label 
                                    fieldId="convertedAmount"
                                    fieldLabel="{{ __('Converted Amount') }}"
                                    fieldRequired="false"
                                />
                                <div class="relative">
                                    <x-frontend::forms.input 
                                        x-model="convertedAmount"
                                        @input="handleConvertedAmountChange"
                                        fieldId="convertedAmount"
                                        fieldName="converted_amount"
                                        class="pr-16"
                                    />
                                    <span  x-text="info.pay_currency || '{{ $currency }}'" 
                                        class="absolute top-1/2 right-0 inline-flex -translate-y-1/2 cursor-pointer items-center gap-1 border-l border-gray-200 py-2.5 pr-3 pl-3.5 text-sm font-medium text-gray-700 dark:border-gray-800 dark:text-gray-400" id="basic-addon2">
                                    </span>
                                </div>
                                <div class="font-Inter text-xs text-brand-500 pt-2 inline-block" x-text="conversionRate"></div>
                            </div>
                        </div>
                        <div class="mt-10">
                            <x-frontend::forms.button type="submit" class="w-full" size="md" variant="primary" icon="arrow-right" icon-position="right"
                                x-bind:disabled="isSubmitting"
                                x-bind:loading="isSubmitting">
                                {{ __('Withdraw Money') }}
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
                                <span class="text-sm text-gray-700 dark:text-gray-400" x-text="processingTime">
                                    {{ __('1 hour') }}
                                </span>
                            </li>
                            <li class="flex gap-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Fee') }}</span>
                                <span class="text-sm text-gray-700 dark:text-gray-400" x-text="info.charge ? (info.charge_type === 'percentage' ? info.charge + '%' : info.charge + ' ' + info.pay_currency) : '{{ __('No Fee') }}'">
                                    {{ __('No Fee') }}
                                </span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white/90 mb-3">
                            {{ __('Account Details') }}
                        </h3>
                        @if($selectedAccount)
                            @php
                                $selectedAccountData = $accounts->where('id', $selectedAccount)->first();
                                $credentials = $selectedAccountData 
                                    ? (is_string($selectedAccountData->credentials) 
                                        ? json_decode($selectedAccountData->credentials, true) 
                                        : $selectedAccountData->credentials) 
                                    : [];
                                $credentials = is_array($credentials) ? $credentials : [];
                            @endphp
                            
                            @if($selectedAccountData)
                                <div class="space-y-3">
                                    @if($credentials)
                                        @foreach($credentials as $key => $credential)
                                            <div class="flex gap-2">
                                                <span class="text-sm text-gray-500 dark:text-gray-400 capitalize">
                                                    {{ ucwords(str_replace('_', ' ', $key)) }}
                                                </span>
                                                <span class="text-sm text-gray-700 dark:text-gray-400 break-all">
                                                    @if(is_array($credential))
                                                        {{ $credential['value'] ?? '' }}
                                                    @else
                                                        {{ $credential }}
                                                    @endif
                                                </span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                
                                <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex gap-2 mb-2">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Method') }}</span>
                                        <span class="text-sm text-gray-700 dark:text-gray-400">{{ $selectedAccountData->method_name }}</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Currency') }}</span>
                                        <span class="text-sm text-gray-700 dark:text-gray-400">{{ $selectedAccountData->method->currency ?? $currency }}</span>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(setting('contact_widget_withdraw_page', 'contact_widget'))
            <div class="border-t border-gray-200 dark:border-gray-700 my-10"></div>
            @include('frontend::include.__contact_widget')
        @endif

        {{-- Modal for OTP (only if OTP is enabled) --}}
        @if(setting('withdraw_otp', 'misc'))
            <!-- Include OTP Modals -->
            @include('frontend::withdraw.modal.__otp_form')
            @include('frontend::withdraw.modal.__cancel_otp')
        @endif

        @include('frontend::withdraw.modal.__varification_choice')
        @include('frontend::withdraw.modal.__ga_form')
    </div>

@endsection

@section('script')
    <script>
        function withdrawVerification() {
            return {
                // Data properties
                selectedTargetId: '{{ old('target_id') }}',
                selectedWithdrawAccount: '{{ old('withdraw_account') }}',
                accountType: '{{ old('account_type') }}',
                amount: '{{ old('amount') }}',
                convertedAmount: '',
                info: {},
                currency: @json($currency),
                
                // UI state
                isConversionHidden: true,
                processingTime: '',
                conversionRate: '',
                
                // Verification settings
                withdrawOtp: @json((bool) setting('withdraw_otp', 'withdraw_settings')),
                twoFaEnabledForUser: @json((bool) setting('fa_verification', 'permission') && (bool) auth()->user()->two_fa),
                
                // Modal state variables
                isVerificationChoiceModalOpen: false,
                isOtpModalOpen: @json(session('otp') && Carbon::now()->lt(session('otp_expiration')) ? true : false),
                isCancelModalOpen: false,
                modals: {
                    ga: false
                },
                
                // Verification variables
                selectedVerificationMethod: null,
                hasGoogleAuthenticator: @json(auth()->check() && auth()->user()->google2fa_secret ? true : false),
                
                // Input variables
                otpInputs: ['', '', '', ''],  // 4-digit OTP
                gaInputs: ['', '', '', '', '', ''],
                isResendingOtp: false,
                isSubmitting: false,
                
                // OTP Timer
                otpExpiryTime: @json(setting('withdraw_otp_expires', 'withdraw_settings') ?? 10) * 60,  // in seconds
                otpTimer: null,
                

                init() {
                    // Initialize old values
                    @if($selectedAccount)
                        // Pre-select the account and load its details
                        this.selectedWithdrawAccount = '{{ $selectedAccount }}';
                        this.$nextTick(() => {
                            this.handleWithdrawAccountChange();
                        });
                    @else
                        // Initialize old values
                        if (this.accountType && this.selectedWithdrawAccount) {
                            this.$nextTick(() => {
                                this.handleWithdrawAccountChange();
                            });
                        }
                    @endif
                    
                    if (this.amount) {
                        this.$nextTick(() => {
                            this.handleAmountChange();
                        });
                    }
                    
                    // Start OTP timer if modal is open
                    this.$watch('isOtpModalOpen', (value) => {
                        if (value) {
                            this.startOtpTimer();
                        } else {
                            this.stopOtpTimer();
                        }
                    });
                    
                    // Check session for verification requirement (like jQuery version)
                    this.checkSessionForVerification();
                },

                // Check session for verification requirement after backend redirect
                checkSessionForVerification() {
                    @php
                        $authRequired = session('withdraw_auth_required', false);
                        $withdrawalData = session('withdrawal_data', null);
                        $authOptions = session('withdraw_auth_options', []);
                        $hasOtpOption = $authOptions['otp'] ?? false;
                        $hasGaOption = $authOptions['ga'] ?? false;
                        
                        // Check if this is a redirect from backend (has old input) or fresh page load
                        $hasOldInput = old('target_id') || old('withdraw_account') || old('amount');
                    @endphp
                    
                    // Only show modal if:
                    // 1. Auth is required AND withdrawal data exists (form was submitted)
                    // 2. AND there's old input (meaning this is a redirect from backend, not fresh page load)
                    @if(session('withdraw_auth_required') && !empty($withdrawalData) && $hasOldInput)
                        // Both verification methods available
                        @if($hasOtpOption && $hasGaOption)
                            this.$nextTick(() => {
                                this.isVerificationChoiceModalOpen = true;
                            });
                        // Only OTP available
                        @elseif($hasOtpOption && !$hasGaOption)
                            this.$nextTick(() => {
                                // OTP should already be sent by backend, just show modal
                                this.isOtpModalOpen = true;
                            });
                        // Only GA available
                        @elseif(!$hasOtpOption && $hasGaOption)
                            this.$nextTick(() => {
                                this.modals.ga = true;
                            });
                        @endif
                        
                        // Clear session flags after detecting (like jQuery version does)
                        @php
                            session()->forget('withdraw_auth_required');
                            session()->forget('withdraw_auth_options');
                        @endphp
                    @endif
                },

                // Helper methods
                findForm() {
                    return document.getElementById('withdrawForm');
                },

                getCsrfToken() {
                    return document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
                },

                showPageLoader() {
                    const pageLoader = document.getElementById('page-loader');
                    if (pageLoader) pageLoader.style.display = 'block';
                },

                syncFormFields(form) {
                    const targetIdInput = form.querySelector('[name="target_id"]');
                    const accountTypeInput = form.querySelector('[name="account_type"]');
                    const withdrawAccountInput = form.querySelector('[name="withdraw_account"]');
                    const amountInput = form.querySelector('[name="amount"]');
                    
                    if (targetIdInput && this.selectedTargetId) {
                        targetIdInput.value = this.selectedTargetId;
                    }
                    if (accountTypeInput && this.accountType) {
                        accountTypeInput.value = this.accountType;
                    }
                    if (withdrawAccountInput && this.selectedWithdrawAccount) {
                        withdrawAccountInput.value = this.selectedWithdrawAccount;
                    }
                    if (amountInput && this.amount) {
                        amountInput.value = this.amount;
                    }
                },

                // Validation helper
                validateDouble(value) {
                    return value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                },

                handleTradingAccountChange() {
                    const selectedOption = this.$el.querySelector('#tradingAccount option:checked');
                    if (selectedOption) {
                        this.accountType = selectedOption.dataset.type || '';
                    }
                },

                async handleWithdrawAccountChange() {
                    if (!this.selectedWithdrawAccount || isNaN(this.selectedWithdrawAccount)) {
                        return;
                    }
                    
                    try {
                        let url = '{{ route("user.withdraw.details", ['accountId' => ':accountId', 'amount' => ':amount']) }}';
                        url = url.replace(':accountId', this.selectedWithdrawAccount);
                        url = url.replace(':amount', this.amount || '0');
                        url = url.replace(/\/+$/, '');
                            
                        const response = await fetch(url, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        this.info = data.info || {};
                        
                        if (this.info.pay_currency === this.currency) {
                            this.isConversionHidden = true;
                        } else {
                            this.isConversionHidden = false;
                            this.conversionRate = `1 ${this.currency} = ${this.info.rate} ${this.info.pay_currency}`;
                            this.handleAmountChange();
                        }
                        
                        
                        this.processingTime = this.info.processing_time || '';
                        
                    } catch (error) {
                        // Silent error handling - could add user notification here if needed
                    }
                },

                handleAmountChange() {
                    // Validate input
                    this.amount = this.validateDouble(this.amount);
                    
                    // Calculate converted amount if rate is available
                    if (this.info.rate) {
                        this.convertedAmount = parseFloat((this.amount * this.info.rate).toFixed(4)).toString();
                    }
                },

                handleConvertedAmountChange() {
                    this.convertedAmount = this.validateDouble(this.convertedAmount);
                    
                    if (!this.info.rate) return;
                    
                    // Calculate original amount from converted amount
                    this.amount = parseFloat((this.convertedAmount / this.info.rate).toFixed(4)).toString();
                },

                async submitOtp() {
                    const otpCode = this.otpInputs.join('');
                    
                    if (otpCode.length !== 4) {
                        notify().error('Please enter the complete 4-digit code');
                        return;
                    }
                    
                    this.isSubmitting = true;
                    
                    try {
                        const response = await fetch('{{ route("user.withdraw.otp.verify") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.getCsrfToken()
                            },
                            body: JSON.stringify({ otp: otpCode })
                        });
                        
                        const data = await response.json();
                        
                        if (data.status === 'success') {
                            this.isOtpModalOpen = false;
                            notify().success(data.message);
                            this.showPageLoader();
                            this.submitFormAfterOtp();
                        } else {
                            this.isSubmitting = false;
                            const errorMessage = data.message || '{{ __("OTP verification failed") }}';
                            notify().error(errorMessage);
                            this.otpInputs = ['', '', '', ''];
                            
                            this.$nextTick(() => {
                                const firstInput = document.querySelector('[data-otp-index="0"]');
                                if (firstInput) firstInput.focus();
                            });
                            
                            if (errorMessage.toLowerCase().includes('expired')) {
                                setTimeout(() => {
                                    if (confirm('{{ __("OTP has expired. Would you like to request a new one?") }}')) {
                                        this.resendOtp();
                                    }
                                }, 500);
                            }
                        }
                    } catch (error) {
                        this.isSubmitting = false;
                        notify().error('An error occurred during OTP verification. Please try again.');
                        this.otpInputs = ['', '', '', ''];
                    }
                },

                // Note: OTP sending is handled by backend during form submission
                // This method is kept for potential future use but not called in current flow

                async resendOtp() {
                    this.isResendingOtp = true;
                    
                    try {
                        const response = await fetch('{{ route('user.withdraw.otp.resend') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.getCsrfToken()
                            }
                        });
                        
                        const data = await response.json();
                        notify().success(data.message);
                        this.startOtpTimer();
                        this.otpInputs = ['', '', '', ''];
                        
                        this.$nextTick(() => {
                            const firstInput = document.querySelector('[data-otp-index="0"]');
                            if (firstInput) firstInput.focus();
                        });
                    } catch (error) {
                        notify().error('An error occurred while resending the OTP. Please try again.');
                    } finally {
                        this.isResendingOtp = false;
                    }
                },

                showCancelModal() {
                    this.isCancelModalOpen = true;
                },

                closeOtpModal() {
                    this.isOtpModalOpen = false;
                    this.otpInputs = ['', '', '', ''];
                    this.stopOtpTimer();
                },

                // OTP Timer methods
                startOtpTimer() {
                    // Reset timer
                    this.otpExpiryTime = @json(setting('withdraw_otp_expires', 'withdraw_settings') ?? 10) * 60;
                    
                    // Clear existing timer if any
                    if (this.otpTimer) {
                        clearInterval(this.otpTimer);
                    }
                    
                    // Start countdown
                    this.otpTimer = setInterval(() => {
                        if (this.otpExpiryTime > 0) {
                            this.otpExpiryTime--;
                        } else {
                            this.stopOtpTimer();
                        }
                    }, 1000);
                },

                stopOtpTimer() {
                    if (this.otpTimer) {
                        clearInterval(this.otpTimer);
                        this.otpTimer = null;
                    }
                },

                formatTime(seconds) {
                    const minutes = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    return `${minutes}:${secs.toString().padStart(2, '0')}`;
                },

                // OTP input handling methods
                updateOtpInput(index, value) {
                    // Only allow digits
                    if (!/^\d*$/.test(value)) return;
                    
                    this.otpInputs[index] = value;
                    
                    // Auto-focus next input
                    if (value && index < 3) {
                        const nextInput = document.querySelector(`[data-otp-index="${index + 1}"]`);
                        if (nextInput) nextInput.focus();
                    }
                    
                    // Auto-submit when all 4 digits entered
                    if (index === 3 && value && this.otpInputs.every(digit => digit !== '')) {
                        this.$nextTick(() => {
                            this.submitOtp();
                        });
                    }
                },

                handleOtpKeydown(index, event) {
                    // Handle backspace
                    if (event.key === 'Backspace' && !this.otpInputs[index] && index > 0) {
                        const prevInput = document.querySelector(`[data-otp-index="${index - 1}"]`);
                        if (prevInput) {
                            prevInput.focus();
                            this.otpInputs[index - 1] = '';
                        }
                    }
                    
                    // Handle arrow keys
                    if (event.key === 'ArrowLeft' && index > 0) {
                        const prevInput = document.querySelector(`[data-otp-index="${index - 1}"]`);
                        if (prevInput) prevInput.focus();
                    } else if (event.key === 'ArrowRight' && index < 3) {
                        const nextInput = document.querySelector(`[data-otp-index="${index + 1}"]`);
                        if (nextInput) nextInput.focus();
                    }
                },

                handleOtpPaste(event) {
                    event.preventDefault();
                    const pastedData = event.clipboardData.getData('text').replace(/\D/g, '');
                    
                    if (pastedData.length === 4) {
                        for (let i = 0; i < 4; i++) {
                            this.otpInputs[i] = pastedData[i] || '';
                        }
                        
                        // Focus the last input
                        const lastInput = document.querySelector('[data-otp-index="3"]');
                        if (lastInput) {
                            lastInput.focus();
                            // Auto-submit after paste
                            this.$nextTick(() => {
                                this.submitOtp();
                            });
                        }
                    }
                },

                // Smart verification flow - main logic
                startVerification() {
                    // Validate required fields first
                    if (!this.validateForm()) {
                        return;
                    }
                    
                    // Set loading state - will persist until page reload/redirect
                    this.isSubmitting = true;
                    
                    // For withdrawals, always submit form first to let backend handle verification
                    // Backend will redirect back with session data if verification is needed
                    // This matches the jQuery version flow
                    this.submitFormDirectly();
                },

                // Validate form fields
                validateForm() {
                    if (!this.selectedTargetId) {
                        notify().error('Please select an account to withdraw from');
                        return false;
                    }
                    
                    if (!this.selectedWithdrawAccount) {
                        notify().error('Please select a withdraw method');
                        return false;
                    }
                    
                    if (!this.amount || parseFloat(this.amount) <= 0) {
                        notify().error('Please enter a valid amount');
                        return false;
                    }
                    
                    return true;
                },

                // Submit form without verification
                submitFormDirectly() {
                    const form = this.findForm();
                    if (form) {
                        this.isSubmitting = true;
                        form.removeAttribute('@submit.prevent');
                        this.showPageLoader();
                        form.submit();
                    }
                },

                submitFormAfterOtp() {
                    const form = this.findForm();
                    if (form) {
                        this.isSubmitting = true;
                        this.syncFormFields(form);
                        form.removeAttribute('@submit.prevent');
                        this.showPageLoader();
                        form.submit();
                    } else {
                        this.isSubmitting = false;
                        notify().error('Form submission error');
                    }
                },

                // Method selection for verification choice modal
                selectVerificationMethod(method) {
                    this.selectedVerificationMethod = method;
                },

                // Handle verification method selection and proceed
                async proceedWithVerification() {
                    if (!this.selectedVerificationMethod) {
                        notify().error('Please choose a verification method');
                        return;
                    }
                    
                    this.isVerificationChoiceModalOpen = false;
                    
                    if (this.selectedVerificationMethod === 'email') {
                        try {
                            const response = await fetch('{{ route('user.withdraw.otp.resend') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.getCsrfToken(),
                                    'Accept': 'application/json'
                                }
                            });
                            
                            if (!response.ok) {
                                let errorMessage = '{{ __("Failed to send OTP. Please try again.") }}';
                                try {
                                    const errorData = await response.json();
                                    errorMessage = errorData.message || errorMessage;
                                } catch (e) {
                                    errorMessage = '{{ __("Server error. Please try again later.") }}';
                                }
                                notify().error(errorMessage);
                                return;
                            }
                            
                            const data = await response.json();
                            
                            if (data.success || data.status === 'success') {
                                notify().success(data.message || 'OTP has been sent. Please verify it to proceed.');
                                this.isOtpModalOpen = true;
                                this.startOtpTimer();
                            } else {
                                notify().error(data.message || 'Failed to send OTP. Please try again.');
                            }
                        } catch (error) {
                            notify().error('Failed to send OTP. Please try again.');
                        }
                    } else if (this.selectedVerificationMethod === 'ga') {
                        this.modals.ga = true;
                    }
                },

                // Google Authenticator input handling
                updateGaInput(index, value) {
                    if (value.length <= 1 && /^\d*$/.test(value)) {
                        this.gaInputs[index] = value;
                        
                        // Auto-focus next input
                        if (value && index < 5) {
                            const nextInput = document.querySelector(`input[data-ga-index="${index + 1}"]`);
                            if (nextInput) nextInput.focus();
                        }
                    }
                },

                handleGaKeydown(index, event) {
                    // Handle backspace to move to previous input
                    if (event.key === 'Backspace' && !this.gaInputs[index] && index > 0) {
                        const prevInput = document.querySelector(`input[data-ga-index="${index - 1}"]`);
                        if (prevInput) {
                            prevInput.focus();
                            this.gaInputs[index - 1] = '';
                        }
                    }
                },

                handleGaPaste(event) {
                    event.preventDefault();
                    const pastedData = event.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
                    
                    for (let i = 0; i < 6; i++) {
                        this.gaInputs[i] = pastedData[i] || '';
                    }
                    
                    // Focus the last filled input or the first empty one
                    const lastIndex = Math.min(pastedData.length - 1, 5);
                    const targetInput = document.querySelector(`input[data-ga-index="${lastIndex}"]`);
                    if (targetInput) targetInput.focus();
                },

                // Submit Google Authenticator code
                async submitGa() {
                    const gaCode = this.gaInputs.join('');
                    if (gaCode.length !== 6) {
                        notify().error('Please enter a complete 6-digit code');
                        return;
                    }
                    
                    try {
                        const response = await fetch('{{ route("user.withdraw.ga.verify") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.getCsrfToken()
                            },
                            body: JSON.stringify({ one_time_password: gaCode })
                        });
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        
                        if (data.status === 'success') {
                            this.modals.ga = false;
                            this.gaInputs = ['', '', '', '', '', ''];
                            this.isSubmitting = true;
                            notify().success(data.message);
                            
                            const form = this.findForm();
                            if (form) {
                                this.syncFormFields(form);
                            }
                            
                            this.showPageLoader();
                            this.submitFormDirectly();
                        } else {
                            this.isSubmitting = false;
                            notify().error(data.message || 'Google Authenticator verification failed');
                            this.gaInputs = ['', '', '', '', '', ''];
                        }
                    } catch (error) {
                        this.isSubmitting = false;
                        notify().error('An error occurred during Google Authenticator verification. Please try again.');
                        this.gaInputs = ['', '', '', '', '', ''];
                    }
                },

                // Confirm cancel action
                confirmCancel() {
                    this.isSubmitting = false;
                    this.isOtpModalOpen = false;
                    this.isCancelModalOpen = false;
                    this.isVerificationChoiceModalOpen = false;
                    this.modals.ga = false;
                    this.otpInputs = ['', '', '', ''];
                    this.gaInputs = ['', '', '', '', '', ''];
                    this.selectedVerificationMethod = null;
                    this.stopOtpTimer();
                }
            }
        }
    </script>
@endsection