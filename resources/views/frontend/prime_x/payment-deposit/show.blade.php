@extends('frontend.prime_x.layouts.user')
@section('title')
    {{ __('Custom Payment Account Request Details') }}
@endsection
@section('content')

    <div class="pageTitle flex flex-col md:flex-row justify-between md:items-center flex-wrap mb-8">
        <h4
            class="font-medium lg:text-2xl text-xl capitalize text-slate-900 dark:text-white inline-block ltr:pr-4 rtl:pl-4 mb-4 md:mb-0">
            {{ __('Request #:id Details', ['id' => 'PDX' . $request->id]) }}
        </h4>
        <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-3">
            <a href="{{ route('user.payment-deposit') }}"
                class="btn btn-primary btn-sm inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:arrow-left"></iconify-icon>
                {{ __('Back to Custom Payment Account') }}
            </a>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Request Information -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="card shadow-lg border-0">
                @if ($request->isPending())
                    <div
                        class="card-header bg-gradient-to-r from-warning-50 to-orange-50 dark:from-warning-900/20 dark:to-orange-900/20 border-b border-warning-200 dark:border-warning-800">
                        <div class="flex items-center gap-4">
                            <h4 class="card-title text-slate-900 dark:text-white flex items-center">
                                <iconify-icon icon="lucide:clock" class="text-xl ltr:mr-3 rtl:ml-3"></iconify-icon>
                                {{ __('Request Status') }}
                            </h4>
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full text-xs font-medium bg-warning text-warning bg-opacity-30 border border-warning-300">
                                <iconify-icon icon="lucide:clock" class="w-3 h-3 ltr:mr-3 rtl:ml-3"></iconify-icon>
                                {{ __('Pending Review') }}
                            </span>
                        </div>
                    </div>
                    <div
                        class="card-body bg-gradient-to-br from-warning-50 to-orange-50 dark:from-warning-900/10 dark:to-orange-900/10">
                        <!-- Status Hero Section -->
                        <div class="text-center py-10 mb-6">
                            <div
                                class="icon h-20 w-20 bg-warning text-warning bg-opacity-30 rounded-full flex flex-col items-center justify-center mx-auto mb-8">
                                <iconify-icon icon="icomoon-free:hour-glass" class="text-4xl"></iconify-icon>
                            </div>
                            <h3 class="text-2xl font-bold text-warning-900 dark:text-warning-100 mb-3">
                                {{ __('Under Review') }}</h3>
                            <p class="text-warning-700 dark:text-warning-300 max-w-md mx-auto text-base leading-relaxed">
                                {{ __('Your payment deposit request is being carefully reviewed by our team. We\'ll notify you once it\'s processed.') }}
                            </p>
                        </div>
                    @elseif($request->isApproved())
                        <div
                            class="card-header bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-b border-green-200 dark:border-green-800">
                            <div class="flex items-center gap-4">
                                <h4 class="card-title text-green-900 dark:text-green-100 flex items-center">
                                    <iconify-icon icon="lucide:check-circle"
                                        class="text-xl ltr:mr-3 rtl:ml-3"></iconify-icon>
                                    {{ __('Request Status') }}
                                </h4>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-success text-success bg-opacity-30 border border-success-300">
                                    <iconify-icon icon="lucide:check" class="w-3 h-3 ltr:mr-1 rtl:ml-1"></iconify-icon>
                                    {{ __('Approved') }}
                                </span>
                            </div>
                        </div>
                        <div
                            class="card-body bg-gradient-to-br from-green-25 to-emerald-25 dark:from-green-900/10 dark:to-emerald-900/10">
                            <!-- Status Hero Section -->
                            <div class="text-center py-8 mb-6">
                                <div
                                    class="icon h-20 w-20 bg-success text-success bg-opacity-30 rounded-full flex flex-col items-center justify-center mx-auto mb-4">
                                    <iconify-icon icon="lucide:check-circle" class="text-4xl"></iconify-icon>
                                </div>
                                <h3 class="text-xl font-semibold text-green-900 dark:text-green-100 mb-2">
                                    {{ __('Approved!') }}</h3>
                                <p class="text-green-700 dark:text-green-300 max-w-sm mx-auto">
                                    {{ __('Great news! Your payment deposit request has been approved. Bank details are now available.') }}
                                </p>
                            </div>
                        @elseif($request->isRejected())
                            <div
                                class="card-header bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 border-b border-red-200 dark:border-red-800">
                                <div class="flex items-center gap-4">
                                    <h4 class="card-title text-red-900 dark:text-red-100 flex items-center">
                                        <iconify-icon icon="lucide:x-circle"
                                            class="text-xl ltr:mr-3 rtl:ml-3"></iconify-icon>
                                        {{ __('Request Status') }}
                                    </h4>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                        <iconify-icon icon="lucide:x" class="w-3 h-3 ltr:mr-1 rtl:ml-1"></iconify-icon>
                                        {{ __('Rejected') }}
                                    </span>
                                </div>
                            </div>
                            <div
                                class="card-body bg-gradient-to-br from-red-25 to-rose-25 dark:from-red-900/10 dark:to-rose-900/10">
                                <!-- Status Hero Section -->
                                <div class="text-center py-8 mb-6">
                                    <div
                                        class="icon h-20 w-20 bg-danger text-danger bg-opacity-30 rounded-full flex flex-col items-center justify-center mx-auto mb-4">
                                        <iconify-icon icon="icomoon-free:cross" class="text-4xl"></iconify-icon>
                                    </div>
                                    <h3 class="text-xl font-semibold text-red-900 dark:text-red-100 mb-2">
                                        {{ __('Request Declined') }}</h3>
                                    <p class="text-red-700 dark:text-red-300 max-w-sm mx-auto">
                                        {{ __('Unfortunately, your payment deposit request could not be approved. Please review the reason below.') }}
                                    </p>
                                </div>
                @endif

                <!-- Request Details Grid -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h5
                        class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wide mb-4 flex items-center">
                        <iconify-icon icon="lucide:info" class="text-base ltr:mr-2 rtl:ml-2"></iconify-icon>
                        {{ __('Request Information') }}
                    </h5>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">{{ __('Request ID') }}</label>
                                <div class="text-lg font-semibold text-slate-900 dark:text-white">#PDX{{ $request->id }}
                                </div>
                            </div>
                            <div>
                                <label
                                    class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">{{ __('Submitted Date') }}</label>
                                <div class="text-sm text-slate-700 dark:text-slate-300 flex items-center">
                                    <iconify-icon icon="lucide:calendar" class="text-sm ltr:mr-2 rtl:ml-2"></iconify-icon>
                                    {{ $request->submitted_at->format('M d, Y \a\t H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            @if ($request->approved_at)
                                <div>
                                    <label
                                        class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">{{ __('Processed Date') }}</label>
                                    <div class="text-sm text-slate-700 dark:text-slate-300 flex items-center">
                                        <iconify-icon icon="lucide:check" class="text-sm ltr:mr-2 rtl:ml-2"></iconify-icon>
                                        {{ $request->approved_at->format('M d, Y \a\t H:i') }}
                                    </div>
                                </div>
                            @endif
                            @if ($request->approved_by && $request->approvedBy)
                                <div>
                                    <label
                                        class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">{{ __('Processed By') }}</label>
                                    <div class="text-sm text-slate-700 dark:text-slate-300 flex items-center">
                                        <iconify-icon icon="lucide:user" class="text-sm ltr:mr-2 rtl:ml-2"></iconify-icon>
                                        {{ $request->approvedBy->name }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Responses -->
        <div class="card shadow-lg border-0">
            <div
                class="card-header bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-blue-200 dark:border-blue-800">
                <h4 class="card-title text-blue-900 dark:text-blue-100 flex items-center">
                    <iconify-icon icon="lucide:file-text" class="text-xl ltr:mr-3 rtl:ml-3"></iconify-icon>
                    {{ __('Submitted Information') }}
                </h4>
            </div>
            <div class="card-body px-6 pt-6 pb-6">
                @if ($request->sanitized_fields)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($request->sanitized_fields as $key => $value)
                            <div
                                class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                                <label
                                    class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2 block">{{ str_replace('_', ' ', $key) }}</label>
                                <div class="text-sm text-slate-900 dark:text-white font-medium">
                                    @if (is_array($value))
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($value as $item)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                                    {{ $item }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="flex items-center">
                                            <iconify-icon icon="lucide:check-circle"
                                                class="text-green-500 text-sm ltr:mr-2 rtl:ml-2"></iconify-icon>
                                            {{ $value }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div
                            class="h-16 w-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <iconify-icon icon="lucide:file-x"
                                class="text-2xl text-slate-400 dark:text-slate-500"></iconify-icon>
                        </div>
                        <h5 class="text-lg font-medium text-slate-900 dark:text-white mb-2">
                            {{ __('No Information Available') }}</h5>
                        <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                            {{ __('No form data was submitted with this request.') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Rejection Reason (if applicable) -->
        @if ($request->isRejected() && $request->rejection_reason)
            <div class="card shadow-lg border-0 border-l-4 border-l-red-500">
                <div
                    class="card-header bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 border-b border-red-200 dark:border-red-800">
                    <h4 class="card-title text-red-900 dark:text-red-100 flex items-center">
                        <iconify-icon icon="lucide:alert-triangle" class="text-xl ltr:mr-3 rtl:ml-3"></iconify-icon>
                        {{ __('Rejection Details') }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-12 w-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center">
                                    <iconify-icon icon="lucide:info" class="text-xl"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h5
                                    class="text-sm font-semibold text-red-900 dark:text-red-100 uppercase tracking-wide mb-2">
                                    {{ __('Reason for Rejection') }}</h5>
                                <p class="text-red-800 dark:text-red-200 leading-relaxed">{{ $request->rejection_reason }}
                                </p>
                                <div
                                    class="mt-4 p-3 bg-red-100 dark:bg-red-900/30 rounded-lg border border-red-200 dark:border-red-700">
                                    <p class="text-xs text-red-700 dark:text-red-300 flex items-center">
                                        <iconify-icon icon="lucide:lightbulb"
                                            class="text-sm ltr:mr-2 rtl:ml-2"></iconify-icon>
                                        {{ __('You may submit a new request after addressing the concerns mentioned above.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Bank Details Card (if approved) -->
    @if ($request->isApproved() && $request->bank_details)
        <div class="card shadow-xl border-0 overflow-hidden">
            <!-- Header with animated gradient -->
            <div class="card-header bg-gradient-to-r from-emerald-500 to-green-500 text-white border-none">
                <div class="flex items-center justify-between">
                    <h4 class="card-title text-white font-bold flex items-center">
                        <iconify-icon icon="lucide:landmark" class="text-xl ltr:mr-3 rtl:ml-3"></iconify-icon>
                        {{ __('Bank Details') }}
                    </h4>
                    <div class="flex items-center space-x-2">
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white/20 text-white">
                            <iconify-icon icon="lucide:shield-check" class="w-3 h-3 ltr:mr-1 rtl:ml-1"></iconify-icon>
                            {{ __('Verified') }}
                        </span>
                    </div>
                </div>
            </div>

            <div
                class="card-body bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20 px-6 pb-6">
                <!-- Success Alert -->
                <div class="bg-emerald-100 dark:bg-emerald-900/30 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <iconify-icon icon="lucide:check-circle" class="text-emerald-500 text-xl"></iconify-icon>
                        </div>
                        <div class="ml-3">
                            <h5 class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">
                                {{ __('Ready for Deposit') }}</h5>
                            <p class="text-xs text-emerald-700 dark:text-emerald-300 mt-1">
                                {{ __('Use the bank details below to complete your payment deposit.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Bank Details Cards -->
                <div class="space-y-4">
                    <div
                        class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-emerald-200 dark:border-emerald-800 shadow-sm">
                        <label
                            class="text-xs font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider mb-2 block">{{ __('Bank Name') }}</label>
                        <div class="text-sm font-semibold text-slate-900 dark:text-white flex items-center">
                            <iconify-icon icon="lucide:building"
                                class="text-emerald-500 ltr:mr-2 rtl:ml-2"></iconify-icon>
                            {{ $request->bank_details['bank_name'] ?? 'N/A' }}
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-emerald-200 dark:border-emerald-800 shadow-sm">
                        <label
                            class="text-xs font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider mb-2 block">{{ __('Account Name') }}</label>
                        <div class="text-sm font-semibold text-slate-900 dark:text-white flex items-center">
                            <iconify-icon icon="lucide:user" class="text-emerald-500 ltr:mr-2 rtl:ml-2"></iconify-icon>
                            {{ $request->bank_details['account_name'] ?? 'N/A' }}
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-emerald-200 dark:border-emerald-800 shadow-sm">
                        <label
                            class="text-xs font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider mb-2 block">{{ __('Account Number') }}</label>
                        <div class="text-sm font-semibold text-slate-900 dark:text-white flex items-center">
                            <iconify-icon icon="lucide:hash" class="text-emerald-500 ltr:mr-2 rtl:ml-2"></iconify-icon>
                            <span class="font-mono">{{ $request->bank_details['account_number'] ?? 'N/A' }}</span>
                        </div>
                    </div>

                    @if (!empty($request->bank_details['routing_number']))
                        <div
                            class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-emerald-200 dark:border-emerald-800 shadow-sm">
                            <label
                                class="text-xs font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider mb-2 block">{{ __('Routing Number') }}</label>
                            <div class="text-sm font-semibold text-slate-900 dark:text-white flex items-center">
                                <iconify-icon icon="lucide:route"
                                    class="text-emerald-500 ltr:mr-2 rtl:ml-2"></iconify-icon>
                                <span class="font-mono">{{ $request->bank_details['routing_number'] }}</span>
                            </div>
                        </div>
                    @endif

                    @if (!empty($request->bank_details['swift_code']))
                        <div
                            class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-emerald-200 dark:border-emerald-800 shadow-sm">
                            <label
                                class="text-xs font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider mb-2 block">{{ __('SWIFT Code') }}</label>
                            <div class="text-sm font-semibold text-slate-900 dark:text-white flex items-center">
                                <iconify-icon icon="lucide:globe"
                                    class="text-emerald-500 ltr:mr-2 rtl:ml-2"></iconify-icon>
                                <span class="font-mono">{{ $request->bank_details['swift_code'] }}</span>
                            </div>
                        </div>
                    @endif

                    @if (!empty($request->bank_details['bank_address']))
                        <div
                            class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-emerald-200 dark:border-emerald-800 shadow-sm">
                            <label
                                class="text-xs font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider mb-2 block">{{ __('Bank Address') }}</label>
                            <div class="text-sm font-medium text-slate-900 dark:text-white flex items-start">
                                <iconify-icon icon="lucide:map-pin"
                                    class="text-emerald-500 ltr:mr-2 rtl:ml-2 mt-0.5 flex-shrink-0"></iconify-icon>
                                <span>{{ $request->bank_details['bank_address'] }}</span>
                            </div>
                        </div>
                    @endif

                    @if (!empty($request->bank_details['additional_instructions']))
                        <div
                            class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-4 border border-amber-200 dark:border-amber-700">
                            <label
                                class="text-xs font-bold text-amber-700 dark:text-amber-300 uppercase tracking-wider mb-2 block flex items-center">
                                <iconify-icon icon="lucide:info" class="ltr:mr-1 rtl:ml-1"></iconify-icon>
                                {{ __('Important Instructions') }}
                            </label>
                            <div class="text-sm font-medium text-amber-800 dark:text-amber-200">
                                {{ $request->bank_details['additional_instructions'] }}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 space-y-3">
                    <button onclick="window.print()"
                        class="btn bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 text-white w-full inline-flex items-center justify-center shadow-lg">
                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:printer"></iconify-icon>
                        {{ __('Print Details') }}
                    </button>
                    <button onclick="copyBankDetails()" id="copyBtn"
                        class="btn bg-white dark:bg-slate-800 border-2 border-emerald-500 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 w-full inline-flex items-center justify-center transition-all duration-300">
                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:copy" id="copyIcon"></iconify-icon>
                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2 hidden" icon="lucide:check"
                            id="checkIcon"></iconify-icon>
                        <span id="copyText">{{ __('Copy All Details') }}</span>
                    </button>
                </div>

                <!-- Security Notice -->
                <div
                    class="mt-6 p-3 bg-slate-100 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                    <p class="text-xs text-slate-600 dark:text-slate-400 flex items-center">
                        <iconify-icon icon="lucide:shield"
                            class="text-sm ltr:mr-2 rtl:ml-2 text-slate-500"></iconify-icon>
                        {{ __('Keep these details secure and only use them for your authorized deposit.') }}
                    </p>
                </div>
            </div>
        </div>
        </div>
    @endif
    </div>

@endsection

@section('script')
    <script>
        function copyBankDetails() {
            const bankDetails = @json($request->bank_details ?? []);
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
            const copyBtn = document.getElementById('copyBtn');
            const copyIcon = document.getElementById('copyIcon');
            const checkIcon = document.getElementById('checkIcon');
            const copyText = document.getElementById('copyText');

            // Show loading state
            copyBtn.disabled = true;
            copyBtn.classList.add('opacity-75');

            // Try modern clipboard API first, then fallback to legacy method
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy).then(function() {
                    handleCopySuccess();
                }).catch(function(err) {
                    console.log('Clipboard API failed, trying fallback:', err);
                    fallbackCopyTextToClipboard(textToCopy);
                });
            } else {
                // Fallback for older browsers or non-secure context
                fallbackCopyTextToClipboard(textToCopy);
            }
        }

        function handleCopySuccess() {
            const copyBtn = document.getElementById('copyBtn');
            const copyIcon = document.getElementById('copyIcon');
            const checkIcon = document.getElementById('checkIcon');
            const copyText = document.getElementById('copyText');

            // Success state
            copyIcon.classList.add('hidden');
            checkIcon.classList.remove('hidden');
            copyText.textContent = '{{ __('Copied!') }}';
            copyBtn.classList.remove('border-emerald-500', 'text-emerald-600');
            copyBtn.classList.add('border-green-500', 'text-green-600', 'bg-green-50', 'dark:bg-green-900/20');

            // Success - notification removed as requested

            // Reset after 3 seconds
            setTimeout(function() {
                copyIcon.classList.remove('hidden');
                checkIcon.classList.add('hidden');
                copyText.textContent = '{{ __('Copy All Details') }}';
                copyBtn.classList.remove('border-green-500', 'text-green-600', 'bg-green-50',
                    'dark:bg-green-900/20');
                copyBtn.classList.add('border-emerald-500', 'text-emerald-600');
                copyBtn.disabled = false;
                copyBtn.classList.remove('opacity-75');
            }, 3000);
        }

        function handleCopyError(err) {
            console.log('Copy failed:', err);
            const copyBtn = document.getElementById('copyBtn');

            // Error state - notification removed as requested
            copyBtn.disabled = false;
            copyBtn.classList.remove('opacity-75');
        }

        function fallbackCopyTextToClipboard(text) {
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
                    handleCopySuccess();
                } else {
                    handleCopyError('execCommand failed');
                }
            } catch (err) {
                handleCopyError(err);
            }

            document.body.removeChild(textArea);
        }
    </script>
@endsection
