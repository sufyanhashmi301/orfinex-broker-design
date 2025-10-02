@extends('frontend::layouts.user')
@section('title')
    {{ __('Custom Payment Account Request') }}
@endsection
@section('content')
    <div x-data="paymentDepositForm" x-init="initDatepickers()">
    @if ($latestRequest && $latestRequest->isPending())
        <!-- Pending Request Status -->
        <div class="outline outline-1 outline-gray-200 cursor-pointer dark:outline-gray-800 rounded-lg shadow-md p-8">
            <div class="max-w-2xl mx-auto text-center">
                <!-- Simple Icon -->
                <div class="w-16 h-16 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="clock" class="w-8 h-8 text-orange-600 dark:text-orange-400"></i>
                </div>

                <!-- Clean Typography -->
                <div class="space-y-3 mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ __('Request Under Review') }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ __("Your payment deposit request is under review and we'll provide bank details shortly.") }}
                    </p>
                </div>

                <!-- Simple Status Badge -->
                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300 mb-6">
                    <i data-lucide="clock" class="w-4 h-4 mr-2"></i>
                    {{ __('Pending Review') }}
                </div>

                <!-- Clean Action Button -->
                <div class="mb-6">
                    <x-frontend::link-button 
                        href="{{ route('user.payment-deposit.show', $latestRequest->id) }}" 
                        class="btn btn-primary" 
                        icon="eye">
                        {{ __('View Request Details') }}
                    </x-frontend::link-button>
                </div>

                <!-- Simple Support Links -->
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Need help?') }}
                    <x-frontend::text-link href="{{setting('customer_support_link','platform_links','javascript:void(0);')}}" class="text-blue-600 dark:text-blue-400 hover:underline">
                        {{ __('Contact Support') }}
                    </x-frontend::text-link>
                    {{ __('or email') }}
                    <x-frontend::text-link href="mailto:{{ setting('support_email','global')}}" class="text-blue-600 dark:text-blue-400 hover:underline">
                        {{ setting('support_email','global')}}
                    </x-frontend::text-link>
                </div>
            </div>
        </div>
    @elseif($latestRequest && $latestRequest->isRejected())
        <!-- Rejected Request Status -->
        <div class="outline outline-1 outline-gray-200 cursor-pointer dark:outline-gray-800 rounded-lg shadow-md p-8">
            <div class="max-w-2xl mx-auto text-center">
                <!-- Simple Icon -->
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="x-circle" class="w-8 h-8 text-red-600 dark:text-red-400"></i>
                </div>

                <!-- Clean Typography -->
                <div class="space-y-3 mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ __('Request Rejected') }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ __('Unfortunately, your payment deposit request has been rejected. Please review the reason below.') }}
                    </p>
                </div>

                <!-- Simple Status Badge -->
                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 mb-6">
                    <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                    {{ __('Rejected') }}
                </div>

                <!-- Rejection Reason -->
                @if ($latestRequest->rejection_reason)
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6 text-left">
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 bg-red-100 dark:bg-red-800/50 rounded-full flex items-center justify-center mt-0.5 flex-shrink-0">
                                <i data-lucide="info" class="w-3 h-3 text-red-600 dark:text-red-400"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-red-900 dark:text-red-100 mb-1">{{ __('Rejection Reason') }}</h4>
                                <p class="text-sm text-red-800 dark:text-red-200">{{ $latestRequest->rejection_reason }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Clean Action Button -->
                <div class="mb-6">
                    <x-frontend::link-button 
                        href="{{ route('user.payment-deposit.show', $latestRequest->id) }}" 
                        class="btn btn-primary" 
                        icon="eye">
                        {{ __('View Request Details') }}
                    </x-frontend::link-button>
                </div>

                <!-- Simple Support Links -->
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Need help?') }}
                    <x-frontend::text-link href="{{ setting('customer_support_link', 'platform_links', 'javascript:void(0);') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                        {{ __('Contact Support') }}
                    </x-frontend::text-link>
                    {{ __('or email') }}
                    <x-frontend::text-link href="mailto:{{ setting('support_email', 'global') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                        {{ setting('support_email', 'global') }}
                    </x-frontend::text-link>
                </div>
            </div>
        </div>
    @elseif($latestRequest && $latestRequest->isApproved())
        <!-- Approved Request with Bank Details -->
        <div class="outline outline-1 outline-gray-200 cursor-pointer dark:outline-gray-800 rounded-lg shadow-md p-8">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Simple Icon -->
                <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="check-circle" class="w-8 h-8 text-green-600 dark:text-green-400"></i>
                </div>

                <!-- Clean Typography -->
                <div class="space-y-3 mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ __('Request Approved!') }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ __('Great! Your payment deposit request has been approved. Use the bank details below to make your deposit.') }}
                    </p>
                    @if ($latestRequest->bank_details)
                        <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg border border-green-200 dark:border-green-800 text-left my-6">
                            <h4 class="text-xl font-medium text-green-900 dark:text-green-100 mb-4 text-center">
                                {{ __('Bank Details for Deposit') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Bank Name') }}:</span>
                                    <div class="text-sm text-green-900 dark:text-green-100 font-medium">
                                        {{ $latestRequest->bank_details['bank_name'] ?? 'N/A' }}
                                    </div>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Account Name') }}:</span>
                                    <div class="text-sm text-green-900 dark:text-green-100 font-medium">
                                        {{ $latestRequest->bank_details['account_name'] ?? 'N/A' }}
                                    </div>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Account Number') }}:</span>
                                    <div class="text-sm text-green-900 dark:text-green-100 font-medium">
                                        {{ $latestRequest->bank_details['account_number'] ?? 'N/A' }}
                                    </div>
                                </div>
                                @if (!empty($latestRequest->bank_details['routing_number']))
                                    <div>
                                        <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Routing Number') }}:</span>
                                        <div class="text-sm text-green-900 dark:text-green-100 font-medium">
                                            {{ $latestRequest->bank_details['routing_number'] }}
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($latestRequest->bank_details['swift_code']))
                                    <div>
                                        <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('SWIFT Code') }}:</span>
                                        <div class="text-sm text-green-900 dark:text-green-100 font-medium">
                                            {{ $latestRequest->bank_details['swift_code'] }}
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($latestRequest->bank_details['bank_address']))
                                    <div class="md:col-span-2">
                                        <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Bank Address') }}:</span>
                                        <div class="text-sm text-green-900 dark:text-green-100">
                                            {{ $latestRequest->bank_details['bank_address'] }}
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($latestRequest->bank_details['additional_instructions']))
                                    <div class="md:col-span-2">
                                        <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Additional Instructions') }}:</span>
                                        <div class="text-sm text-green-900 dark:text-green-100">
                                            {{ $latestRequest->bank_details['additional_instructions'] }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <!-- Copy Button -->
                            <div class="flex items-center justify-center gap-3 mt-6">
                                <x-frontend::forms.button type="button" x-on:click="copyBankDetails()" id="copyBtnIndex" variant="secondary" icon="copy">
                                    {{ __('Copy Bank Details') }}
                                </x-frontend::forms.button>
                                <x-frontend::link-button href="{{ route('user.payment-deposit.show', $latestRequest->id) }}" variant="primary" icon="eye">
                                    {{ __('View Request Details') }}
                                </x-frontend::link-button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if (!$latestRequest)
        <!-- New Request Form -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90 mb-2">
                    {{ __('Request Custom Payment Account') }}
                </h2>
                <p class="text-gray-800 text-theme-sm dark:text-white/90">
                    {{ __('Submit your payment deposit request and receive bank details from our team for secure transactions.') }}
                </p>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-y-12 lg:gap-y-5 lg:gap-x-12">
            <div class="col-span-12 lg:col-span-7">
                <div class="lg:max-w-xl">
                    <form action="{{ route('user.payment-deposit.store') }}" method="POST" id="payment-deposit-form" class="space-y-5" enctype="multipart/form-data">
                        @csrf
                        @foreach ($depositQuestions as $qIndex => $depositQuestion)
                            @if ($depositQuestion->fields && is_array($depositQuestion->fields))
                                @foreach ($depositQuestion->fields as $field)
                                    <div class="input-area">
                                        <x-frontend::forms.label 
                                            fieldLabel="{{ $field['name'] }}" 
                                            :fieldRequired="$field['validation'] === 'required'" />

                                        @if ($field['type'] === 'text')
                                            <x-frontend::forms.input 
                                                fieldName="fields[{{ $field['name'] }}]"
                                                fieldValue="" 
                                                :required="$field['validation'] === 'required'" />
                                        @elseif($field['type'] === 'date')
                                            <x-frontend::forms.date-input 
                                                fieldName="fields[{{ $field['name'] }}]"
                                                fieldValue=""
                                                fieldPlaceholder="YYYY-MM-DD"
                                                :required="$field['validation'] === 'required'"
                                                class="dateOfBirth flatpickr-payment-date"
                                                data-field-name="{{ $field['name'] }}" />
                                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                                {{ __('Click to select a date. Previously used dates will be highlighted in green.') }}
                                            </div>
                                        @elseif($field['type'] === 'checkbox')
                                            @foreach ($field['options'] as $index => $option)
                                                <div class="mb-3">
                                                    <x-frontend::forms.checkbox 
                                                        fieldId="checkbox_{{ $qIndex }}_{{ $index }}"
                                                        fieldName="fields[{{ $field['name'] }}][]"
                                                        fieldValue="{{ $option }}"
                                                        :label="$option"
                                                        checked="false"
                                                        :required="$field['validation'] === 'required'" />
                                                </div>
                                            @endforeach
                                        @elseif($field['type'] === 'radio')
                                            <div class="flex items-center space-x-7 flex-wrap">
                                                @foreach ($field['options'] as $option)
                                                <div class="mb-2">
                                                    <x-frontend::forms.radio 
                                                        fieldName="fields[{{ $field['name'] }}]"
                                                        fieldValue="{{ $option }}"
                                                        :label="$option"
                                                        :required="$field['validation'] === 'required'" />
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($field['type'] === 'dropdown')
                                            <x-frontend::forms.select 
                                                fieldName="fields[{{ $field['name'] }}]"
                                                fieldValue="" 
                                                placeholder="{{ __('Select an option') }}"
                                                :required="$field['validation'] === 'required'">
                                                @foreach ($field['options'] as $option)
                                                    <option value="{{ $option }}">
                                                        {{ $option }}
                                                    </option>
                                                @endforeach
                                            </x-frontend::forms.select>
                                        @elseif($field['type'] === 'file')
                                            <div class="fileUpload">
                                                <x-frontend::forms.file-input 
                                                    fieldName="fields[{{ $field['name'] }}]"
                                                    :required="$field['validation'] === 'required'"
                                                    accept="image/*,.pdf,.doc,.docx,.txt" />
                                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                                        {{ __('Allowed file types: images, PDF, DOC, DOCX, TXT. Maximum size: 5MB') }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                        <div class="input-area">
                            <x-frontend::forms.checkbox 
                                fieldId="agreement-check"
                                fieldName="agreement"
                                label="{{ __('I have read and agree to the terms and conditions') }}"
                                checked="false"
                                required />
                            </div>
                        <div class="mt-10">
                            <x-frontend::forms.button 
                                type="button" 
                                class="w-full" 
                                size="md" 
                                variant="primary" 
                                icon="arrow-right" 
                                icon-position="right"
                                x-on:click="submitForm()"
                                x-bind:disabled="isSubmitting"
                                x-text="submitText || '{{ __('Submit Request') }}'">
                                {{ __('Submit Request') }}
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
                </div>
            </div>
        </div>
    @endif
    </div>
@endsection

@section('script')
    <script>
        function initPaymentDepositForm() {
            if (typeof Alpine === 'undefined') {
                console.error('Alpine.js is not loaded');
                return;
            }
            
            Alpine.data("paymentDepositForm", () => ({
                // State
                previouslyUsedDates: @json($previouslyUsedDates ?? []),
                isSubmitting: false,
                safetyTimeout: null,
                submitText: @json(__('Submit Request')),
                
                // Debug
                init() {
                    console.log('PaymentDepositForm Alpine component initialized');
                },

                // Init flatpickr
                initDatepickers() {
                    const that = this;
                    document.querySelectorAll('.flatpickr-payment-date').forEach(field => {
                        let fieldName = field.dataset.fieldName;
                        let fieldDates = that.previouslyUsedDates[fieldName] || [];

                        flatpickr(field, {
                            dateFormat: "Y-m-d",
                            allowInput: false,
                            clickOpens: true,
                            enableTime: false,
                            onDayCreate(dObj, dStr, fp, dayElem) {
                                const dateStr = dayElem.dateObj.toISOString().split('T')[0];
                                if (fieldDates.includes(dateStr)) {
                                    dayElem.classList.add('previously-used-date');
                                    dayElem.style.backgroundColor = '#10b981';
                                    dayElem.style.color = 'white';
                                    dayElem.style.fontWeight = 'bold';
                                    dayElem.title = 'Previously used date - Click to select';
                                }
                            },
                            onChange(selectedDates, dateStr, instance) {
                                if (dateStr && !/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
                                    instance.clear();
                                    if (typeof notify === 'function') {
                                        notify().error('Please select a valid date');
                                    }
                                }
                            }
                        });

                        // Show recent dates
                        if (fieldDates.length > 0) {
                            let helpText = field.closest('.input-area').querySelector('.text-xs');
                            if (helpText) {
                                let datesList = fieldDates.slice(0, 5).join(', ');
                                let moreText = fieldDates.length > 5 ? ' (and ' + (fieldDates.length - 5) + ' more)' : '';
                                helpText.innerHTML += '<br><strong>Recent dates:</strong> ' + datesList + moreText;
                            }
                        }
                    });
                },

                // Reset button
                resetButton() {
                    this.isSubmitting = false;
                    this.submitText = @json(__('Submit Request'));
                    if (this.safetyTimeout) {
                        clearTimeout(this.safetyTimeout);
                        this.safetyTimeout = null;
                    }
                },

                // Submit form (using fetch instead of submit_form)
                submitForm() {
                    const agreementCheck = document.querySelector('#agreement-check');
                    if (!agreementCheck || !agreementCheck.checked) {
                        if (typeof notify === 'function') {
                            notify().error(@json(__('Kindly check the agreement before proceeding!')));
                        }
                        return;
                    }

                    this.isSubmitting = true;
                    this.submitText = @json(__('Submitting...'));

                    this.safetyTimeout = setTimeout(() => {
                        this.resetButton();
                        if (typeof notify === 'function') {
                            notify().error(@json(__('Request timeout. Please try again.')));
                        }
                    }, 30000);

                    let form = document.querySelector('#payment-deposit-form');
                    let formData = new FormData(form);
                    let url = form.getAttribute('action');

                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(res => res.json())
                    .then(response => {
                        this.resetButton();
                        if (response.success) {
                            if (typeof notify === 'function') {
                                notify().success(response.message || 'Success!');
                            }
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            if (typeof notify === 'function') {
                                notify().error(response.message || 'Something went wrong.');
                            }
                        }
                    })
                    .catch(() => {
                        this.resetButton();
                        if (typeof notify === 'function') {
                            notify().error('Server error. Please try again.');
                        }
                    });
                },

                // Copy bank details
                copyBankDetails() {
                    const bankDetails = @json(isset($latestRequest) && $latestRequest && $latestRequest->isApproved() ? $latestRequest->bank_details : null);
                    if (!bankDetails) return;

                    let textToCopy = "Bank Details for Deposit:\n\n";
                    if (bankDetails.bank_name) textToCopy += "Bank Name: " + bankDetails.bank_name + "\n";
                    if (bankDetails.account_name) textToCopy += "Account Name: " + bankDetails.account_name + "\n";
                    if (bankDetails.account_number) textToCopy += "Account Number: " + bankDetails.account_number + "\n";
                    if (bankDetails.routing_number) textToCopy += "Routing Number: " + bankDetails.routing_number + "\n";
                    if (bankDetails.swift_code) textToCopy += "SWIFT Code: " + bankDetails.swift_code + "\n";
                    if (bankDetails.bank_address) textToCopy += "Bank Address: " + bankDetails.bank_address + "\n";
                    if (bankDetails.additional_instructions) textToCopy += "Additional Instructions: " + bankDetails.additional_instructions + "\n";

                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(textToCopy).then(() => {
                            if (typeof notify === 'function') {
                                notify().success(@json(__("Copied!")));
                            }
                        }).catch(() => {
                            this.fallbackCopy(textToCopy);
                        });
                    } else {
                        this.fallbackCopy(textToCopy);
                    }
                },

                // Fallback copy
                fallbackCopy(text) {
                    const textArea = document.createElement("textarea");
                    textArea.value = text;
                    textArea.style.position = "fixed";
                    textArea.style.opacity = "0";
                    
                    document.body.appendChild(textArea);
                    textArea.select();
                    
                    try {
                        const successful = document.execCommand('copy');
                        if (successful && typeof notify === 'function') {
                            notify().success(@json(__("Copied!")));
                        } else if (typeof notify === 'function') {
                            notify().error(@json(__("Copy failed, please try again.")));
                        }
                    } catch (err) {
                        if (typeof notify === 'function') {
                            notify().error(@json(__("Copy failed, please try again.")));
                        }
                    }
                    
                    document.body.removeChild(textArea);
                }
            }));
        }

        // Initialize
        if (typeof Alpine !== 'undefined') {
            initPaymentDepositForm();
        } else {
            document.addEventListener("alpine:init", initPaymentDepositForm);
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Alpine !== 'undefined') {
                    initPaymentDepositForm();
                }
            });
        }
    </script>
@endsection
