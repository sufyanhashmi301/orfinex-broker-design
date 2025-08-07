@extends('frontend::layouts.user')
@section('content')
<div class="payment-container h-screen bg-white">
    <!-- Loading state -->
    <div id="iframe-loader" class="d-flex justify-content-center align-items-center h-100">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">{{ __('Loading...') }}</span>
            </div>
            <p class="mt-3 text-muted">{{ __('Loading Uniwire payment gateway...') }}</p>
        </div>
    </div>
    
    <!-- Iframe container -->
    <div id="iframe-container" style="display: none;" class="h-100">
        <iframe 
            id="uniwire-iframe"
            src="{{ $data['invoice_url'] }}" 
            frameborder="0" 
            width="100%" 
            height="100%" 
            style="min-height: 800px; border: none;"
            allowfullscreen
            sandbox="allow-forms allow-scripts allow-same-origin allow-top-navigation allow-popups"
            referrerpolicy="no-referrer-when-downgrade"
            onload="handleIframeLoad()"
            onerror="handleIframeError()"
        ></iframe>
    </div>
    
    <!-- Fallback for iframe loading issues -->
    <div id="iframe-fallback" style="display: none;" class="text-center p-5">
        <div class="alert alert-warning">
            <h5><i class="fas fa-exclamation-triangle"></i> {{ __('Payment Gateway Loading Issue') }}</h5>
            <p>{{ __('The payment gateway is having trouble loading in this browser. Please try one of the options below:') }}</p>
            
            <div class="mt-4">
                <a href="{{ $data['invoice_url'] }}" target="_blank" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-external-link-alt"></i> {{ __('Open in New Tab') }}
                </a>
                <button onclick="retryIframe()" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-redo"></i> {{ __('Retry') }}
                </button>
            </div>
            
            <div class="mt-4">
                <small class="text-muted">
                    {{ __('If you continue to experience issues, please contact support or try using a different browser.') }}
                </small>
            </div>
        </div>
        
        <!-- Browser-specific advice -->
        <div class="mt-4" id="browser-advice" style="display: none;">
            <div class="alert alert-info">
                <h6>{{ __('Safari Users (Mac)') }}</h6>
                <p class="small">
                    {{ __('Safari may block some payment gateways. Try:') }}<br>
                    1. {{ __('Disable "Prevent cross-site tracking" in Safari preferences') }}<br>
                    2. {{ __('Use Chrome or Firefox as an alternative') }}<br>
                    3. {{ __('Click "Open in New Tab" above') }}
                </p>
            </div>
        </div>
    </div>
</div>

<script>
let iframeLoadTimeout;
let retryCount = 0;
const maxRetries = 2;

function handleIframeLoad() {
    clearTimeout(iframeLoadTimeout);
    document.getElementById('iframe-loader').style.display = 'none';
    document.getElementById('iframe-container').style.display = 'block';
    console.log('Uniwire iframe loaded successfully');
}

function handleIframeError() {
    console.error('Uniwire iframe failed to load');
    showFallback();
}

function showFallback() {
    document.getElementById('iframe-loader').style.display = 'none';
    document.getElementById('iframe-container').style.display = 'none';
    document.getElementById('iframe-fallback').style.display = 'block';
    
    // Show browser-specific advice for Safari/Mac users
    const userAgent = navigator.userAgent.toLowerCase();
    if (userAgent.includes('safari') && !userAgent.includes('chrome')) {
        document.getElementById('browser-advice').style.display = 'block';
    }
}

function retryIframe() {
    if (retryCount < maxRetries) {
        retryCount++;
        document.getElementById('iframe-fallback').style.display = 'none';
        document.getElementById('iframe-loader').style.display = 'flex';
        
        // Reload the iframe
        const iframe = document.getElementById('uniwire-iframe');
        iframe.src = iframe.src;
        
        // Set timeout for retry
        iframeLoadTimeout = setTimeout(showFallback, 10000);
    } else {
        alert('{{ __("Maximum retry attempts reached. Please try opening in a new tab.") }}');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial timeout for iframe loading
    iframeLoadTimeout = setTimeout(function() {
        if (document.getElementById('iframe-container').style.display === 'none') {
            console.warn('Uniwire iframe took too long to load, showing fallback');
            showFallback();
        }
    }, 15000); // 15 seconds timeout
    
    // Check if iframe loads within a reasonable time
    setTimeout(function() {
        const iframe = document.getElementById('uniwire-iframe');
        try {
            // Try to access iframe content (will throw error if blocked)
            if (iframe.contentDocument || iframe.contentWindow) {
                // If we can access it but it's still loading, that's okay
                console.log('Iframe is accessible');
            }
        } catch (e) {
            console.warn('Iframe access blocked, likely due to CORS or security policies');
            // Don't show fallback immediately, let the normal loading timeout handle it
        }
    }, 3000);
});

// Handle message events from iframe (if supported by Uniwire)
window.addEventListener('message', function(event) {
    // Verify origin for security
    if (event.origin.includes('uniwire.com')) {
        console.log('Received message from Uniwire:', event.data);
        
        // Handle payment completion messages
        if (event.data && event.data.type === 'payment_completed') {
            window.location.href = "{{ route('status.success') }}";
        } else if (event.data && event.data.type === 'payment_cancelled') {
            window.location.href = "{{ route('status.cancel') }}";
        }
    }
});
</script>

<style>
.payment-container {
    background: #f8f9fa;
    position: relative;
}

#iframe-loader .spinner-border {
    width: 3rem;
    height: 3rem;
}

.alert {
    border-radius: 10px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.btn {
    border-radius: 25px;
    padding: 10px 25px;
    font-weight: 600;
}

@media (max-width: 768px) {
    .btn-lg {
        display: block;
        width: 100%;
        margin-bottom: 10px;
    }
    
    .me-3 {
        margin-right: 0 !important;
    }
}
</style>
@endsection 