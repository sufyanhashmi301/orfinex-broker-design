@extends('frontend::layouts.user')
@section('title')
    {{ $data['method'] ?? __('Payment Gateway') }}
@endsection
@section('content')
<!-- Breadcrumb -->
<div class="mb-5">
    <ul class="m-0 p-0 list-none">
        <li class="inline-block relative top-[3px] text-base text-primary font-Inter">
            <a href="{{route('user.dashboard')}}">
                <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
            </a>
        </li>
        <li class="inline-block relative text-sm text-primary font-Inter">
            {{ __('Deposit') }}
            <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
        </li>
        <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
            {{ $data['method'] ?? __('Payment Gateway') }}
        </li>
    </ul>
</div>

<div class="grid grid-cols-12 gap-5 min-h-[calc(100vh-120px)]">
    <div class="col-span-12 flex">
        <div class="card w-full flex flex-col">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="fas fa-credit-card me-2"></i>
                    {{ __('Process Payment') }} - {{ $data['currency'] }} {{ number_format($data['pay_amount'], 2) }}
                </h4>
                <div class="flex gap-2">
                    <a href="{{ route('user.deposit.amount') }}" class="btn inline-flex justify-center btn-primary btn-sm">
                        {{ __('Deposit') }}
                    </a>
                    <button class="btn inline-flex justify-center btn-outline-dark btn-sm" onclick="closePayment()">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
            <div class="card-body p-6 flex-1 flex flex-col justify-center">
                <!-- Payment Information -->
                <div class="mb-6">
                    <div class="bg-slate-100 dark:bg-slate-700 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <h6 class="text-slate-600 dark:text-slate-300 text-sm mb-1">{{ __('Transaction ID') }}</h6>
                                <p class="text-slate-900 dark:text-white font-medium">{{ $data['txn'] }}</p>
                            </div>
                            <div>
                                <h6 class="text-slate-600 dark:text-slate-300 text-sm mb-1">{{ __('Pay Amount') }}</h6>
                                <p class="text-slate-900 dark:text-white font-medium">{{ $data['currency'] }} {{ number_format($data['pay_amount'], 2) }}</p>
                            </div>
                            <div>
                                <h6 class="text-slate-600 dark:text-slate-300 text-sm mb-1">{{ __('Base Amount') }}</h6>
                                <p class="text-slate-900 dark:text-white font-medium">{{ $data['base_currency'] }} {{ number_format($data['final_amount'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Status Container -->
                <div id="auto-payment-container" class="flex-1 flex flex-col justify-center">
                    <div class="text-center">
                        <h5 id="status-message" class="text-slate-900 dark:text-white mb-3">{{ __('Ready to process your payment') }}</h5>
                        <p class="text-slate-600 dark:text-slate-300 mb-6">{{ __('Click the button below to open the secure payment window') }}</p>

                        <!-- Main Continue Button - Requires User Click -->
                        <div id="main-continue-section" class="mb-6">
                            <button class="btn inline-flex justify-center btn-primary pulse-button" id="main-continue-btn" onclick="openPopupPayment()" style="padding: 20px 40px; font-size: 1.25rem;">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="heroicons-outline:credit-card"></iconify-icon>
                                {{ __('Open Secure Payment Window') }}
                            </button>
                            <div class="mt-3">
                                <small class="text-slate-500 dark:text-slate-400">
                                    <iconify-icon class="text-sm ltr:mr-1 rtl:ml-1" icon="heroicons-outline:shield-check"></iconify-icon>
                                    {{ __('Click to open payment gateway securely') }}
                                </small>
                            </div>
                        </div>

                        <!-- Popup Blocked Alert -->
                        <div class="alert alert-warning" id="popup-blocked-alert" style="display: none;">
                            <div class="flex items-start space-x-3 rtl:space-x-reverse">
                                <div class="flex-none">
                                    <iconify-icon class="text-2xl" icon="heroicons-outline:information-circle"></iconify-icon>
                                </div>
                                <div class="flex-1">
                                    <h5 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('Popup Blocked') }}</h5>
                                    <div class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                                        {{ __('If the payment window was blocked, please allow popups for this site and try again') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Manual Open Button -->
                        <button class="btn inline-flex justify-center btn-primary" id="manual-open-btn" onclick="openPopupPayment()" style="display: none;">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="heroicons-outline:external-link"></iconify-icon>
                            {{ __('Try Again') }}
                        </button>

                        <!-- Hidden auto-trigger button -->
                        <button class="btn inline-flex justify-center btn-primary" id="auto-trigger-btn" onclick="openPopupPayment()" style="display: none;">
                            {{ __('Auto Trigger') }}
                        </button>
                    </div>
                </div>

                <!-- Payment Monitoring Status - DISABLED -->
                <div id="payment-monitoring-container" style="display: none;">
                    <!-- All monitoring content hidden -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let paymentPopup = null;
let statusCheckInterval = null;
let autoOpenTimeout = null;

// Auto-open popup payment on page load
function autoOpenPayment() {
    console.log('Starting payment process - user interaction required');
    
    // No auto-click - just show the prominent button for user to click
    // This ensures genuine user interaction which bypasses popup blockers
    console.log('Payment ready - waiting for user to click button');
}

// Start countdown and auto-click the continue button (DISABLED)
function startCountdownAndAutoClick() {
    // Disabled: Auto-click doesn't work reliably due to popup blockers
    // User must click the button manually
    console.log('Auto-click disabled - user interaction required');
}

// Handle successful popup opening
function handlePopupOpened() {
    // Hide the main continue section
    const mainContinueSection = document.getElementById('main-continue-section');
    if (mainContinueSection) {
        mainContinueSection.style.display = 'none';
    }
    
    // Update status message to show success
    const statusMessage = document.getElementById('status-message');
    if (statusMessage) {
        statusMessage.textContent = '{{ __("Payment window opened successfully") }}';
    }
    
    // Add success message instead of monitoring view (only if not already added)
    const autoPaymentContainer = document.getElementById('auto-payment-container');
    const existingSuccess = autoPaymentContainer?.querySelector('.success-message');
    
    if (autoPaymentContainer && !existingSuccess) {
        // Create simple success message using site's alert styling with reopen button
        const successDiv = document.createElement('div');
        successDiv.className = 'text-center mt-6 success-message';
        successDiv.innerHTML = `
            <div class="alert alert-success">
                <div class="flex items-start space-x-3 rtl:space-x-reverse">
                    <div class="flex-none">
                        <iconify-icon class="text-2xl text-success-500" icon="heroicons-outline:check-circle"></iconify-icon>
                    </div>
                    <div class="flex-1">
                        <h5 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('Payment Window Opened') }}</h5>
                        <div class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                            {{ __('Complete your payment in the popup window. You can close this tab after completing payment.') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button class="btn inline-flex justify-center btn-outline-primary btn-sm" onclick="openPopupPayment()">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="heroicons-outline:external-link"></iconify-icon>
                    {{ __('Reopen Payment Window') }}
                </button>
            </div>
        `;
        autoPaymentContainer.appendChild(successDiv);
    }
    
    // Monitor popup only (no status tracking or monitoring view)
    monitorPopup();
}

// Direct popup open function (fallback)
function directPopupOpen() {
    console.log('Attempting direct popup open...');
    
    // Calculate popup position (centered)
    const width = 800;
    const height = 700;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;
    
    console.log('Attempting to open popup window...');
    
    // Open popup window
    paymentPopup = window.open(
        '{{ $data["invoice_url"] }}',
        'uniwire_payment',
        `width=${width},height=${height},left=${left},top=${top},scrollbars=yes,resizable=yes`
    );
    
    // Check if popup was blocked or failed to open
    if (!paymentPopup) {
        console.warn('Popup was blocked - paymentPopup is null');
        handlePopupBlocked();
        return;
    }
    
    console.log('Popup opened successfully');
    
    // Switch to monitoring view immediately
    showMonitoringView();
    
    // Monitor popup only (status tracking disabled)
    monitorPopup();
    
    // Additional check after a brief delay to see if popup was actually blocked
    setTimeout(function() {
        try {
            if (paymentPopup && (paymentPopup.closed || typeof paymentPopup.closed == 'undefined')) {
                console.warn('Popup was closed immediately - likely blocked');
                handlePopupBlocked();
                return;
            }
            console.log('Popup confirmed to be open and accessible');
        } catch (e) {
            console.warn('Error checking popup status:', e);
            handlePopupBlocked();
        }
    }, 500);
}

// Open popup payment window (for manual retry)
function openPopupPayment() {
    console.log('Manual popup open triggered...');
    
    // Calculate popup position (centered)
    const width = 800;
    const height = 700;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;
    
    // Close existing popup if any
    if (paymentPopup && !paymentPopup.closed) {
        paymentPopup.close();
    }
    
    // Open popup window
    paymentPopup = window.open(
        '{{ $data["invoice_url"] }}',
        'uniwire_payment',
        `width=${width},height=${height},left=${left},top=${top},scrollbars=yes,resizable=yes`
    );
    
    if (!paymentPopup || paymentPopup.closed || typeof paymentPopup.closed == 'undefined') {
        console.warn('Manual popup was also blocked');
        alert('{{ __("Popup blocked! Please allow popups for this site and try again.") }}');
        return;
    }
    
    console.log('Manual popup opened successfully');
    
    // Hide blocked alert and manual button
    document.getElementById('popup-blocked-alert').style.display = 'none';
    document.getElementById('manual-open-btn').style.display = 'none';
    
    // Call the popup opened handler
    handlePopupOpened();
}

// Handle popup being blocked
function handlePopupBlocked() {
    // Hide main continue section
    const mainContinueSection = document.getElementById('main-continue-section');
    if (mainContinueSection) {
        mainContinueSection.style.display = 'none';
    }
    
    document.getElementById('status-message').textContent = '{{ __("Payment window was blocked") }}';
    document.getElementById('popup-blocked-alert').style.display = 'block';
    document.getElementById('manual-open-btn').style.display = 'inline-block';
    
    // Show instructions to user
    alert('{{ __("Popup blocked! Please allow popups for this site and click the button below to try again.") }}');
}

// Show monitoring view
function showMonitoringView() {
    document.getElementById('auto-payment-container').style.display = 'none';
    document.getElementById('payment-monitoring-container').style.display = 'block';
}

// Monitor popup window
function monitorPopup() {
    const checkPopup = setInterval(function() {
        if (paymentPopup && paymentPopup.closed) {
            clearInterval(checkPopup);
            console.log('Popup window closed by user');
            // Don't automatically go back - user might have completed payment
            // Status check will handle completion
        }
    }, 1000);
}

// Start checking payment status
function startPaymentStatusCheck() {
    // Disabled: Do not track payment statuses in CRM view
    console.log('Payment status tracking disabled');
    return;
    
    if (statusCheckInterval) {
        clearInterval(statusCheckInterval);
    }
    
    statusCheckInterval = setInterval(function() {
        checkPaymentStatus();
    }, 3000); // Check every 3 seconds
}

// Check payment status via AJAX
function checkPaymentStatus() {
    // Disabled: Do not track payment statuses in CRM view
    return;
    
    fetch('{{ route("user.deposit.payment.status") }}?txn={{ $data["txn"] }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error('Payment status error:', data.error);
            return;
        }
        
        console.log('Payment status:', data.status);
        
        // Check transaction status
        if (data.status === 'success' || data.status === 'completed') {
            handlePaymentSuccess();
        } else if (data.status === 'failed' || data.status === 'cancelled') {
            handlePaymentFailure();
        }
        // For 'pending' status, continue checking
    })
    .catch(error => {
        console.error('Error checking payment status:', error);
    });
}

// Handle payment success
function handlePaymentSuccess() {
    // Disabled: Do not auto-redirect on payment success
    console.log('Payment success detected - but auto-redirect disabled');
    return;
    
    clearInterval(statusCheckInterval);
    if (paymentPopup) {
        paymentPopup.close();
    }
    
    // Show success message and redirect
    alert('{{ __("Payment completed successfully!") }}');
    window.location.href = '{{ route("status.success", ["txn" => $data["txn"]]) }}';
}

// Handle payment failure
function handlePaymentFailure() {
    // Disabled: Do not auto-redirect on payment failure
    console.log('Payment failure detected - but auto-redirect disabled');
    return;
    
    clearInterval(statusCheckInterval);
    if (paymentPopup) {
        paymentPopup.close();
    }
    
    // Show error message and redirect
    alert('{{ __("Payment failed or was cancelled.") }}');
    window.location.href = '{{ route("status.cancel", ["txn" => $data["txn"]]) }}';
}

// Cancel payment
function cancelPayment() {
    if (statusCheckInterval) {
        clearInterval(statusCheckInterval);
    }
    if (paymentPopup) {
        paymentPopup.close();
    }
    
    window.location.href = '{{ route("status.cancel", ["txn" => $data["txn"]]) }}';
}

// Close entire payment process
function closePayment() {
    if (confirm('{{ __("Are you sure you want to cancel this payment?") }}')) {
        cancelPayment();
    }
}

// Debug function to test auto-open manually
function debugAutoOpen() {
    console.log('=== DEBUG AUTO-OPEN ===');
    console.log('Payment popup status:', paymentPopup);
    console.log('Auto-payment container:', document.getElementById('auto-payment-container'));
    console.log('Auto-payment container display:', document.getElementById('auto-payment-container')?.style.display);
    console.log('Payment monitoring container:', document.getElementById('payment-monitoring-container'));
    console.log('Invoice URL:', '{{ $data["invoice_url"] }}');
    
    console.log('Attempting manual auto-open...');
    autoOpenPayment();
}

// Auto-start payment process on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded event fired');
    setTimeout(function() {
        console.log('Starting auto payment after 1 second delay...');
        autoOpenPayment();
    }, 1000);
});

// Also try on window load as fallback
window.addEventListener('load', function() {
    console.log('Window load event fired');
    // Only auto-open if not already opened
    if (!paymentPopup || paymentPopup.closed) {
        console.log('Window loaded, checking if auto-open needed...');
        setTimeout(function() {
            if (document.getElementById('auto-payment-container').style.display !== 'none') {
                console.log('Auto-opening from window load event...');
                autoOpenPayment();
            }
        }, 500);
    }
});

// Immediate trigger as final fallback (for faster browsers)
console.log('Setting up immediate fallback trigger...');
setTimeout(function() {
    console.log('Immediate fallback trigger executing...');
    if (!paymentPopup || paymentPopup.closed) {
        console.log('No popup detected, attempting auto-open...');
        if (document.getElementById('auto-payment-container') && 
            document.getElementById('auto-payment-container').style.display !== 'none') {
            console.log('Auto-payment container visible, opening popup...');
            autoOpenPayment();
        } else {
            console.log('Auto-payment container not visible or not found');
        }
    } else {
        console.log('Popup already exists and is open');
    }
}, 2000); // Increased to 2 seconds to ensure page is fully loaded

// Handle messages from payment window
window.addEventListener('message', function(event) {
    if (event.origin.includes('uniwire.com')) {
        console.log('Received message from Uniwire:', event.data);
        
        // Status tracking disabled - just log the messages
        if (event.data && event.data.type === 'payment_completed') {
            console.log('Payment completed message received (auto-redirect disabled)');
        } else if (event.data && event.data.type === 'payment_cancelled') {
            console.log('Payment cancelled message received (auto-redirect disabled)');
        }
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (statusCheckInterval) {
        clearInterval(statusCheckInterval);
    }
    if (paymentPopup && !paymentPopup.closed) {
        paymentPopup.close();
    }
});
</script>

<style>
.pulse-button {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
    }
    70% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
    }
}
</style>
@endsection 