@extends('frontend::user.setting.index')
@section('title')
    {{ __('Veriff Identity Verification') }}
@endsection
@section('settings-content')
<div class="card">
    <div class="card-body px-6 pt-3">
        @if (auth()->user()->kyc >= kyc_required_completed_level())
        {{-- verification completed--}}
        <div class="text-center py-8">
            <iconify-icon icon="lucide:check-circle" class="text-6xl text-primary mb-4"></iconify-icon>
            <h4 class="text-xl font-semibold text-slate-900 dark:text-white mb-2">
                {{ __('Verification Completed') }}
            </h4>
            <p class="text-slate-600 dark:text-slate-400">
                {{ __('Your identity verification has been successfully completed.') }}
            </p>
        </div>
        @elseif (!isset($veriffstatus) || $veriffstatus === 0)
        {{-- veriff deactivated --}}
        <div class="text-center py-8">
            <iconify-icon icon="lucide:alert-circle" class="text-6xl text-orange-500 mb-4"></iconify-icon>
            <h4 class="text-xl font-semibold text-slate-900 dark:text-white mb-2">
                {{ __('Service Unavailable') }}
            </h4>
            <p class="text-slate-600 dark:text-slate-400">
                {{ __('Veriff verification service is currently unavailable.') }}
            </p>
        </div>
        @else
        {{-- Veriff account verification --}}
        <div class="mb-6">
            <h4 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                <iconify-icon icon="lucide:shield-check" class="text-primary text-2xl"></iconify-icon>
                {{ __('Identity Verification with Veriff') }}
            </h4>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed" id="verification-message">
                {{ __('Preparing your secure verification session. This will only take a moment...') }}
            </p>
            
            {{-- Progress indicator --}}
            <div class="mt-4" id="verification-progress">
                <div class="flex items-center justify-center space-x-2">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-primary rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-primary rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-primary rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                    <span class="text-xs text-slate-500 dark:text-slate-400 ml-2">Initializing...</span>
                </div>
            </div>
        </div>

        <div class="border border-slate-100 dark:border-slate-700 rounded-lg p-6 bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-900">
            <div class="text-center">
                {{-- Enhanced loading state --}}
                <div id="loading-spinner" class="mb-6">
                    <div class="inline-flex items-center px-6 py-3 bg-white dark:bg-slate-700 rounded-lg shadow-sm border border-slate-200 dark:border-slate-600">
                        <div class="animate-spin rounded-full h-5 w-5 border-2 border-primary border-t-transparent mr-3"></div>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-100" id="loading-text">{{ __('Setting up verification...') }}</span>
                    </div>
                </div>
                
                {{-- Veriff SDK container with simple centered layout --}}
                <div class="flex justify-center items-center min-h-[200px] w-full">
                    <div class="w-full max-w-md mx-auto text-center">
                        <div id='veriff-root' class="mx-auto text-center"></div>
                    </div>
                </div>
                
                {{-- Success message after button appears --}}
                <div id="verification-ready" class="mt-4 hidden">
                    <div class="inline-flex items-center px-4 py-2 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                        <iconify-icon icon="lucide:check-circle" class="text-green-600 dark:text-green-400 mr-2"></iconify-icon>
                        <span class="text-sm text-green-700 dark:text-green-300">{{ __('Verification ready! Click the button above.') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Fallback iframe container --}}
        <div id="direct-iframe-container" style="display: none; margin-top: 20px;">
            <iframe id="direct-veriff-iframe" 
                    class="w-full h-96 border-0 rounded-lg shadow-lg"
                    allow="camera; microphone; fullscreen; geolocation">
            </iframe>
        </div>
        
        <div id="verification-status" class="mt-4 text-center" style="display: none;">
            <p class="text-slate-600 dark:text-slate-400">
                {{ __('Verification in progress...') }}
            </p>
        </div>
        @endif
    </div>
</div>
@endsection

@section('style')
<style>
    /* Simple, non-aggressive centering for Veriff */
    #veriff-root {
        text-align: center;
        width: 100%;
    }
    
    /* Center buttons with minimal interference */
    #veriff-root button,
    #veriff-root input[type="submit"] {
        margin: 8px auto;
        display: block;
    }
    
    /* Ensure forms are centered */
    #veriff-root form {
        text-align: center;
        margin: 0 auto;
    }
    
    /* Optional: Style buttons without breaking SDK */
    #veriff-root button {
        border-radius: 8px;
        font-weight: 600;
        padding: 12px 24px;
        min-width: 140px;
        transition: all 0.2s ease;
    }
    
    #veriff-root button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>
@endsection

@section('script')
{{-- Veriff Official SDKs --}}
<script src='https://cdn.veriff.me/sdk/js/1.5/veriff.min.js'></script>
<script src='https://cdn.veriff.me/incontext/js/v1/veriff.js'></script>

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

@if(isset($credentials) && $veriffstatus === 1)
// Initialize Veriff SDK following official documentation
const veriff = Veriff({
    host: '{{ $credentials->base_url ?? "https://stationapi.veriff.com" }}',
    apiKey: '{{ $credentials->api_key }}',
    parentId: 'veriff-root',
    onSession: function(err, response) {
        if (err) {
            console.error('Veriff session error:', err);
            updateKycStatus('error');
            return;
        }
        
        console.log('Veriff session created:', response);
        
        // Update session ID in backend
        if (response.verification && response.verification.id) {
            updateUserSessionId(response.verification.id);
        }
        
        // Show verification URL - go directly to new tab (most reliable)
        console.log('Veriff verification URL:', response.verification.url);
        
        // Since iframes often fail due to security restrictions, 
        // let's go directly to the most reliable approach
        openInNewTab(response.verification.url);
        
        // Update UI - hide loading spinner and show instructions
        updateVerificationText();
        $('#verification-status').show();
    }
});

// Set user parameters (pre-populate data)
veriff.setParams({
    person: {
        givenName: '{{ $user->first_name ?? "" }}',
        lastName: '{{ $user->last_name ?? "" }}'
    },
    vendorData: '{{ $user->id }}', // Our internal user ID
    endUserId: '{{ $endUserId }}' // UUID or user-X format for Veriff
});

// Simple update when verification is ready
function updateVerificationText() {
    // Hide loading elements
    $('#loading-spinner').fadeOut(300);
    $('#verification-progress').fadeOut(300);
    
    // Update message
    $('#verification-message').html('{{ __("Your verification session is ready. Click the Get Verified button below to begin.") }}');
    
    // Show success indicator
    setTimeout(function() {
        $('#verification-ready').removeClass('hidden').hide().fadeIn(400);
    }, 400);
    
    console.log('Verification ready');
}

// Simple SDK initialization
$(document).ready(function() {
    // Start Veriff SDK after a short delay
    setTimeout(function() {
        console.log('Starting Veriff verification...');
        
        // Mount Veriff SDK
        veriff.mount({
            submitBtnText: '{{ __("Get verified") }}'
        });
        
        // Update UI after mounting
        setTimeout(function() {
            updateVerificationText();
        }, 2000);
    }, 1500);
});

// Handle Veriff InContext SDK events
function handleVeriffEvent(msg) {
    console.log('Handling Veriff event:', msg);
    
    switch(msg) {
        case 'STARTED':
            console.log('Verification started');
            $('#verification-status').html('<p class="text-blue-600">{{ __("Verification started...") }}</p>');
            break;
        case 'SUBMITTED':
            console.log('Verification submitted');
            $('#verification-status').html('<p class="text-blue-600">{{ __("Verification submitted for review...") }}</p>');
            break;
        case 'FINISHED':
            console.log('Verification finished');
            updateKycStatus('finished');
            $('#verification-status').html('<p class="text-green-600">{{ __("Verification completed! Please wait for review.") }}</p>');
            $('#start-veriff-btn').hide();
            break;
        case 'CANCELED':
            console.log('Verification canceled');
            updateKycStatus('canceled');
            $('#verification-status').html('<p class="text-yellow-600">{{ __("Verification was canceled.") }}</p>');
            $('#start-veriff-btn').show().prop('disabled', false).text('{{ __("Start Verification") }}');
            break;
        case 'RELOAD_REQUEST':
            console.log('Verification reload requested');
            window.location.reload();
            break;
        default:
            console.log('Unknown Veriff event:', msg);
    }
}

// Update user session ID
function updateUserSessionId(sessionId) {
    $.ajax({
        url: "{{ route('user.kyc.veriff.status') }}",
        method: 'POST',
        data: {
            action: 'update_session_id',
            session_id: sessionId
        },
        success: function(response) {
            console.log('Session ID updated:', sessionId);
        },
        error: function(xhr, status, error) {
            console.error('Error updating session ID:', error);
        }
    });
}

// Update KYC status
function updateKycStatus(status) {
    console.log('Updating KYC status:', status);
    
    $.ajax({
        url: "{{ route('user.kyc.veriff.status') }}",
        method: 'POST',
        data: {
            status: status,
            end_user_id: '{{ $endUserId }}'
        },
        success: function(response) {
            console.log('KYC status update response:', response);
            if (response.status === 200) {
                console.log(response.message || 'KYC status updated successfully');
                if (response.redirect) {
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error updating KYC status:', error);
        }
    });
}

// Show verification options (iframe or redirect)
function showVerificationOptions(url) {
    console.log('Showing verification options for URL:', url);
    
    // Hide the SDK container and start button
    $('#veriff-root').hide();
    $('#start-veriff-btn').hide();
    
    // Show options to user
    $('#verification-status').html(`
        <div class="text-center mb-4">
            <p class="text-blue-600 font-semibold mb-4">{{ __("🔍 Verification Ready") }}</p>
            <p class="text-slate-600 dark:text-slate-400 mb-6">{{ __("Choose how you'd like to complete your verification:") }}</p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="openInNewTab('${url}')" class="btn btn-primary">
                    <iconify-icon icon="mdi:open-in-new" class="mr-2"></iconify-icon>
                    {{ __("Open in New Tab") }}
                </button>
                <button onclick="showDirectIframe('${url}')" class="btn btn-outline btn-secondary">
                    <iconify-icon icon="mdi:web" class="mr-2"></iconify-icon>
                    {{ __("Show Here") }}
                </button>
            </div>
            
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-4">{{ __("Recommended: Open in new tab for better experience") }}</p>
        </div>
    `);
}

// Open verification in new tab
function openInNewTab(url) {
    console.log('Opening Veriff verification in new tab:', url);
    
    // Open in new tab
    const newWindow = window.open(url, '_blank', 'width=1000,height=800,scrollbars=yes,resizable=yes,location=yes,menubar=no,toolbar=no');
    
    if (newWindow) {
        // Update status
        $('#verification-status').html(`
            <div class="text-center">
                <p class="text-green-600 font-semibold">{{ __("✅ Verification Opened") }}</p>
                <p class="text-slate-600 dark:text-slate-400 mt-2">{{ __("Please complete your verification in the new tab that just opened.") }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">{{ __("Return to this page after completing verification.") }}</p>
                
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <p class="text-sm text-blue-700 dark:text-blue-300 mb-3">
                        <strong>{{ __("What to do:") }}</strong>
                    </p>
                    <ol class="text-sm text-blue-600 dark:text-blue-400 list-decimal list-inside space-y-1">
                        <li>{{ __("Complete the verification in the new tab") }}</li>
                        <li>{{ __("Follow all instructions from Veriff") }}</li>
                        <li>{{ __("Return to KYC dashboard to view updated status") }}</li>
                    </ol>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('user.kyc') }}" class="btn btn-primary">
                        {{ __("Return to KYC Dashboard") }}
                    </a>
                </div>
            </div>
        `);
        
        // Try to focus the new window
        setTimeout(() => {
            try {
                newWindow.focus();
            } catch (e) {
                console.log('Could not focus new window');
            }
        }, 100);
        
    } else {
        // Popup blocked - show manual link
        $('#verification-status').html(`
            <div class="text-center">
                <p class="text-red-600 font-semibold">{{ __("❌ Popup Blocked") }}</p>
                <p class="text-slate-600 dark:text-slate-400 mt-2">{{ __("Your browser blocked the popup. Please click the link below to open verification manually:") }}</p>
                
                <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <a href="${url}" target="_blank" class="btn btn-primary btn-lg">
                        <iconify-icon icon="mdi:open-in-new" class="mr-2"></iconify-icon>
                        {{ __("Open Veriff Verification") }}
                    </a>
                </div>
                
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-4">{{ __("After completing verification, return to your KYC dashboard to view the updated status.") }}</p>
                
                <div class="mt-4">
                    <a href="{{ route('user.kyc') }}" class="btn btn-primary">
                        {{ __("Return to KYC Dashboard") }}
                    </a>
                </div>
            </div>
        `);
    }
}



// Show verification in direct iframe
function showDirectIframe(url) {
    console.log('Showing direct iframe with URL:', url);
    
    // Prevent any default behavior
    event.preventDefault();
    
    // Hide the SDK container and start button
    $('#veriff-root').hide();
    $('#start-veriff-btn').hide();
    
    // Show loading state first
    $('#verification-status').html(`
        <div class="text-center">
            <p class="text-blue-600 font-semibold">{{ __("🔄 Loading Verification...") }}</p>
            <p class="text-slate-600 dark:text-slate-400 mt-2">{{ __("Please wait while we load the verification interface.") }}</p>
            <div class="mt-4">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>
        </div>
    `);
    
    // Show iframe container
    $('#direct-iframe-container').show();
    
    // Set iframe source with error handling
    const iframe = document.getElementById('direct-veriff-iframe');
    
    // Add error handler
    iframe.onerror = function() {
        console.error('Iframe failed to load');
        $('#verification-status').html(`
            <div class="text-center">
                <p class="text-red-600 font-semibold">{{ __("❌ Loading Failed") }}</p>
                <p class="text-slate-600 dark:text-slate-400 mt-2">{{ __("The verification interface couldn't load in the iframe.") }}</p>
                <div class="mt-4">
                    <button onclick="openInNewTab('${url}')" class="btn btn-primary">
                        {{ __("Open in New Tab Instead") }}
                    </button>
                </div>
            </div>
        `);
    };
    
    // Add load handler
    iframe.onload = function() {
        console.log('Veriff iframe loaded successfully');
        // Check if iframe actually has content
        setTimeout(() => {
            try {
                const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                if (iframeDoc.body && iframeDoc.body.innerHTML.trim() === '') {
                    // Iframe is empty
                    $('#verification-status').html(`
                        <div class="text-center">
                            <p class="text-yellow-600 font-semibold">{{ __("⚠️ Interface Not Loading") }}</p>
                            <p class="text-slate-600 dark:text-slate-400 mt-2">{{ __("The verification interface appears to be empty. This may be due to security restrictions.") }}</p>
                            <div class="mt-4">
                                <button onclick="openInNewTab('${url}')" class="btn btn-primary">
                                    {{ __("Open in New Tab Instead") }}
                                </button>
                            </div>
                        </div>
                    `);
                } else {
                    // Iframe loaded successfully
                    $('#verification-status').html(`
                        <div class="text-center">
                            <p class="text-green-600 font-semibold">{{ __("✅ Verification Loaded") }}</p>
                            <p class="text-slate-600 dark:text-slate-400 mt-2">{{ __("Please complete your verification in the interface below.") }}</p>
                        </div>
                    `);
                }
            } catch (e) {
                // Cross-origin restriction - this is normal
                $('#verification-status').html(`
                    <div class="text-center">
                        <p class="text-green-600 font-semibold">{{ __("✅ Verification Interface Ready") }}</p>
                        <p class="text-slate-600 dark:text-slate-400 mt-2">{{ __("Please complete your verification in the interface below.") }}</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">{{ __("If the interface appears blank, try the 'Open in New Tab' option.") }}</p>
                    </div>
                `);
            }
        }, 2000);
    };
    
    // Set iframe source
    iframe.src = url;
    
    console.log('Iframe source set to:', url);
    
    // Listen for iframe events
    window.addEventListener('message', function(event) {
        console.log('Received message from direct iframe:', event);
        
        // Handle messages from Veriff iframe  
        if (event.origin.includes('veriff.com') || event.origin.includes('alchemy.veriff.com')) {
            console.log('Veriff iframe event:', event.data);
            
            // Try to parse the event data
            let eventData = event.data;
            if (typeof eventData === 'string') {
                try {
                    eventData = JSON.parse(eventData);
                } catch (e) {
                    console.log('Non-JSON message from Veriff:', eventData);
                    return;
                }
            }
            
            // Handle based on event type
            if (eventData.type === 'VERIFICATION_FINISHED' || eventData.action === 'finished') {
                handleVeriffEvent('FINISHED');
            } else if (eventData.type === 'VERIFICATION_CANCELED' || eventData.action === 'canceled') {
                handleVeriffEvent('CANCELED');
            } else if (eventData.type === 'VERIFICATION_SUBMITTED' || eventData.action === 'submitted') {
                handleVeriffEvent('SUBMITTED');
            }
        }
    });
}

@else
$(document).ready(function() {
    $('#start-veriff-btn').prop('disabled', true).text('{{ __("Veriff not configured") }}');
});
@endif
</script>
@endsection

