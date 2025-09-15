@extends('frontend::layouts.user')
@section('title')
    {{ __('Basic KYC') }}
@endsection
@section('content')

    @if($user->kyc ==\App\Enums\KYCStatus::Level3->value)
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] py-10 px-10">
            <div class="text-center">
                <div class="relative flex items-center justify-center z-1 mb-7">
                    <svg class="fill-success-50 dark:fill-success-500/15" width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M34.364 6.85053C38.6205 -2.28351 51.3795 -2.28351 55.636 6.85053C58.0129 11.951 63.5594 14.6722 68.9556 13.3853C78.6192 11.0807 86.5743 21.2433 82.2185 30.3287C79.7862 35.402 81.1561 41.5165 85.5082 45.0122C93.3019 51.2725 90.4628 63.9451 80.7747 66.1403C75.3648 67.3661 71.5265 72.2695 71.5572 77.9156C71.6123 88.0265 60.1169 93.6664 52.3918 87.3184C48.0781 83.7737 41.9219 83.7737 37.6082 87.3184C29.8831 93.6664 18.3877 88.0266 18.4428 77.9156C18.4735 72.2695 14.6352 67.3661 9.22531 66.1403C-0.462787 63.9451 -3.30193 51.2725 4.49185 45.0122C8.84391 41.5165 10.2138 35.402 7.78151 30.3287C3.42572 21.2433 11.3808 11.0807 21.0444 13.3853C26.4406 14.6722 31.9871 11.951 34.364 6.85053Z" fill="" fill-opacity=""></path>
                    </svg>

                    <span class="absolute -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2">
                        <svg class="fill-success-600 dark:fill-success-400" width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M32.1445 19.0002C32.1445 26.2604 26.2589 32.146 18.9987 32.146C11.7385 32.146 5.85287 26.2604 5.85287 19.0002C5.85287 11.7399 11.7385 5.85433 18.9987 5.85433C26.2589 5.85433 32.1445 11.7399 32.1445 19.0002ZM18.9987 35.146C27.9158 35.146 35.1445 27.9173 35.1445 19.0002C35.1445 10.0831 27.9158 2.85433 18.9987 2.85433C10.0816 2.85433 2.85287 10.0831 2.85287 19.0002C2.85287 27.9173 10.0816 35.146 18.9987 35.146ZM21.0001 26.0855C21.0001 24.9809 20.1047 24.0855 19.0001 24.0855L18.9985 24.0855C17.894 24.0855 16.9985 24.9809 16.9985 26.0855C16.9985 27.19 17.894 28.0855 18.9985 28.0855L19.0001 28.0855C20.1047 28.0855 21.0001 27.19 21.0001 26.0855ZM18.9986 10.1829C19.827 10.1829 20.4986 10.8545 20.4986 11.6829L20.4986 20.6707C20.4986 21.4992 19.827 22.1707 18.9986 22.1707C18.1701 22.1707 17.4986 21.4992 17.4986 20.6707L17.4986 11.6829C17.4986 10.8545 18.1701 10.1829 18.9986 10.1829Z" fill=""></path>
                        </svg>
                    </span>
                </div>
                <p class="text-lg text-gray-800 dark:text-gray-100 mb-3">
                    {{ __('Your KYC Is Verified') }}
                </p>
            </div>
        </div>
    @else
    <div x-data="kycLevel3Handler('{{ route("user.kyc.data", ":id") }}', {{ config('app.max_file_size', 10) * 1024 * 1024 }})" class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="p-5 md:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="">
                    <form 
                        action="{{ route('user.kyc.level3.submit') }}" 
                        method="post" enctype="multipart/form-data"
                        @submit="validateAndSubmit($event)">
                        @csrf
                        <div class="progress-steps-form">
                            <x-frontend::forms.select-field
                                fieldLabel="{{ __('Verification Type') }}"
                                fieldName="kyc_id"
                                x-model="selectedKycId"
                                @change="handleKycTypeChange()"
                                placeholder="{{ __('----') }}"
                                fieldRequired>
                                @foreach($kycs as $kyc)
                                    <option value="{{ $kyc->id }}">{{ $kyc->name }}</option>
                                @endforeach
                            </x-frontend::forms.select-field>
                        </div>
                        
                        <!-- Loading State -->
                        <div x-show="isLoading" x-transition class="mt-4">
                            <div class="flex items-center justify-center py-8">
                                <svg class="animate-spin h-6 w-6 text-brand-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="ml-2 text-gray-600 dark:text-gray-400">{{ __('Loading KYC fields...') }}</span>
                            </div>
                        </div>

                        <!-- Error State -->
                        <div x-show="error" x-transition class="mt-4">
                            <div class="rounded-lg bg-red-50 dark:bg-red-900/20 p-4">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                            {{ __('Error loading KYC fields') }}
                                        </h3>
                                        <p class="mt-1 text-sm text-red-700 dark:text-red-300" x-text="error">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KYC Data Container -->
                        <div x-show="kycContent && !isLoading && !error" x-transition class="mt-4" x-html="kycContent"></div>
                        
                        <x-frontend::forms.button 
                            type="submit" 
                            size="md" 
                            variant="primary" 
                            icon="check" 
                            icon-position="left"
                            x-bind:disabled="isSubmitting || !selectedKycId || !kycContent"
                            class="mt-3">
                            <span x-text="isSubmitting ? '{{ __('Submitting...') }}' : '{{ __('Submit Now') }}'"></span>
                        </x-frontend::forms.button>
                    </form>
                </div>
                <div>
                    <p class="text-sm dark:text-white mt-7 mb-3">
                        {{ __('The document you are providing must be valid at least 30 days and contain all of the following details:') }}
                    </p>
                    <figure class="figure d-block">
                        <svg alt="{{ __('verification example') }}" viewBox="0 0 320 178" class="img-fluid">
                            <use xlink:href="{{ asset('frontend/images/cards.svg#pid-passport') }}"></use>
                        </svg>
                    </figure>
                </div>
            </div>
        </div>
        <div class="p-5 md:p-6">
            <ul class="space-y-2">
                <li class="text-sm text-gray-900 dark:text-gray-300 flex space-x-2 items-center">
                    <i data-lucide="check-circle" class="w-4 h-4 text-success-600"></i>
                    <span>
                        {{ __('Upload a colorful full-size (4 sides visible) photo of the document.') }}
                    </span>
                </li>
                <li class="text-sm text-gray-900 dark:text-gray-300 flex space-x-2 items-center">
                    <i data-lucide="x-circle" class="w-4 h-4 text-error-600"></i>
                    <span>
                        {{ __('Do not upload selfies, screenshots, and do not modify the images in graphic editors.') }}
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    @endif
@endsection
@section('script')
    <script>
        function kycLevel3Handler(baseUrl, maxFileSize) {
            return {
                selectedKycId: '',
                kycContent: '',
                error: '',
                isLoading: false,
                isSubmitting: false,

                async handleKycTypeChange() {
                    if (!this.selectedKycId) {
                        this.kycContent = '';
                        return;
                    }
                    this.isLoading = true;
                    this.error = '';
                    try {
                        const res = await fetch(baseUrl.replace(':id', this.selectedKycId));
                        if (!res.ok) throw new Error('{{ __("Failed to load KYC form") }}');
                        this.kycContent = await res.text();
                    } catch (e) {
                        this.error = e.message;
                        this.kycContent = '';
                    } finally {
                        this.isLoading = false;
                    }
                },

                validateAndSubmit(e) {
                    this.error = '';

                    // Ensure a type is selected and form content loaded
                    if (!this.selectedKycId || !this.kycContent.trim()) {
                        this.error = '{{ __("Please complete the form") }}';
                        return;
                    }

                    // Validate required file inputs
                    const files = this.$el.querySelectorAll('input[type="file"][required]');
                    for (let file of files) {
                        if (!file.files.length) {
                            this.error = '{{ __("Please upload required files") }}';
                            return;
                        }
                        if (file.files[0].size > maxFileSize) {
                            this.error = '{{ __("File too large") }}';
                            return;
                        }
                    }

                    // Submit
                    this.isSubmitting = true;
                    e.target.submit();
                }
            }
        }
    </script>
@endsection
