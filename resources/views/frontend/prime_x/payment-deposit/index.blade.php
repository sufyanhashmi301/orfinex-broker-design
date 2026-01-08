@extends('frontend.prime_x.layouts.user')
@section('title')
    {{ __('Custom Payment Account Request') }}
@endsection
@section('content')

    @if ($latestRequest && $latestRequest->isPending())
        <!-- Pending Request Status -->
        <div class="card basicTable_wrapper items-center justify-center">
            <div class="card-body p-6">
                <div class="max-w-2xl progress-steps-form">
                    <div class="transaction-status text-center">
                        <div
                            class="icon h-20 w-20 bg-warning text-warning bg-opacity-30 rounded-full flex flex-col items-center justify-center mx-auto">
                            <iconify-icon icon="icomoon-free:hour-glass" class="text-4xl"></iconify-icon>
                        </div>
                        <h2 class="text-3xl dark:text-white my-5">{{ __('Custom Payment Account Request Pending') }}</h2>
                        <p class="text-sm mb-3 dark:text-white">
                            {{ __("Your payment deposit request is under review and we'll provide bank details shortly. Stay tuned!") }}
                        </p>
                        <div class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                            {{ __('Submitted on: ') }} {{ toSiteTimezone($latestRequest->submitted_at, 'M d, Y \a\t H:i') }}
                        </div>
                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <a href="{{ route('user.payment-deposit.show', $latestRequest->id) }}"
                                class="btn btn-primary inline-flex items-center justify-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:eye"></iconify-icon>
                                <span>{{ __('View Request Details') }}</span>
                            </a>
                        </div>
                        <div class="mt-5">
                            <p class="text-sm dark:text-slate-100">
                                {{ __('If you face any issue, please visit our') }}
                                <a href="{{ setting('customer_support_link', 'platform_links', 'javascript:void(0);') }}"
                                    class="btn-link">{{ __('Customer Support') }}</a>
                                {{ __('or Email us at') }}
                                <a href="mailto:{{ setting('support_email', 'global') }}"
                                    class="btn-link">{{ setting('support_email', 'global') }}</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($latestRequest && $latestRequest->isRejected())
        <!-- Rejected Request Status -->
        <div class="card basicTable_wrapper items-center justify-center">
            <div class="card-body p-6">
                <div class="max-w-2xl progress-steps-form">
                    <div class="transaction-status text-center">
                        <div
                            class="icon h-20 w-20 bg-danger text-danger bg-opacity-30 rounded-full flex flex-col items-center justify-center mx-auto">
                            <iconify-icon icon="icomoon-free:cross" class="text-4xl"></iconify-icon>
                        </div>
                        <h2 class="text-3xl dark:text-white my-5">{{ __('Custom Payment Account Request Rejected') }}</h2>
                        <p class="text-sm mb-3 dark:text-white">
                            {{ __('Unfortunately, your payment deposit request has been rejected. Please review the reason below and submit a new request if needed.') }}
                        </p>
                        @if ($latestRequest->rejection_reason)
                            <div
                                class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-200 dark:border-red-800 mb-4">
                                <p class="text-sm text-red-900 dark:text-red-100">
                                    <strong>{{ __('Reason:') }}</strong> {{ $latestRequest->rejection_reason }}
                                </p>
                            </div>
                        @endif
                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <a href="{{ route('user.payment-deposit.show', $latestRequest->id) }}"
                                class="btn btn-primary inline-flex items-center justify-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:eye"></iconify-icon>
                                <span>{{ __('View Request Details') }}</span>
                            </a>
                        </div>
                        <div class="mt-5">
                            <p class="text-sm dark:text-slate-100">
                                {{ __('If you face any issue, please visit our') }}
                                <a href="{{ setting('customer_support_link', 'platform_links', 'javascript:void(0);') }}"
                                    class="btn-link">{{ __('Customer Support') }}</a>
                                {{ __('or Email us at') }}
                                <a href="mailto:{{ setting('support_email', 'global') }}"
                                    class="btn-link">{{ setting('support_email', 'global') }}</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($latestRequest && $latestRequest->isApproved())
        <!-- Approved Request with Bank Details -->
        <div class="card basicTable_wrapper items-center justify-center">
            <div class="card-body p-6">
                <div class="max-w-2xl progress-steps-form">
                    <div class="transaction-status text-center">
                        <div
                            class="icon h-20 w-20 bg-success text-success bg-opacity-30 rounded-full flex flex-col items-center justify-center mx-auto">
                            <iconify-icon icon="lucide:check-circle" class="text-4xl"></iconify-icon>
                        </div>
                        <h2 class="text-3xl dark:text-white my-5">{{ __('Custom Payment Account Request Approved') }}</h2>
                        <p class="text-sm mb-5 dark:text-white">
                            {{ __('Great! Your payment deposit request has been approved. Please use the bank details below to make your deposit.') }}
                        </p>

                        @if ($latestRequest->bank_details)
                            <div
                                class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg border border-green-200 dark:border-green-800 text-left mb-6">
                                <h4 class="font-medium text-green-900 dark:text-green-100 mb-4 text-center">
                                    {{ __('Bank Details for Deposit') }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <span
                                            class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Bank Name') }}:</span>
                                        <div class="text-sm text-green-900 dark:text-green-100 font-medium">
                                            {{ $latestRequest->bank_details['bank_name'] ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <span
                                            class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Account Name') }}:</span>
                                        <div class="text-sm text-green-900 dark:text-green-100 font-medium">
                                            {{ $latestRequest->bank_details['account_name'] ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <span
                                            class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Account Number') }}:</span>
                                        <div class="text-sm text-green-900 dark:text-green-100 font-medium">
                                            {{ $latestRequest->bank_details['account_number'] ?? 'N/A' }}</div>
                                    </div>
                                    @if (!empty($latestRequest->bank_details['routing_number']))
                                        <div>
                                            <span
                                                class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Routing Number') }}:</span>
                                            <div class="text-sm text-green-900 dark:text-green-100 font-medium">
                                                {{ $latestRequest->bank_details['routing_number'] }}</div>
                                        </div>
                                    @endif
                                    @if (!empty($latestRequest->bank_details['swift_code']))
                                        <div>
                                            <span
                                                class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('SWIFT Code') }}:</span>
                                            <div class="text-sm text-green-900 dark:text-green-100 font-medium">
                                                {{ $latestRequest->bank_details['swift_code'] }}</div>
                                        </div>
                                    @endif
                                    @if (!empty($latestRequest->bank_details['bank_address']))
                                        <div class="md:col-span-2">
                                            <span
                                                class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Bank Address') }}:</span>
                                            <div class="text-sm text-green-900 dark:text-green-100">
                                                {{ $latestRequest->bank_details['bank_address'] }}</div>
                                        </div>
                                    @endif
                                    @if (!empty($latestRequest->bank_details['additional_instructions']))
                                        <div class="md:col-span-2">
                                            <span
                                                class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Additional Instructions') }}:</span>
                                            <div class="text-sm text-green-900 dark:text-green-100">
                                                {{ $latestRequest->bank_details['additional_instructions'] }}</div>
                                        </div>
                                    @endif
                                </div>
                                <!-- Copy Button -->
                                <div class="text-center mt-4">
                                    <button onclick="copyBankDetailsIndex()" id="copyBtnIndex"
                                        class="btn bg-white dark:bg-slate-800 border-2 border-green-500 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 inline-flex items-center justify-center transition-all duration-300">
                                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:copy"
                                            id="copyIconIndex"></iconify-icon>
                                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2 hidden" icon="lucide:check"
                                            id="checkIconIndex"></iconify-icon>
                                        <span id="copyTextIndex">{{ __('Copy Bank Details') }}</span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <a href="{{ route('user.payment-deposit.show', $latestRequest->id) }}"
                                class="btn btn-primary inline-flex items-center justify-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:eye"></iconify-icon>
                                <span>{{ __('View Request Details') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (!$latestRequest)
        <!-- New Request Form -->
        <div class="card">
            <div class="p-6">
                <h4 class="card-title mb-2">
                    {{ __('Request Custom Payment Account') }}
                </h4>
                <p class="dark:text-white">
                    {{ __('Submit your payment deposit request and receive bank details from our team for secure transactions.') }}
                </p>
            </div>
            <div class="card-body px-6 pb-6">
                <form action="{{ route('user.payment-deposit.store') }}" method="POST" id="payment-deposit-form"
                    class="space-y-4" enctype="multipart/form-data">
                    @csrf
                    @foreach ($depositQuestions as $qIndex => $depositQuestion)
                        @if ($depositQuestion->fields && is_array($depositQuestion->fields))
                            @foreach ($depositQuestion->fields as $field)
                                <div class="input-area">
                                    <div class="grid grid-cols-12">
                                        <div class="col-span-12">
                                            <label class="form-label text-lg font-medium">{{ $field['name'] }}</label>
                                        </div>
                                        @if ($field['type'] === 'text')
                                            <div class="md:col-span-6 col-span-12">
                                                <input name="fields[{{ $field['name'] }}]" class="form-control !text-lg"
                                                    type="text" value=""
                                                    @if ($field['validation'] === 'required') required @endif>
                                            </div>
                                        @elseif($field['type'] === 'date')
                                            <div class="md:col-span-6 col-span-12">
                                                <input name="fields[{{ $field['name'] }}]"
                                                    class="form-control !text-lg dateOfBirth flatpickr-payment-date"
                                                    type="text" value="" placeholder="YYYY-MM-DD" readonly
                                                    data-field-name="{{ $field['name'] }}"
                                                    @if ($field['validation'] === 'required') required @endif>
                                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                                    {{ __('Click to select a date. Previously used dates will be highlighted in green.') }}
                                                </div>
                                            </div>
                                        @elseif($field['type'] === 'checkbox')
                                            <div class="col-span-12">
                                                @foreach ($field['options'] as $index => $option)
                                                    <div class="checkbox-area mb-2">
                                                        <label
                                                            for="flexCheckDefault{{ $qIndex }}{{ $index }}"
                                                            class="inline-flex items-center cursor-pointer">
                                                            <input class="hidden" type="checkbox"
                                                                name="fields[{{ $field['name'] }}][]"
                                                                value="{{ $option }}"
                                                                id="flexCheckDefault{{ $qIndex }}{{ $index }}"
                                                                @if ($field['validation'] === 'required') required @endif />
                                                            <span
                                                                class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                                <img src="{{ asset('frontend/images/icon/ck-white.svg') }}"
                                                                    alt=""
                                                                    class="h-[10px] w-[10px] block m-auto opacity-0">
                                                            </span>
                                                            <span
                                                                class="text-slate-500 dark:text-slate-400 text-sm leading-6">
                                                                {{ $option }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($field['type'] === 'radio')
                                            <div class="col-span-12">
                                                @foreach ($field['options'] as $option)
                                                    <div class="basicRadio mb-2">
                                                        <label class="flex items-center cursor-pointer">
                                                            <input type="radio" class="hidden"
                                                                name="fields[{{ $field['name'] }}]"
                                                                value="{{ $option }}"
                                                                @if ($field['validation'] === 'required') required @endif>
                                                            <span
                                                                class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                            <span
                                                                class="text-slate-500 dark:text-slate-400 text-sm leading-6 capitalize">
                                                                {{ $option }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($field['type'] === 'dropdown')
                                            <div class="md:col-span-6 col-span-12 select2-lg">
                                                <select name="fields[{{ $field['name'] }}]"
                                                    class="select2 form-control w-full mt-2 py-2"
                                                    @if ($field['validation'] === 'required') required @endif>
                                                    <option value="">{{ __('Select an option') }}</option>
                                                    @foreach ($field['options'] as $option)
                                                        <option value="{{ $option }}"
                                                            class="inline-block font-Inter font-normal text-sm text-slate-600">
                                                            {{ $option }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @elseif($field['type'] === 'file')
                                            <div class="md:col-span-6 col-span-12">
                                                <div class="fileUpload">
                                                    <input type="file" name="fields[{{ $field['name'] }}]"
                                                        class="form-control !text-lg"
                                                        accept="image/*,.pdf,.doc,.docx,.txt"
                                                        @if ($field['validation'] === 'required') required @endif>
                                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                                        {{ __('Allowed file types: images, PDF, DOC, DOCX, TXT. Maximum size: 5MB') }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                    <div class="input-area">
                        <div class="checkbox-area">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="hidden" name="agreement" id="agreement-check" required>
                                <span
                                    class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                    <img src="{{ asset('frontend/images/icon/ck-white.svg') }}" alt=""
                                        class="h-[10px] w-[10px] block m-auto opacity-0">
                                </span>
                                <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">
                                    {{ __('I have read and agree to the terms and conditions') }}
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-right">
                            <button type="button" class="btn btn-dark save-btn inline-flex items-center justify-center" id="submitRequestBtn">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:send" id="submitIcon"></iconify-icon>
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 hidden spining-icon" icon="lucide:loader" id="submitLoader"></iconify-icon>
                                <span id="submitText">{{ __('Submit Request') }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

@endsection

@section('script')
    <script>
        // Initialize flatpickr for payment deposit date fields
        $(document).ready(function() {
            // Get previously used dates from PHP
            var previouslyUsedDates = @json($previouslyUsedDates ?? []);

            // Initialize flatpickr for payment date fields using the predefined dateOfBirth class pattern
            $('.flatpickr-payment-date').each(function() {
                var $field = $(this);
                var fieldName = $field.data('field-name');
                var fieldDates = previouslyUsedDates[fieldName] || [];

                // Initialize flatpickr with system standard configuration
                var fpInstance = $field.flatpickr({
                    dateFormat: "Y-m-d",
                    allowInput: false,
                    clickOpens: true,
                    enableTime: false,
                    onDayCreate: function(dObj, dStr, fp, dayElem) {
                        // Highlight previously used dates in green
                        var dateStr = dayElem.dateObj.toISOString().split('T')[0];
                        if (fieldDates.includes(dateStr)) {
                            dayElem.classList.add('previously-used-date');
                            dayElem.style.backgroundColor = '#10b981';
                            dayElem.style.color = 'white';
                            dayElem.style.fontWeight = 'bold';
                            dayElem.title = 'Previously used date - Click to select';
                        }
                    },
                    onChange: function(selectedDates, dateStr, instance) {
                        // Validate the selected date format
                        if (dateStr && !/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
                            instance.clear();
                            if (typeof tNotify === 'function') {
                                tNotify('error', 'Please select a valid date');
                            }
                        }
                    }
                });

                // Add information about previously used dates
                if (fieldDates.length > 0) {
                    var $helpText = $field.siblings('.text-xs');
                    var datesList = fieldDates.slice(0, 5).join(', ');
                    var moreText = fieldDates.length > 5 ? ` (and ${fieldDates.length - 5} more)` : '';
                    $helpText.html($helpText.html() + '<br><strong>Recent dates:</strong> ' + datesList +
                        moreText);
                }
            });
        });

        // Function to reset submit button loading state
        function resetSubmitButton() {
            $('#submitRequestBtn').prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
            $('#submitIcon').removeClass('hidden');
            $('#submitLoader').addClass('hidden');
            $('#submitText').text('{{ __('Submit Request') }}');
        }

        $('body').on('click', '.save-btn', function() {
            if ($('#agreement-check').is(':checked')) {
                var btn = $(this);

                // Show loading state
                btn.prop('disabled', true);
                $('#submitIcon').addClass('hidden');
                $('#submitLoader').removeClass('hidden');
                $('#submitText').text('{{ __('Submitting...') }}');

                // Safety timeout - reset button after 30 seconds if no response
                var safetyTimeout = setTimeout(function() {
                    resetSubmitButton();
                    tNotify('error', '{{ __('Request timeout. Please try again.') }}');
                }, 30000);

                // Store timeout ID so we can clear it when response is received
                window.submitSafetyTimeout = safetyTimeout;

                let form = document.querySelector('#payment-deposit-form');
                let formData = new FormData(form);
                var url = $('#payment-deposit-form').attr('action');
                submit_form(formData, btn, url, '', 'paymentDepositForm');
            } else {
                tNotify('error', '{{ __('Kindly check the agreement before proceeding!') }}')
            }
        });

        // Override global form submission callback to reset button state
        window.paymentDepositFormCallback = function(response) {
            // Clear safety timeout since we got a response
            if (window.submitSafetyTimeout) {
                clearTimeout(window.submitSafetyTimeout);
                window.submitSafetyTimeout = null;
            }

            // Always reset button state regardless of response type
            resetSubmitButton();

            if (response.success) {
                // Success handling - reload page after delay
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                // Error/validation handling - button is already reset above
                // User can now fix validation errors and resubmit
                console.log('Form submission failed:', response);
            }
        };

        // Also handle AJAX errors (network issues, server errors, etc.)
        $(document).ajaxError(function(event, xhr, settings, thrownError) {
            // Check if this is for our payment deposit form
            if (settings.url && settings.url.includes('payment-deposit')) {
                // Clear safety timeout since we got an error response
                if (window.submitSafetyTimeout) {
                    clearTimeout(window.submitSafetyTimeout);
                    window.submitSafetyTimeout = null;
                }
                resetSubmitButton();
                console.log('AJAX error occurred:', thrownError);
            }
        });

        // Reset button state on any error or if user navigates away
        $(window).on('beforeunload', function() {
            resetSubmitButton();
        });

        // Copy bank details functionality
        function copyBankDetailsIndex() {
            @if ($latestRequest && $latestRequest->isApproved() && $latestRequest->bank_details)
                const bankDetails = @json($latestRequest->bank_details);
                console.log('Bank Details Object:', bankDetails);

                let textToCopy = "Bank Details for Deposit:\n\n";

                // Check each field and add to copy text
                if (bankDetails && bankDetails.bank_name) {
                    textToCopy += `Bank Name: ${bankDetails.bank_name}\n`;
                }
                if (bankDetails && bankDetails.account_name) {
                    textToCopy += `Account Name: ${bankDetails.account_name}\n`;
                }
                if (bankDetails && bankDetails.account_number) {
                    textToCopy += `Account Number: ${bankDetails.account_number}\n`;
                }
                if (bankDetails && bankDetails.routing_number) {
                    textToCopy += `Routing Number: ${bankDetails.routing_number}\n`;
                }
                if (bankDetails && bankDetails.swift_code) {
                    textToCopy += `SWIFT Code: ${bankDetails.swift_code}\n`;
                }
                if (bankDetails && bankDetails.bank_address) {
                    textToCopy += `Bank Address: ${bankDetails.bank_address}\n`;
                }
                if (bankDetails && bankDetails.additional_instructions) {
                    textToCopy += `Additional Instructions: ${bankDetails.additional_instructions}\n`;
                }

                console.log('Text to copy:', textToCopy);

                // Get button elements
                const copyBtn = document.getElementById('copyBtnIndex');
                const copyIcon = document.getElementById('copyIconIndex');
                const checkIcon = document.getElementById('checkIconIndex');
                const copyText = document.getElementById('copyTextIndex');

                // Show loading state
                copyBtn.disabled = true;
                copyBtn.classList.add('opacity-75');

                // Try modern clipboard API first, then fallback to legacy method
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(textToCopy).then(function() {
                        handleCopySuccessIndex();
                    }).catch(function(err) {
                        console.log('Clipboard API failed, trying fallback:', err);
                        fallbackCopyTextToClipboardIndex(textToCopy);
                    });
                } else {
                    // Fallback for older browsers or non-secure context
                    fallbackCopyTextToClipboardIndex(textToCopy);
                }
            @endif
        }

        function handleCopySuccessIndex() {
            const copyBtn = document.getElementById('copyBtnIndex');
            const copyIcon = document.getElementById('copyIconIndex');
            const checkIcon = document.getElementById('checkIconIndex');
            const copyText = document.getElementById('copyTextIndex');

            // Success state
            copyIcon.classList.add('hidden');
            checkIcon.classList.remove('hidden');
            copyText.textContent = '{{ __('Copied!') }}';
            copyBtn.classList.remove('border-green-500', 'text-green-600');
            copyBtn.classList.add('border-emerald-500', 'text-emerald-600', 'bg-emerald-50', 'dark:bg-emerald-900/20');

            // Reset after 3 seconds
            setTimeout(function() {
                copyIcon.classList.remove('hidden');
                checkIcon.classList.add('hidden');
                copyText.textContent = '{{ __('Copy Bank Details') }}';
                copyBtn.classList.remove('border-emerald-500', 'text-emerald-600', 'bg-emerald-50',
                    'dark:bg-emerald-900/20');
                copyBtn.classList.add('border-green-500', 'text-green-600');
                copyBtn.disabled = false;
                copyBtn.classList.remove('opacity-75');
            }, 3000);
        }

        function handleCopyErrorIndex(err) {
            console.log('Copy failed:', err);
            const copyBtn = document.getElementById('copyBtnIndex');

            // Error state - reset button
            copyBtn.disabled = false;
            copyBtn.classList.remove('opacity-75');
        }

        function fallbackCopyTextToClipboardIndex(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;

            // Avoid scrolling to bottom
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";
            textArea.style.opacity = "0";

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    handleCopySuccessIndex();
                } else {
                    handleCopyErrorIndex('execCommand failed');
                }
            } catch (err) {
                handleCopyErrorIndex(err);
            }

            document.body.removeChild(textArea);
        }
    </script>
@endsection
