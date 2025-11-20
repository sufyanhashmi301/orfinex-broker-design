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
                    <form id="withdrawAccountForm" action="{{ route('user.withdraw.account.store') }}" method="post" enctype="multipart/form-data" @submit.prevent="startVerification">
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
                            <x-frontend::forms.button type="submit" class="w-full" size="md" variant="primary" icon="square-pen" icon-position="left"
                                x-bind:disabled="isSubmitting"
                                x-bind:loading="isSubmitting">
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
                isSubmitting: false,
                
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
  
                otpInputs: ['', '', '', ''],  // 4-digit OTP
                isResendingOtp: false,
                
                // OTP Timer
                otpExpiryTime: @json(setting('withdraw_otp_expires', 'withdraw_settings') ?? 10) * 60,  // in seconds
                otpTimer: null,
                
                // Google Authenticator inputs
                gaInputs: ['', '', '', '', '', ''],
                
                init() {
                    // Watch OTP modal state
                    this.$watch('isOtpModalOpen', (value) => {
                        if (value) {
                            this.startOtpTimer();
                        } else {
                            this.stopOtpTimer();
                        }
                    });
                    
                    // Watch verification choice modal - reset loading if closed without proceeding
                    this.$watch('isVerificationChoiceModalOpen', (value) => {
                        if (!value && this.isSubmitting && !this.selectedVerificationMethod) {
                            // Modal closed without selection, reset loading state
                            this.isSubmitting = false;
                        }
                    });
                },

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
                    if (!this.selectedVerificationMethod) {
                        this.isSubmitting = false;
                        return;
                    }
                    
                    this.isVerificationChoiceModalOpen = false;
                    this.isSubmitting = true;
                    await this.submitFormWithVerificationMethod(this.selectedVerificationMethod === 'email' ? 'otp' : 'ga');
                },

                // Helper method to find form
                findForm() {
                    return document.getElementById('withdrawAccountForm') || 
                           document.querySelector('form[action*="withdraw.account.store"]') ||
                           document.querySelector('form[method="post"]');
                },

                // Helper method to get CSRF token
                getCsrfToken() {
                    return document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
                },

                // Helper method to handle form submission response
                handleFormResponse(data, response) {
                    if (data.status === 'success') {
                        if (data.show_modal) {
                            this.isOtpModalOpen = true;
                            notify().success(data.message);
                        } else if (data.show_ga) {
                            this.modals.ga = true;
                            notify().info(data.message);
                        } else if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    } else {
                        this.isSubmitting = false;
                        notify().error(data.message || 'An error occurred');
                        
                        if (data.errors) {
                            Object.values(data.errors).flat().forEach(error => {
                                notify().error(error);
                            });
                        }
                    }
                },

                async submitFormWithVerificationMethod(method) {
                    this.isSubmitting = true;
                    
                    const form = this.findForm();
                    if (!form) {
                        this.isSubmitting = false;
                        notify().error('Form not found. Please refresh the page.');
                        return;
                    }
                    
                    const formData = new FormData(form);
                    formData.append('verification_method', method);
                    
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': this.getCsrfToken(),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        });
                        
                        const contentType = response.headers.get('content-type');
                        if (contentType?.includes('application/json')) {
                            const data = await response.json();
                            this.handleFormResponse(data, response);
                        } else {
                            window.location.reload();
                        }
                    } catch (error) {
                        this.isSubmitting = false;
                        notify().error('An error occurred while processing your request');
                    }
                },

                // OTP Methods
                async resendOtp() {
                    this.isResendingOtp = true;
                    
                    try {
                        const response = await fetch('{{ route('user.withdraw.account.resend-otp') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
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
                            
                            // Reset timer when new OTP sent
                            this.startOtpTimer();
                            
                            // Clear inputs for new code
                            this.otpInputs = ['', '', '', ''];
                            
                            // Focus first input
                            this.$nextTick(() => {
                                const firstInput = document.querySelector('[data-otp-index="0"]');
                                if (firstInput) firstInput.focus();
                            });
                        } else {
                            notify().error(data.message || 'Failed to resend OTP');
                        }
                    } catch (error) {
                        notify().error('An error occurred while resending the OTP. Please try again.');
                    } finally {
                        this.isResendingOtp = false;
                    }
                },

                async submitOtp() {
                    const otpCode = this.otpInputs.join('');
                    
                    if (otpCode.length !== 4) {
                        notify().error('Please enter the complete 4-digit code');
                        return;
                    }
                    
                    // Ensure loading state is maintained
                    this.isSubmitting = true;
                    
                    try {
                        const response = await fetch('{{ route("user.withdraw.account.verify-otp.post") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin', // Include cookies for session handling
                            body: JSON.stringify({
                                verification_code: otpCode
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
                            this.otpInputs = ['', '', '', ''];
                            
                            notify().success(data.message);
                            
                            // Redirect to the specified URL (account already created by backend)
                            if (data.redirect) {
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 1000); // Small delay to show success message
                            }
                        } else {
                            // Reset loading state on error
                            this.isSubmitting = false;
                            
                            // Handle error response from backend
                            const errorMessage = data.message || 'OTP verification failed';
                            notify().error(errorMessage);
                            
                            // Check if session expired
                            if (data.expired && data.redirect) {
                                setTimeout(() => {
                                    notify().warning('Redirecting to form...');
                                    window.location.href = data.redirect;
                                }, 2000);
                                return;
                            }
                            
                            // Clear inputs on error
                            this.otpInputs = ['', '', '', ''];
                            
                            // Focus first input for re-entry
                            this.$nextTick(() => {
                                const firstInput = document.querySelector('[data-otp-index="0"]');
                                if (firstInput) firstInput.focus();
                            });
                            
                            // If OTP expired, suggest resending
                            if (errorMessage.toLowerCase().includes('expired') && !data.expired) {
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

                // Cancel Modal Methods
                showCancelModal() {
                    this.isCancelModalOpen = true;
                },

                confirmCancel() {
                    this.isSubmitting = false;
                    this.isOtpModalOpen = false;
                    this.isCancelModalOpen = false;
                    this.modals.ga = false;
                    this.otpInputs = ['', '', '', ''];
                    this.gaInputs = ['', '', '', '', '', ''];
                    this.selectedVerificationMethod = null;
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
                    
                    // Ensure loading state is maintained
                    this.isSubmitting = true;
                    
                    try {
                        const response = await fetch('{{ route("user.withdraw.account.verify-ga.post") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({
                                one_time_password: gaCode
                            })
                        });
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const contentType = response.headers.get('content-type');
                        if (!contentType?.includes('application/json')) {
                            throw new Error('Server returned non-JSON response');
                        }
                        
                        const data = await response.json();
                        
                        if (data.status === 'success') {
                            this.modals.ga = false;
                            this.gaInputs = ['', '', '', '', '', ''];
                            
                            notify().success(data.message);
                            
                            // Redirect to the specified URL (account already created by backend)
                            if (data.redirect) {
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 1000); // Small delay to show success message
                            }
                        } else {
                            this.isSubmitting = false;
                            notify().error(data.message || '{{ __("Google Authenticator verification failed") }}');
                            this.gaInputs = ['', '', '', '', '', ''];
                        }
                    } catch (error) {
                        this.isSubmitting = false;
                        notify().error('{{ __("An error occurred during verification. Please try again.") }}');
                        this.gaInputs = ['', '', '', '', '', ''];
                    } finally {
                        this.modals.ga = false;
                    }
                },

                // Smart verification flow - main logic
                async startVerification() {
                    // Set loading state - will persist until process completes or error
                    this.isSubmitting = true;
                    
                    // Check both settings and route accordingly
                    if (this.withdrawAccountOtp && this.twoFaEnabledForUser) {
                        // Both active → Show choice modal
                        this.isVerificationChoiceModalOpen = true;
                    } else if (this.withdrawAccountOtp && !this.twoFaEnabledForUser) {
                        // Only OTP → Submit form to store endpoint (which saves data and sends OTP)
                        await this.submitFormAndShowOtpModal();
                    } else if (!this.withdrawAccountOtp && this.twoFaEnabledForUser) {
                        // Only 2FA → Submit form to store endpoint (which saves data)
                        await this.submitFormAndShowGaModal();
                    } else {
                        // Neither → Submit form directly
                        await this.submitFormDirectly();
                    }
                },
                
                // Submit form to store endpoint and show OTP modal
                async submitFormAndShowOtpModal() {
                    this.isSubmitting = true;
                    
                    const form = this.findForm();
                    if (!form) {
                        this.isSubmitting = false;
                        notify().error('{{ __("Form not found. Please refresh the page.") }}');
                        return;
                    }
                    
                    if (!form.checkValidity()) {
                        this.isSubmitting = false;
                        form.reportValidity();
                        return;
                    }
                    
                    const formData = new FormData(form);
                    
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': this.getCsrfToken(),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        });
                        
                        const contentType = response.headers.get('content-type');
                        if (contentType?.includes('application/json')) {
                            const data = await response.json();
                            
                            if (data.status === 'success' && data.show_modal) {
                                this.isOtpModalOpen = true;
                                notify().success(data.message || '{{ __("OTP has been sent to your email") }}');
                            } else {
                                this.handleFormResponse(data, response);
                            }
                        } else {
                            window.location.href = response.url || window.location.href;
                        }
                    } catch (error) {
                        this.isSubmitting = false;
                        notify().error('{{ __("An error occurred while processing your request") }}');
                    }
                },
                
                // Submit form to store endpoint and show GA modal
                async submitFormAndShowGaModal() {
                    this.isSubmitting = true;
                    
                    const form = this.findForm();
                    if (!form) {
                        this.isSubmitting = false;
                        notify().error('{{ __("Form not found. Please refresh the page.") }}');
                        return;
                    }
                    
                    if (!form.checkValidity()) {
                        this.isSubmitting = false;
                        form.reportValidity();
                        return;
                    }
                    
                    const formData = new FormData(form);
                    
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': this.getCsrfToken(),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        });
                        
                        const contentType = response.headers.get('content-type');
                        if (contentType?.includes('application/json')) {
                            const data = await response.json();
                            
                            if (data.status === 'success' && data.show_ga) {
                                this.modals.ga = true;
                                notify().info(data.message || 'Please verify with your Google Authenticator');
                            } else {
                                this.handleFormResponse(data, response);
                            }
                        } else {
                            window.location.href = response.url || window.location.href;
                        }
                    } catch (error) {
                        this.isSubmitting = false;
                        notify().error('An error occurred while processing your request');
                    }
                },

                // Submit form without verification
                async submitFormDirectly() {
                    this.isSubmitting = true;
                    
                    const form = this.findForm();
                    if (!form) {
                        this.isSubmitting = false;
                        notify().error('Form not found. Please refresh the page.');
                        return;
                    }
                    
                    if (!form.checkValidity()) {
                        this.isSubmitting = false;
                        form.reportValidity();
                        return;
                    }
                    
                    const formData = new FormData(form);
                    
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': this.getCsrfToken(),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        });
                        
                        const contentType = response.headers.get('content-type');
                        if (contentType?.includes('application/json')) {
                            const data = await response.json();
                            
                            if (data.status === 'success') {
                                notify().success(data.message || 'Account created successfully');
                                if (data.redirect) {
                                    setTimeout(() => {
                                        window.location.href = data.redirect;
                                    }, 500);
                                }
                            } else {
                                this.handleFormResponse(data, response);
                            }
                        } else {
                            window.location.href = response.url || window.location.href;
                        }
                    } catch (error) {
                        this.isSubmitting = false;
                        notify().error('An error occurred while processing your request');
                        setTimeout(() => {
                            form.submit();
                        }, 1000);
                    }
                }
            }
        }
    </script>
@endsection
