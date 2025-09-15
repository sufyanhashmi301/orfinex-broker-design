@extends('frontend::layouts.user')

@section('title')
    {{ __('Basic KYC') }}
@endsection

@section('content')
    @if($user->kyc ==  \App\Enums\KYCStatus::Pending->value)
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] py-10 px-10">
            <div class="flex items-center justify-center flex-col gap-3">
                <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26 19.875V30.9167" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M25.988 37.5417H26.0075" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                    {{ __('Your KYC is Pending') }}
                </p>
            </div>
        </div>
    @elseif($user->kyc ==  \App\Enums\KYCStatus::Level2->value)
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] py-10 px-10">
            <div class="flex items-center justify-center flex-col gap-3">
                <iconify-icon class="text-success" icon="solar:user-check-bold" style="font-size: 52px;"></iconify-icon>
                <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                    {{ __('Your KYC is Verified') }}
                </p>
            </div>
        </div>
    @else
        <div x-data="kycHandler('{{ route("user.kyc.data", ":id") }}', {{ config('app.max_file_size', 10) * 1024 * 1024 }})" class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="p-5 md:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <form 
                            @submit.prevent="validateAndSubmit" 
                            action="{{ route('user.kyc.submit') }}" 
                            method="post" 
                            enctype="multipart/form-data">
                            @csrf
                            <div class="progress-steps-form">
                                <label for="kycTypeSelect" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    {{ __('Verification Type') }}
                                </label>
                                <div class="relative">
                                    <select 
                                        x-model="selectedKycId"
                                        @change="handleKycTypeChange()"
                                        name="kyc_id" 
                                        id="kycTypeSelect" 
                                        :disabled="isLoading"
                                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 disabled:opacity-50 disabled:cursor-not-allowed" required>
                                        <option selected>{{ __('----') }}</option>
                                        @foreach($kycs as $kyc)
                                            <option value="{{ $kyc->id }}">{{ $kyc->name }}</option>
                                        @endforeach
                                    </select>
                                    
                                </div>
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
                            {{ __('The document you are providing must be valid for at least 30 days and contain all of the following details:') }}
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
    @endif
@endsection

@section('script')
    <script>
        function kycHandler(baseUrl, maxFileSize) {
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
