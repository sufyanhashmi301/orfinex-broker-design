@extends('frontend::layouts.user')
@section('title')
    {{ $data['method'] ?? __('Payment Gateway') }}
@endsection

@if(isset($data['auto_redirect']) && $data['auto_redirect'])
@section('head')
    <meta http-equiv="refresh" content="0;url={{ $data['invoice_url'] }}">
    <script>
        // Immediate redirect with new tab fallback
        window.addEventListener('DOMContentLoaded', function() {
            // Try to open in new tab first
            const newTab = window.open('{{ $data["invoice_url"] }}', '_blank');
            if (newTab) {
                // New tab opened successfully
                console.log('Payment opened in new tab');
            } else {
                // Fallback to same tab redirect
                window.location.href = '{{ $data["invoice_url"] }}';
            }
        });
    </script>
@endsection
@endif

@section('content')

@if(isset($data['auto_redirect']) && $data['auto_redirect'])
<!-- Auto-redirect loading view -->
<div class="min-h-[calc(100vh-120px)] flex items-center justify-center">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-primary-100 rounded-full mb-6">
            <iconify-icon class="text-5xl text-primary-600 animate-spin" icon="heroicons-outline:arrow-path"></iconify-icon>
        </div>
        <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">{{ __('Redirecting to Payment') }}</h3>
        <p class="text-slate-600 dark:text-slate-300 mb-4">{{ __('Opening secure payment gateway...') }}</p>
        <div class="w-48 h-2 bg-slate-200 rounded-full mx-auto mb-6">
            <div class="h-2 bg-primary-600 rounded-full animate-pulse" style="width: 85%; transition: width 2s ease-in-out;"></div>
        </div>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            {{ __('If redirect doesn\'t work,') }} 
            <a href="{{ $data['invoice_url'] }}" target="_blank" class="text-primary-600 hover:text-primary-700 underline">
                {{ __('click here') }}
            </a>
        </p>
    </div>
</div>
@else
<!-- Simple iframe-only view -->
<div class="w-full h-screen relative">
    <!-- Loading overlay -->
    <div id="iframe-loading" class="absolute inset-0 bg-white dark:bg-slate-800 flex items-center justify-center z-10">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-full mb-4">
                <iconify-icon class="text-3xl text-primary-600 animate-spin" icon="heroicons-outline:arrow-path"></iconify-icon>
            </div>
            <p class="text-slate-600 dark:text-slate-300">{{ __('Loading payment gateway...') }}</p>
        </div>
    </div>
    
    <!-- Iframe blocked fallback -->
    <div id="iframe-blocked" class="absolute inset-0 bg-slate-50 dark:bg-slate-700 flex items-center justify-center z-20" style="display: none;">
        <div class="text-center p-6">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 rounded-full mb-4">
                <iconify-icon class="text-3xl text-orange-600" icon="heroicons-outline:exclamation-triangle"></iconify-icon>
            </div>
            <h5 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">{{ __('Loading Payment Gateway') }}</h5>
            <p class="text-slate-600 dark:text-slate-300 mb-4">{{ __('Please wait while we load the secure payment interface...') }}</p>
            <div class="space-y-2">
                <button class="btn inline-flex justify-center btn-primary" onclick="retryIframe()">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="heroicons-outline:arrow-path"></iconify-icon>
                    {{ __('Retry') }}
                </button>
                <br>
                <a href="{{ $data['invoice_url'] }}" target="_blank" class="btn inline-flex justify-center btn-outline-primary">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="heroicons-outline:arrow-top-right-on-square"></iconify-icon>
                    {{ __('Open in New Tab') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Primary iframe (modern browsers) -->
    <iframe 
        id="payment-iframe"
        src="{{ $data['invoice_url'] }}"
        class="w-full h-full border-0"
        style="min-height: 100vh;"
        sandbox="allow-same-origin allow-scripts allow-forms allow-popups allow-top-navigation"
        referrerpolicy="strict-origin-when-cross-origin"
        onload="handleIframeLoad()"
        onerror="handleIframeError()">
    </iframe>

    <!-- Object fallback for Mac Safari -->
    <object 
        id="payment-object"
        data="{{ $data['invoice_url'] }}"
        type="text/html"
        class="w-full h-full"
        style="min-height: 100vh; display: none;">
        <param name="src" value="{{ $data['invoice_url'] }}">
        <!-- Embed fallback -->
        <embed 
            src="{{ $data['invoice_url'] }}"
            type="text/html"
            class="w-full h-full"
            style="min-height: 100vh;">
    </object>
</div>

<script>
let iframeLoadTimeout;
let iframeLoaded = false;

// Handle iframe load success
function handleIframeLoad() {
    console.log('Iframe loaded successfully');
    iframeLoaded = true;
    clearTimeout(iframeLoadTimeout);
    
    // Hide loading overlay
    setTimeout(function() {
        document.getElementById('iframe-loading').style.display = 'none';
    }, 500);
}

// Handle iframe load error
function handleIframeError() {
    console.log('Iframe failed to load, trying fallback methods');
    showIframeBlocked();
}

// Show iframe blocked message and alternatives
function showIframeBlocked() {
    document.getElementById('iframe-loading').style.display = 'none';
    document.getElementById('iframe-blocked').style.display = 'flex';
    
    // Try object tag for Mac Safari
    tryObjectFallback();
}

// Try object tag fallback (works better on Mac Safari)
function tryObjectFallback() {
    console.log('Trying object tag fallback for Mac compatibility');
    
    setTimeout(function() {
        const iframe = document.getElementById('payment-iframe');
        const objectTag = document.getElementById('payment-object');
        
        if (!iframeLoaded && iframe && objectTag) {
            iframe.style.display = 'none';
            objectTag.style.display = 'block';
            
            // Hide the blocked message if object loads
            setTimeout(function() {
                document.getElementById('iframe-blocked').style.display = 'none';
            }, 2000);
        }
    }, 1000);
}

// Retry iframe loading
function retryIframe() {
    console.log('Retrying iframe...');
    
    const iframe = document.getElementById('payment-iframe');
    const originalSrc = iframe.src;
    
    // Reset states
    iframeLoaded = false;
    document.getElementById('iframe-blocked').style.display = 'none';
    document.getElementById('iframe-loading').style.display = 'flex';
    
    // Reload iframe
    iframe.src = '';
    setTimeout(function() {
        iframe.src = originalSrc;
    }, 100);
    
    // Set timeout again
    setupIframeTimeout();
}

// Setup iframe load timeout
function setupIframeTimeout() {
    iframeLoadTimeout = setTimeout(function() {
        if (!iframeLoaded) {
            console.log('Iframe load timeout - showing alternatives');
            showIframeBlocked();
        }
    }, 8000); // 8 second timeout
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing iframe payment...');
    setupIframeTimeout();
    
    // Check if we're on Mac and adjust accordingly
    const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;
    if (isMac) {
        console.log('Mac detected - enabling enhanced compatibility mode');
        
        // Shorter timeout for Mac
        clearTimeout(iframeLoadTimeout);
        iframeLoadTimeout = setTimeout(function() {
            if (!iframeLoaded) {
                console.log('Mac iframe timeout - trying object tag');
                tryObjectFallback();
            }
        }, 5000); // 5 seconds for Mac
    }
});

// Handle messages from payment frame
window.addEventListener('message', function(event) {
    if (event.origin.includes('uniwire.com')) {
        console.log('Received message from payment gateway:', event.data);
        
        if (event.data && event.data.type === 'payment_completed') {
            console.log('Payment completed');
            // Could redirect to success page or stay in context
        } else if (event.data && event.data.type === 'payment_cancelled') {
            console.log('Payment cancelled');
            // Could redirect to cancel page
        }
    }
});
</script>

<style>
iframe, object, embed {
    border: none;
    background: white;
}

/* Mac Safari specific styles */
@media screen and (-webkit-min-device-pixel-ratio: 1) {
    .payment-frame {
        -webkit-transform: translateZ(0);
        transform: translateZ(0);
    }
}

/* Remove any default margins/padding */
body {
    margin: 0;
    padding: 0;
}

/* Ensure full height */
html, body {
    height: 100%;
}

/* Dark mode iframe styling */
.dark iframe, .dark object, .dark embed {
    background: #1e293b;
}
</style>
@endif
@endsection 