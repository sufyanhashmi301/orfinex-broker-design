@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Now') }}
@endsection
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

    <div x-data="withdrawForm()">
        <div class="grid grid-cols-12 gap-y-12 lg:gap-y-5 lg:gap-x-12">
            <div class="col-span-12 lg:col-span-7">
                <div class="max-w-xl">
                    <form action="{{ route('user.withdraw.now') }}" method="post" id="withdrawForm" @submit="handleSubmit">
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

        {{-- Modal for OTP (only if OTP is enabled) --}}
        @if(setting('withdraw_otp', 'misc'))
            @include('frontend::withdraw.modal.__otp_form')

            {{-- Modal for Cancel--}}
            @include('frontend::withdraw.modal.__cancel_otp')
        @endif
    </div>

@endsection

@section('script')
    <script>
        function withdrawForm() {
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
                withdrawAmountRange: '',
                conversionRate: '',
                
                // OTP related
                otpInput: '',
                isOtpModalOpen: @json(session('otp') && Carbon::now()->lt(session('otp_expiration')) ? true : false),
                isCancelModalOpen: false,
                isResendingOtp: false,
                isSubmitting: false,
                isOtpEnabled: @json((bool) setting('withdraw_otp', 'misc')),

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
                },

                // Validation helper
                validateDouble(value) {
                    return value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                },

                // Calculate percentage
                calPercentage(amount, percentage) {
                    return (parseFloat(amount) * parseFloat(percentage)) / 100;
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
                        
                        // Add HTML rows to the table
                        if (data.html) {
                            const updateDOM = () => {
                                let tableBody = this.$el?.querySelector('.selectDetailsTbody') || 
                                               document.querySelector('.selectDetailsTbody');
                                
                                if (tableBody) {
                                    const existingRows = Array.from(tableBody.children);
                                    const tempTable = document.createElement('table');
                                    tempTable.innerHTML = `<tbody>${data.html}</tbody>`;
                                    const newRows = Array.from(tempTable.querySelectorAll('tr'));
                                    
                                    if (newRows.length > 0) {
                                        const fragment = document.createDocumentFragment();
                                        
                                        // Keep the first row, add new rows
                                        if (existingRows.length > 0) {
                                            fragment.appendChild(existingRows[0].cloneNode(true));
                                        }
                                        
                                        newRows.forEach(row => {
                                            fragment.appendChild(row.cloneNode(true));
                                        });
                                        
                                        // Replace all content at once to minimize reflow
                                        tableBody.innerHTML = '';
                                        tableBody.appendChild(fragment);
                                    }
                                }
                            };
                            
                            // Use requestIdleCallback when available for better performance
                            if (window.requestIdleCallback) {
                                requestIdleCallback(updateDOM, { timeout: 100 });
                            } else {
                                requestAnimationFrame(updateDOM);
                            }
                        }
                        
                        this.withdrawAmountRange = this.info.range || '';
                        this.processingTime = this.info.processing_time || '';
                        
                    } catch (error) {
                        // Silent error handling - could add user notification here if needed
                    }
                },

                handleAmountChange() {
                    // Validate input
                    this.amount = this.validateDouble(this.amount);
                    
                    if (!this.info.charge) return;
                    
                    const charge = this.info.charge_type === 'percentage' 
                        ? this.calPercentage(this.amount, this.info.charge) 
                        : this.info.charge;
                    
                    // Update UI elements that use these values using requestAnimationFrame for better performance
                    requestAnimationFrame(() => {
                        const withdrawFeeEl = document.querySelector('.withdrawFee');
                        const payAmountEl = document.querySelector('.pay-amount');
                        
                        if (withdrawFeeEl) {
                            withdrawFeeEl.textContent = charge;
                        }
                        if (payAmountEl) {
                            const payAmount = parseFloat(((this.amount * this.info.rate) - (charge * this.info.rate)).toFixed(4));
                            payAmountEl.textContent = `${payAmount} ${this.info.pay_currency}`;
                        }
                    });
                    
                    this.convertedAmount = parseFloat((this.amount * (this.info.rate || 1)).toFixed(4)).toString();
                },

                handleConvertedAmountChange() {
                    this.convertedAmount = this.validateDouble(this.convertedAmount);
                    
                    if (!this.info.rate) return;
                    
                    this.amount = parseFloat((this.convertedAmount / this.info.rate).toFixed(4)).toString();
                    
                    const charge = this.info.charge_type === 'percentage' 
                        ? this.calPercentage(this.amount, this.info.charge) 
                        : this.info.charge;
                    
                    // Update UI elements using requestAnimationFrame for better performance
                    requestAnimationFrame(() => {
                        const withdrawFeeEl = document.querySelector('.withdrawFee');
                        const payAmountEl = document.querySelector('.pay-amount');
                        
                        if (withdrawFeeEl) {
                            withdrawFeeEl.textContent = charge;
                        }
                        if (payAmountEl) {
                            const payAmount = parseFloat(((this.amount * this.info.rate) - (charge * this.info.rate)).toFixed(4));
                            payAmountEl.textContent = `${payAmount} ${this.info.pay_currency}`;
                        }
                    });
                },

                async submitOtp() {
                    try {
                        const response = await fetch('{{ route("user.withdraw.otp.verify") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                otp: this.otpInput
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.status === 'success') {
                            this.isOtpModalOpen = false;
                            
                            if (typeof tNotify !== 'undefined') {
                                tNotify('success', data.message);
                            }
                            
                            // Show loading state
                            const pageLoader = document.getElementById('page-loader');
                            if (pageLoader) pageLoader.style.display = 'block';
                            
                            // Submit the form after successful OTP verification
                            this.submitFormAfterOtp();
                        }
                    } catch (error) {
                        if (typeof tNotify !== 'undefined') {
                            tNotify('error', 'An error occurred during OTP verification. Please try again.');
                        }
                        this.otpInput = '';
                    }
                },

                async resendOtp() {
                    this.isResendingOtp = true;
                    
                    try {
                        const response = await fetch('{{ route('user.withdraw.otp.resend') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (typeof tNotify !== 'undefined') {
                            tNotify('success', data.message);
                        }
                    } catch (error) {
                        if (typeof tNotify !== 'undefined') {
                            tNotify('error', 'An error occurred while resending the OTP. Please try again.');
                        }
                    } finally {
                        this.isResendingOtp = false;
                    }
                },

                showCancelModal() {
                    this.isCancelModalOpen = true;
                },

                closeOtpModal() {
                    this.isOtpModalOpen = false;
                    this.otpInput = ''; // Clear OTP input when closing
                },

                confirmCancel() {
                    this.isOtpModalOpen = false;
                    this.isCancelModalOpen = false;
                    this.otpInput = ''; // Clear OTP input when canceling
                },

                async handleSubmit(event) {
                    event.preventDefault(); // Prevent default form submission
                    
                    if (this.isSubmitting) return; // Prevent double submission
                    
                    // Validate required fields before submission
                    if (!this.selectedTargetId) {
                        if (typeof tNotify !== 'undefined') {
                            tNotify('error', 'Please select an account to withdraw from');
                        }
                        return;
                    }
                    
                    if (!this.selectedWithdrawAccount) {
                        if (typeof tNotify !== 'undefined') {
                            tNotify('error', 'Please select a withdraw method');
                        }
                        return;
                    }
                    
                    if (!this.amount || parseFloat(this.amount) <= 0) {
                        if (typeof tNotify !== 'undefined') {
                            tNotify('error', 'Please enter a valid amount');
                        }
                        return;
                    }
                    
                    // Check if OTP is enabled in settings
                    if (this.isOtpEnabled) {
                        // Show OTP modal for withdrawal verification
                        this.isOtpModalOpen = true;
                        return;
                    } else {
                        // OTP is disabled, submit form directly
                        const form = document.getElementById('withdrawForm');
                        if (form) {
                            // Remove the Alpine.js submit handler temporarily
                            form.removeAttribute('@submit');
                            // Submit the form normally
                            form.submit();
                        }
                        return;
                    }
                    
                    // Set loading state
                    this.isSubmitting = true;
                    
                    // Try to submit the form first (disabled for now)
                    try {
                        const form = document.getElementById('withdrawForm');
                        const formData = new FormData(form);
                        
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        
                        let result;
                        const responseText = await response.text();
                        
                        try {
                            result = JSON.parse(responseText);
                        } catch (e) {
                            
                            // If we get HTML back, it might be a successful redirect
                            if (responseText.includes('<!doctype') || responseText.includes('<html')) {
                                if (typeof tNotify !== 'undefined') {
                                    tNotify('success', 'Withdrawal submitted successfully');
                                }
                                window.location.reload();
                                return;
                            } else {
                                throw new Error('Server returned invalid response format.');
                            }
                        }
                        
                        if (result.status === 'otp_required') {
                            // Backend is requesting OTP verification
                            this.isSubmitting = false; // Reset loading state
                            this.isOtpModalOpen = true;
                        } else if (result.status === 'success') {
                            // Form submitted successfully without OTP
                            if (typeof tNotify !== 'undefined') {
                                tNotify('success', result.message);
                            }
                            
                            // Redirect if provided, otherwise reload
                            if (result.redirect_url) {
                                window.location.href = result.redirect_url;
                            } else {
                                window.location.reload();
                            }
                        } else if (result.status === 'error') {
                            // Backend returned an error
                            this.isSubmitting = false; // Reset loading state
                            if (typeof tNotify !== 'undefined') {
                                tNotify('error', result.message);
                            }
                        }
                    } catch (error) {
                        this.isSubmitting = false; // Reset loading state
                        if (typeof tNotify !== 'undefined') {
                            tNotify('error', 'An error occurred while processing your withdrawal');
                        }
                    }
                },

                submitFormAfterOtp() {
                    // Submit form after successful OTP verification
                    const form = document.getElementById('withdrawForm');
                    if (form) {
                        // Remove the Alpine.js submit handler temporarily
                        form.removeAttribute('@submit');
                        
                        // Submit the form normally
                        form.submit();
                    } else {
                        if (typeof tNotify !== 'undefined') {
                            tNotify('error', 'Form submission error');
                        }
                    }
                }
            }
        }
    </script>
@endsection
