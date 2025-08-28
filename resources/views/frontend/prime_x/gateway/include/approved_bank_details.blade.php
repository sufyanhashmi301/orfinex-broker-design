<div class="col-span-12">
    <div class="frontend-editor-data space-y-4 text-lg mb-3">
        <h6 class="text-slate-900 dark:text-white flex items-center">
            <iconify-icon icon="lucide:check-circle" class="text-green-500 mr-2"></iconify-icon>
            {{ __('Approved Bank Details:') }}
        </h6>
        <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg border border-green-200 dark:border-green-800">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                @if(!empty($bankDetails['bank_name']))
                <div>
                    <span class="font-medium text-green-700 dark:text-green-300">{{ __('Bank Name') }}:</span>
                    <div class="text-green-900 dark:text-green-100 font-medium">{{ $bankDetails['bank_name'] }}</div>
                </div>
                @endif
                
                @if(!empty($bankDetails['account_name']))
                <div>
                    <span class="font-medium text-green-700 dark:text-green-300">{{ __('Account Name') }}:</span>
                    <div class="text-green-900 dark:text-green-100 font-medium">{{ $bankDetails['account_name'] }}</div>
                </div>
                @endif
                
                @if(!empty($bankDetails['account_number']))
                <div>
                    <span class="font-medium text-green-700 dark:text-green-300">{{ __('Account Number') }}:</span>
                    <div class="text-green-900 dark:text-green-100 font-medium font-mono">{{ $bankDetails['account_number'] }}</div>
                </div>
                @endif
                
                @if(!empty($bankDetails['routing_number']))
                <div>
                    <span class="font-medium text-green-700 dark:text-green-300">{{ __('Routing Number') }}:</span>
                    <div class="text-green-900 dark:text-green-100 font-medium font-mono">{{ $bankDetails['routing_number'] }}</div>
                </div>
                @endif
                
                @if(!empty($bankDetails['swift_code']))
                <div>
                    <span class="font-medium text-green-700 dark:text-green-300">{{ __('SWIFT Code') }}:</span>
                    <div class="text-green-900 dark:text-green-100 font-medium font-mono">{{ $bankDetails['swift_code'] }}</div>
                </div>
                @endif
                
                @if(!empty($bankDetails['bank_address']))
                <div class="md:col-span-2">
                    <span class="font-medium text-green-700 dark:text-green-300">{{ __('Bank Address') }}:</span>
                    <div class="text-green-900 dark:text-green-100">{{ $bankDetails['bank_address'] }}</div>
                </div>
                @endif
                
                @if(!empty($bankDetails['additional_instructions']))
                <div class="md:col-span-2">
                    <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-4 border border-amber-200 dark:border-amber-700">
                        <span class="font-medium text-amber-700 dark:text-amber-300 flex items-center">
                            <iconify-icon icon="lucide:info" class="mr-1"></iconify-icon>
                            {{ __('Important Instructions') }}:
                        </span>
                        <div class="text-amber-800 dark:text-amber-200 mt-2">{{ $bankDetails['additional_instructions'] }}</div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Copy Button -->
            <div class="text-center mt-4">
                <button onclick="copyApprovedBankDetails()" id="copyApprovedBtn" class="btn bg-white dark:bg-slate-800 border-2 border-green-500 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 inline-flex items-center justify-center transition-all duration-300">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:copy" id="copyApprovedIcon"></iconify-icon>
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2 hidden" icon="lucide:check" id="checkApprovedIcon"></iconify-icon>
                    <span id="copyApprovedText">{{ __('Copy Bank Details') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

@foreach(json_decode($fieldOptions, true) as $key => $field)
    @if($field['type'] == 'file')
        <div class="col-span-12">
            <label class="form-label">{{ __('' . $field['name']) }}</label>
            <div class="wrap-custom-file dark:border-slate-700">
                <input
                    type="file"
                    name="manual_data[{{ $field['name'] }}]"
                    id="{{ $key }}"
                    accept=".gif, .jpg, .png"
                    @if($field['validation'] == 'required') required @endif
                />
                <label for="{{ $key }}">
                    <img
                        class="upload-icon"
                        src="{{ asset('global/materials/upload.svg') }}"
                        alt="{{ __('Upload Icon') }}"
                    />
                    <span class="dark:text-slate-200">{{ __('Select ') . $field['name'] }}</span>
                </label>
            </div>
        </div>
    @elseif($field['type'] == 'textarea')
        <div class="input-area">
            <textarea class="form-control" rows="5" @if($field['validation'] == 'required') required @endif placeholder="{{ __('Send Money Note') }}" name="manual_data[{{ $field['name'] }}]"></textarea>
        </div>
    @else
        <div class="input-area">
            <label for="{{ str_replace(' ', '_', $field['name']) }}" class="form-label">
                {{ $field['name'] }}
            </label>
            <input type="text" name="manual_data[{{ $field['name'] }}]"
                @if($field['validation'] == 'required') required @endif class="form-control !text-lg"
                aria-label="{{ str_replace(' ', '_', $field['name']) }}"
                id="{{ str_replace(' ', '_', $field['name']) }}"
                aria-describedby="basic-addon1">
        </div>
    @endif
@endforeach

<script>
// Copy bank details functionality
function copyApprovedBankDetails() {
    const bankDetails = @json($bankDetails);
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
    const copyBtn = document.getElementById('copyApprovedBtn');
    const copyIcon = document.getElementById('copyApprovedIcon');
    const checkIcon = document.getElementById('checkApprovedIcon');
    const copyText = document.getElementById('copyApprovedText');
    
    // Show loading state
    copyBtn.disabled = true;
    copyBtn.classList.add('opacity-75');
    
    // Try modern clipboard API first, then fallback to legacy method
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(textToCopy).then(function() {
            handleCopySuccessApproved();
        }).catch(function(err) {
            console.log('Clipboard API failed, trying fallback:', err);
            fallbackCopyTextToClipboardApproved(textToCopy);
        });
    } else {
        // Fallback for older browsers or non-secure context
        fallbackCopyTextToClipboardApproved(textToCopy);
    }
}

function handleCopySuccessApproved() {
    const copyBtn = document.getElementById('copyApprovedBtn');
    const copyIcon = document.getElementById('copyApprovedIcon');
    const checkIcon = document.getElementById('checkApprovedIcon');
    const copyText = document.getElementById('copyApprovedText');
    
    // Success state
    copyIcon.classList.add('hidden');
    checkIcon.classList.remove('hidden');
    copyText.textContent = '{{ __("Copied!") }}';
    copyBtn.classList.remove('border-green-500', 'text-green-600');
    copyBtn.classList.add('border-emerald-500', 'text-emerald-600', 'bg-emerald-50', 'dark:bg-emerald-900/20');
    
    // Reset after 3 seconds
    setTimeout(function() {
        copyIcon.classList.remove('hidden');
        checkIcon.classList.add('hidden');
        copyText.textContent = '{{ __("Copy Bank Details") }}';
        copyBtn.classList.remove('border-emerald-500', 'text-emerald-600', 'bg-emerald-50', 'dark:bg-emerald-900/20');
        copyBtn.classList.add('border-green-500', 'text-green-600');
        copyBtn.disabled = false;
        copyBtn.classList.remove('opacity-75');
    }, 3000);
}

function handleCopyErrorApproved(err) {
    console.log('Copy failed:', err);
    const copyBtn = document.getElementById('copyApprovedBtn');
    
    // Error state - reset button
    copyBtn.disabled = false;
    copyBtn.classList.remove('opacity-75');
}

function fallbackCopyTextToClipboardApproved(text) {
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
            handleCopySuccessApproved();
        } else {
            handleCopyErrorApproved('execCommand failed');
        }
    } catch (err) {
        handleCopyErrorApproved(err);
    }
    
    document.body.removeChild(textArea);
}
</script>
