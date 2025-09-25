@extends('frontend::user.setting.index')
@section('title')
    {{ __('Settings') }}
@endsection
@section('settings-content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
        <div>
            <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90 mb-4">
                {{ __('Authorization') }}
            </h2>
            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                {{ __('Information for logging in to :site.', ['site' => setting('site_title', 'global')]) }}
            </p>
            <p class="mt-0.5 text-gray-800 text-theme-sm dark:text-white/90">
                {{ __('Change your password whenever you think it might have been compromised.') }}
            </p>
        </div>
    </div>
    <div class="border border-gray-200 dark:border-gray-800 mb-10">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-y-3 border-b border-gray-100 px-4 py-5 last:border-b-0 dark:border-gray-800">
            <p class="text-gray-800 text-theme-sm dark:text-white/90 sm:basis-[100px] flex-shrink-1 flex-grow-1">
                {{ __('Login') }}
            </p>
            <p class="text-gray-800 text-theme-sm font-medium dark:text-white/90 w-full sm:w-[418px]">
                {{ $user->email }}
            </p>
            <div class="sm:basis-[100px] flex-shrink-1 flex-grow-1"></div>
        </div>
        <div class="flex flex-col sm:flex-row justify-between gap-y-3 border-b border-gray-100 px-4 py-5 last:border-b-0 dark:border-gray-800" x-data="{ showPasswordForm: false }">
            <p class="text-gray-800 text-theme-sm dark:text-white/90 sm:basis-[100px] flex-shrink-1 flex-grow-1">
                {{ __('Password') }}
            </p>
            <div class="w-full sm:w-[418px]">
                <!-- Password display (shown by default) -->
                <div x-show="!showPasswordForm">
                    <p class="text-gray-800 text-theme-sm font-medium dark:text-white/90">
                        {{ __('********') }}
                    </p>
                </div>

                <!-- Password change form (hidden by default) -->
                <div x-show="showPasswordForm" x-transition>
                    <form action="{{ route('user.new.password') }}" method="post" 
                        x-data="passwordChangeForm()" 
                        @submit="processing = true">
                        @csrf

                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 dark:bg-red-900 dark:border-red-700 dark:text-red-300">
                                <ul class="list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="space-y-4">
                            <x-frontend::forms.password-field
                                fieldId="current_password"
                                fieldLabel="{{ __('Current Password') }}"
                                fieldName="current_password"
                                fieldPlaceholder="{{ __('Enter Current Password') }}"
                                :fieldRequired="true"
                                x-model="currentPassword"
                            />
                            <div>
                                <x-frontend::forms.password-field
                                    fieldId="new_password"
                                    fieldLabel="{{ __('New Password') }}"
                                    fieldName="new_password"
                                    fieldPlaceholder="{{ __('Enter New Password') }}"
                                    :fieldRequired="true"
                                    x-model="newPassword"
                                />
                                <ul class="mt-2 space-y-1">
                                    <li class="text-xs" :class="passwordChecks.length ? 'text-success-600' : 'text-error-500'">
                                        {{ __('Use from 8 to 20 characters') }}
                                    </li>
                                    <li class="text-xs" :class="passwordChecks.upperLower ? 'text-success-600' : 'text-error-500'">
                                        {{ __('Use both uppercase and lowercase letters') }}
                                    </li>
                                    <li class="text-xs" :class="passwordChecks.number ? 'text-success-600' : 'text-error-500'">
                                        {{ __('At least one number') }}
                                    </li>
                                    <li class="text-xs" :class="passwordChecks.special ? 'text-success-600' : 'text-error-500'">
                                        {{ __('At least one special character(!@#$%&*():{}|<>)') }}
                                    </li>
                                </ul>
                            </div>
                           
                            <div>
                                <x-frontend::forms.password-field
                                    fieldId="new_confirm_password"
                                    fieldLabel="{{ __('Confirm Password') }}"
                                    fieldName="new_confirm_password"
                                    fieldPlaceholder="{{ __('Confirm Password') }}"
                                    :fieldRequired="true"
                                    x-model="confirmPassword"
                                />
                                 <!-- Password match indicator -->
                                 <div x-show="confirmPassword && !passwordsMatch" class="mt-2">
                                     <p class="text-xs text-error-500">
                                         ✗ {{ __('Passwords do not match') }}
                                     </p>
                                 </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-3 mt-6">
                            <x-frontend::forms.button 
                                type="submit" 
                                class="w-full" 
                                size="md" 
                                x-bind:variant="isFormValid ? 'primary' : 'outline'"
                                x-bind:disabled="!isFormValid"
                                x-bind:class="{ 'opacity-50 cursor-not-allowed': !isFormValid }"
                            >
                                {{ __('Change Password') }}
                            </x-frontend::forms.button>
                            
                            <x-frontend::forms.button type="button" variant="secondary" size="md" @click="showPasswordForm = false">
                                {{ __('Cancel') }}
                            </x-frontend::forms.button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-right sm:basis-[100px] flex-shrink-1 flex-grow-1">
                <!-- Change button (shown when form is hidden) -->
                <div x-show="!showPasswordForm">
                    <x-frontend::forms.button type="button" variant="secondary" size="md" class="w-full sm:w-auto" @click="showPasswordForm = true">
                        {{ __('Change Password') }}
                    </x-frontend::forms.button>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
        <div>
            <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90 mb-4">
                {{ __('2-Step verification') }}
            </h2>
            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                {{ __('2-step verification ensures that all sensitive transactions are authorized by you.') }}
            </p>
            <p class="mt-0.5 text-gray-800 text-theme-sm dark:text-white/90">
                {{ __('We encourage you to enter verification codes to confirm these transactions.') }}
            </p>
        </div>
    </div>
    <div class="border border-gray-200 dark:border-gray-800 mb-10">
        @include('frontend::user.setting.include.__two_fa')
    </div>
    
    <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            {{ __('Account security and termination') }}
        </h2>
    </div>
    <div class="border border-gray-200 dark:border-gray-800 mb-10">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-y-3 border-b border-gray-100 px-4 py-5 last:border-b-0 dark:border-gray-800">
            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                {{ __('Log out from all other devices except this one to secure your account.') }}
            </p>
            <x-frontend::forms.button type="button" variant="secondary" size="md" icon="log-out" iconPosition="left" class="w-full sm:w-auto">
                {{ __('Log out from other devices') }}
            </x-frontend::forms.button>
        </div>
    </div>

    <!-- Modal for email change -->
    @include('frontend::user.setting.security.modal.__edit_email')

@endsection

@section('script')
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        // Initialize modals store if not already exists
        if (!Alpine.store('modals')) {
            Alpine.store('modals', {
                current: null,
                data: {},
                
                open(modalName, payload = {}) {
                    this.current = modalName;
                    this.data = payload;
                    
                    Alpine.nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                    });
                },
                
                close() {
                    this.current = null;
                    this.data = {};
                },
                
                isOpen(modalName) {
                    return this.current === modalName;
                }
            });
        }
    });

    // Password change form component
    function passwordChangeForm() {
        return {
            processing: false,
            showPasswords: { 
                current: false, 
                new: false, 
                confirm: false 
            },
            currentPassword: '',
            newPassword: '',
            confirmPassword: '',
            
            get passwordChecks() {
                return {
                    length: this.newPassword.length >= 8 && this.newPassword.length <= 20,
                    upperLower: /[a-z]/.test(this.newPassword) && /[A-Z]/.test(this.newPassword),
                    number: /\d/.test(this.newPassword),
                    special: /[!@#$%&*():{}|<>]/.test(this.newPassword)
                }
            },
            
            get passwordsMatch() {
                return this.newPassword && this.confirmPassword && this.newPassword === this.confirmPassword;
            },
            
            get allPasswordRulesMet() {
                return this.passwordChecks.length && 
                       this.passwordChecks.upperLower && 
                       this.passwordChecks.number && 
                       this.passwordChecks.special;
            },
            
            get isFormValid() {
                return this.currentPassword && 
                       this.newPassword && 
                       this.confirmPassword && 
                       this.allPasswordRulesMet && 
                       this.passwordsMatch;
            },
            
            // Method to reset form
            resetForm() {
                this.currentPassword = '';
                this.newPassword = '';
                this.confirmPassword = '';
                this.processing = false;
            },
            
            // Method to toggle password visibility
            togglePasswordVisibility(field) {
                this.showPasswords[field] = !this.showPasswords[field];
            }
        }
    }

    function otpInput(length) {
        return {
            length,
            digits: Array(length).fill(""),
            inputs: [],

            init() {
                this.inputs = this.$el.querySelectorAll('.otp-input');
            },

            get otp() {
                return this.digits.join('');
            },

            onInput(e, i) {
                let v = e.target.value.replace(/\D/g, '').slice(-1);
                this.digits[i] = v;
                e.target.value = v;

                if (v && i < this.length - 1) {
                    this.inputs[i + 1].focus();
                }
            },

            onKeydown(e, i) {
                if (e.key === 'Backspace' && !this.digits[i] && i > 0) {
                    this.inputs[i - 1].focus();
                }
            },

            onPaste(e) {
                e.preventDefault();
                const data = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
                if (!data) return;

                [...data].forEach((ch, idx) => {
                    if (idx < this.length) {
                        this.digits[idx] = ch;
                        this.inputs[idx].value = ch;
                    }
                });

                this.inputs[Math.min(data.length - 1, this.length - 1)].focus();
            }
        }
    }
</script>
@endsection
