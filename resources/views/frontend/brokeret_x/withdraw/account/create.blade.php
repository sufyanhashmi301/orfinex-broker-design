@extends('frontend::user.setting.index')
@section('title')
    {{ __('Withdraw Account') }}
@endsection
@section('settings-content')
    <div x-data="withdrawAccountVerification()">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90 mb-1">
                    {{ __('Create Withdraw Account') }}
                </h2>
                <p class="text-gray-800 text-theme-sm dark:text-white/90">
                    {{ __("Set up a new withdrawal payment method for your account.") }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-y-12 lg:gap-y-5 lg:gap-x-12">
            <div class="col-span-12 lg:col-span-7">
                <div class="lg:max-w-xl">
                    <form action="{{ route('user.withdraw.account.store') }}" method="post" enctype="multipart/form-data" @submit.prevent="startVerification">
                        @csrf
                        
                        <!-- Form Fields -->
                        <div class="selectMethodRow">
                            <div class="input-area selectMethodCol">
                                <x-frontend::forms.label 
                                    fieldId="selectMethod"
                                    fieldLabel="{{ __('Choose Payment Method') }}"
                                    fieldRequired="true"
                                />
                                <x-frontend::forms.select
                                    fieldId="selectMethod"
                                    fieldName="withdraw_method_id"
                                    :placeholder="__('Select Payment Method')"
                                    x-model="selectedMethodId"
                                    @change="loadMethodFields">
                                    @foreach($withdrawMethods as $method)
                                        <option value="{{ $method->id }}">
                                            {{ $method->name }} ({{ ucwords($method->type) }})
                                        </option>
                                    @endforeach
                                </x-frontend::forms.select>
                                
                                <!-- Loading indicator -->
                                <div x-show="loading" class="mt-2 flex items-center text-sm text-gray-500">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ __('Loading payment method details...') }}
                                </div>
                                
                                <!-- Error message -->
                                <div x-show="error" x-text="error" class="mt-2 text-sm text-red-500"></div>
                            </div>
                        </div>
                        
                        <!-- Dynamic method fields container with proper grid layout -->
                        <template x-if="methodFields">
                            <div class="space-y-5 mt-5" x-html="methodFields"></div>
                        </template>

                        <!-- Submit Button -->
                        <div class="buttons mt-6">
                            <x-frontend::forms.button type="submit" class="w-full" size="md" variant="primary" icon="square-pen" icon-position="left">
                                {{ __('Create Withdraw Account') }}
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
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Two-Factor Authentication Required') }}</span>
                                <span class="text-sm text-gray-700 dark:text-gray-400">
                                    {{ setting('withdraw_account_otp', 'withdraw_settings') ? __('Yes') : __('No') }}
                                </span>
                            </li>
                            <li class="flex gap-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Manual Approval Required') }}</span>
                                <span class="text-sm text-gray-700 dark:text-gray-400">
                                    {{ setting('withdraw_account_approval', 'withdraw_settings') ? __('Yes') : __('No') }}
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
        
        <!-- Include OTP Modals -->
        @include('frontend::withdraw.modal.__varification_choice')
        @include('frontend::withdraw.account.modal.__otp_form')
        @include('frontend::withdraw.modal.__cancel_otp')
        @include('frontend::withdraw.modal.__ga_form')
        
    </div>

@endsection
@section('script')
    <script>
        function withdrawAccountVerification() {
            return {
                selectedMethodId: '',
                methodFields: null,
                loading: false,
                error: null,
                
                // Modal states
                isVerificationChoiceModalOpen: false,
                isOtpModalOpen: false,
                isCancelModalOpen: false,
                modals: {
                    ga: false
                },
                
                // Verification data
                selectedVerificationMethod: null,
                hasGoogleAuthenticator: @json(auth()->check() && auth()->user()->google2fa_secret ? true : false),
                withdrawAccountOtp: @json($withdrawAccountOtp ?? false),
                twoFaEnabledForUser: @json($twoFaEnabledForUser ?? false),
  
                otpInput: '',
                isResendingOtp: false,
                
                // Google Authenticator inputs
                gaInputs: ['', '', '', '', '', ''],
                
                async loadMethodFields() {
                    // Clear previous state
                    this.methodFields = null;
                    this.error = null;
                    
                    // If no method selected, return early
                    if (!this.selectedMethodId) {
                        return;
                    }
                    
                    this.loading = true;
                    
                    try {
                        const url = '{{ route("user.withdraw.method", ":id") }}'.replace(':id', this.selectedMethodId);
                        const response = await fetch(url, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html'
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        
                        const data = await response.text();
                        
                        if (data && data.trim()) {
                            this.methodFields = data;
                            
                            // Re-initialize file preview functionality after DOM update
                            this.$nextTick(() => {
                                if (typeof imagePreview === 'function') {
                                    imagePreview();
                                }
                            });
                        } else {
                            this.error = '{{ __("No payment method details available.") }}';
                        }
                        
                    } catch (error) {
                        console.error('Failed to load payment method fields:', error);
                        this.error = '{{ __("Failed to load payment method details. Please try again.") }}';
                    } finally {
                        this.loading = false;
                    }
                },

                // Verification Choice Modal Methods
                selectVerificationMethod(method) {
                    this.selectedVerificationMethod = method;
                },

                async proceedWithVerification() {
                    if (!this.selectedVerificationMethod) return;
                    
                    this.isVerificationChoiceModalOpen = false;
                    
                    if (this.selectedVerificationMethod === 'email') {
                        // Send OTP first, then show modal
                        await this.sendOtpForWithdrawAccountCreation();
                        this.isOtpModalOpen = true;
                    } else if (this.selectedVerificationMethod === 'ga') {
                        // Show GA modal directly
                        this.modals.ga = true;
                    }
                },

                async submitFormWithVerificationMethod(method) {
                    const form = document.querySelector('form[action*="withdraw.account.store"]');
                    if (!form) return;
                    
                    // Prepare form data
                    const formData = new FormData(form);
                    formData.append('verification_method', method);
                    
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        // Check if response is JSON
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            const data = await response.json();
                            
                            if (data.status === 'success') {
                                if (data.show_modal) {
                                    // OTP was sent, show OTP modal
                                    this.isOtpModalOpen = true;
                                    if (typeof notify !== 'undefined') {
                                        notify().success(data.message);
                                    }
                                } else if (data.show_ga) {
                                    // Show GA modal
                                    this.modals.ga = true;
                                    if (typeof notify !== 'undefined') {
                                        notify().info(data.message);
                                    }
                                } else if (data.redirect) {
                                    // Account created successfully, redirect
                                    window.location.href = data.redirect;
                                }
                            } else {
                                // Handle error
                                if (typeof notify !== 'undefined') {
                                    notify().error(data.message || 'An error occurred');
                                }
                            }
                        } else {
                            // Response is HTML (redirect), follow it
                            window.location.reload();
                        }
                    } catch (error) {
                        console.error('Form submission error:', error);
                        if (typeof notify !== 'undefined') {
                            notify().error('An error occurred while processing your request');
                        }
                    }
                },

                // OTP Methods (for resend functionality only)

                async sendOtpForWithdrawAccountCreation() {
                    try {
                        const response = await fetch('{{ route('user.withdraw.account.resend-otp') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.success && typeof tNotify !== 'undefined') {
                            tNotify('success', data.message || 'OTP has been sent to your email.');
                        }
                    } catch (error) {
                        if (typeof tNotify !== 'undefined') {
                            tNotify('error', 'Failed to send OTP. Please try again.');
                        }
                    }
                },

                async resendOtp() {
                    this.isResendingOtp = true;
                    
                    try {
                        const response = await fetch('{{ route('user.withdraw.account.resend-otp') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error('Server returned non-JSON response');
                        }
                        
                        const data = await response.json();
                        
                        if (data.status === 'success') {
                            notify().success(data.message);
                        } else {
                            notify().error(data.message || 'Failed to resend OTP');
                        }
                    } catch (error) {
                        console.error('Resend OTP error:', error);
                        notify().error('An error occurred while resending the OTP. Please try again.');
                    } finally {
                        this.isResendingOtp = false;
                    }
                },

                async submitOtp() {
                    if (!this.otpInput || this.otpInput.length < 4) {
                        notify().error('Please enter a valid OTP');
                        return;
                    }
                    
                    try {
                        const response = await fetch('{{ route("user.withdraw.account.verify-otp.post") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                otp: this.otpInput
                            })
                        });
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error('Server returned non-JSON response');
                        }
                        
                        const data = await response.json();
                        
                        if (data.status === 'success') {
                            this.isOtpModalOpen = false;
                            this.otpInput = '';
                            
                            notify().success(data.message);
                            
                            // Submit the form after successful verification
                            this.submitFormAfterVerification();
                        } else {
                            notify().error(data.message || 'OTP verification failed');
                            this.otpInput = '';
                        }
                    } catch (error) {
                        console.error('OTP verification error:', error);
                        notify().error('An error occurred during OTP verification. Please try again.');
                        this.otpInput = '';
                    }
                },

                // Cancel Modal Methods
                showCancelModal() {
                    this.isCancelModalOpen = true;
                },

                confirmCancel() {
                    this.isOtpModalOpen = false;
                    this.isCancelModalOpen = false;
                    this.modals.ga = false;
                    this.otpInput = '';
                    this.gaInputs = ['', '', '', '', '', ''];
                    this.selectedVerificationMethod = null;
                },

                // Google Authenticator Methods
                updateGaInput(index, value) {
                    // Only allow digits
                    if (!/^\d*$/.test(value)) return;
                    
                    this.gaInputs[index] = value;
                    
                    // Auto-focus next input
                    if (value && index < 5) {
                        const nextInput = document.querySelector(`[data-ga-index="${index + 1}"]`);
                        if (nextInput) nextInput.focus();
                    }
                },

                handleGaKeydown(index, event) {
                    // Handle backspace
                    if (event.key === 'Backspace' && !this.gaInputs[index] && index > 0) {
                        const prevInput = document.querySelector(`[data-ga-index="${index - 1}"]`);
                        if (prevInput) {
                            prevInput.focus();
                            this.gaInputs[index - 1] = '';
                        }
                    }
                    
                    // Handle arrow keys
                    if (event.key === 'ArrowLeft' && index > 0) {
                        const prevInput = document.querySelector(`[data-ga-index="${index - 1}"]`);
                        if (prevInput) prevInput.focus();
                    } else if (event.key === 'ArrowRight' && index < 5) {
                        const nextInput = document.querySelector(`[data-ga-index="${index + 1}"]`);
                        if (nextInput) nextInput.focus();
                    }
                },

                handleGaPaste(event) {
                    event.preventDefault();
                    const pastedData = event.clipboardData.getData('text').replace(/\D/g, '');
                    
                    if (pastedData.length === 6) {
                        for (let i = 0; i < 6; i++) {
                            this.gaInputs[i] = pastedData[i] || '';
                        }
                    }
                },

                async submitGa() {
                    const gaCode = this.gaInputs.join('');
                    
                    if (gaCode.length !== 6) {
                        notify().error('Please enter the complete 6-digit code');
                        return;
                    }
                    
                    // Validate that all inputs are digits
                    if (!/^\d{6}$/.test(gaCode)) {
                        notify().error('Please enter only numbers');
                        return;
                    }
                    
                    try {
                        const response = await fetch('{{ route("user.withdraw.account.verify-ga.post") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                code: gaCode
                            })
                        });
                        
                        if (!response.ok) {
                            const responseText = await response.text();
                            console.error(`HTTP ${response.status} error:`, responseText);
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            const responseText = await response.text();
                            console.error('Non-JSON response received:', responseText);
                            console.error('Content-Type:', contentType);
                            console.error('Response URL:', response.url);
                            throw new Error('Server returned non-JSON response. Check console for details.');
                        }
                        
                        const data = await response.json();
                        
                        if (data.status === 'success') {
                            this.modals.ga = false;
                            this.gaInputs = ['', '', '', '', '', ''];
                            
                            notify().success(data.message);
                            
                            // Submit the form after successful verification
                            this.submitFormAfterVerification();
                        } else {
                            this.modals.ga = false;
                            notify().error(data.message || 'Google Authenticator verification failed');
                            this.gaInputs = ['', '', '', '', '', ''];
                        }
                    } catch (error) {
                        this.modals.ga = false;
                        notify().error('An error occurred during verification. Please try again.');
                        console.error('GA verification error:', error);
                        this.gaInputs = ['', '', '', '', '', ''];
                    } finally {
                        // Always close the modal in case of error
                        this.modals.ga = false;
                    }
                },

                // Form submission after verification
                submitFormAfterVerification() {
                    const form = document.querySelector('form[action*="withdraw.account.store"]');
                    if (form) {
                        // Add a hidden field to indicate verification was completed
                        const verifiedInput = document.createElement('input');
                        verifiedInput.type = 'hidden';
                        verifiedInput.name = 'verification_completed';
                        verifiedInput.value = '1';
                        form.appendChild(verifiedInput);
                        
                        // Submit the form
                        form.submit();
                    } else {
                        notify().error('Form submission error');
                    }
                },

                // Smart verification flow - main logic
                async startVerification() {
                    // Check both settings and route accordingly
                    if (this.withdrawAccountOtp && this.twoFaEnabledForUser) {
                        // Both active → Show choice modal
                        this.isVerificationChoiceModalOpen = true;
                    } else if (this.withdrawAccountOtp && !this.twoFaEnabledForUser) {
                        // Only OTP → Send OTP first, then show modal
                        await this.sendOtpForWithdrawAccountCreation();
                        this.isOtpModalOpen = true;
                    } else if (!this.withdrawAccountOtp && this.twoFaEnabledForUser) {
                        // Only 2FA → Show GA modal directly
                        this.modals.ga = true;
                    } else {
                        // Neither → Submit form directly
                        this.submitFormDirectly();
                    }
                },

                // Submit form without verification
                async submitFormDirectly() {
                    const form = document.querySelector('form[action*="withdraw.account.store"]');
                    if (!form) return;
                    
                    // Prepare form data
                    const formData = new FormData(form);
                    
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        // Check if response is JSON
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            const data = await response.json();
                            
                            if (data.status === 'success') {
                                if (data.redirect) {
                                    // Account created successfully, redirect
                                    window.location.href = data.redirect;
                                } else {
                                    // Success message
                                    if (typeof notify !== 'undefined') {
                                        notify().success(data.message || 'Account created successfully');
                                    }
                                }
                            } else {
                                // Handle error
                                if (typeof notify !== 'undefined') {
                                    notify().error(data.message || 'An error occurred');
                                }
                            }
                        } else {
                            // Response is HTML (redirect), follow it
                            window.location.reload();
                        }
                    } catch (error) {
                        console.error('Form submission error:', error);
                        if (typeof notify !== 'undefined') {
                            notify().error('An error occurred while processing your request');
                        }
                    }
                },

                // Method selection for verification choice modal
                selectVerificationMethod(method) {
                    this.selectedVerificationMethod = method;
                },

                // Show cancel modal
                showCancelModal() {
                    this.isCancelModalOpen = true;
                },

                // Confirm cancel action
                confirmCancel() {
                    this.isOtpModalOpen = false;
                    this.isCancelModalOpen = false;
                    this.modals.ga = false;
                    this.otpInput = '';
                    this.gaInputs = ['', '', '', '', '', ''];
                    this.selectedVerificationMethod = null;
                }
            }
        }
    </script>
@endsection
