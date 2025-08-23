<div class="space-y-4">
    <!-- User Information -->
    <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-lg">
        <h5 class="font-medium text-slate-900 dark:text-white mb-3">{{ __('User Information') }}</h5>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Name') }}:</span>
                <span class="text-sm text-slate-900 dark:text-white ml-2">{{ $depositRequest->user->full_name }}</span>
            </div>
            <div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Email') }}:</span>
                <span class="text-sm text-slate-900 dark:text-white ml-2">{{ $depositRequest->user->email }}</span>
            </div>
            <div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Phone') }}:</span>
                <span class="text-sm text-slate-900 dark:text-white ml-2">{{ $depositRequest->user->phone ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Country') }}:</span>
                <span class="text-sm text-slate-900 dark:text-white ml-2">{{ $depositRequest->user->country ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <!-- Request Information -->
    <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-lg">
        <h5 class="font-medium text-slate-900 dark:text-white mb-3">{{ __('Request Information') }}</h5>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Status') }}:</span>
                <span class="text-sm ml-2">
                    @if($depositRequest->status === 'pending')
                        <span class="badge bg-warning-500 text-white">{{ __('Pending') }}</span>
                    @elseif($depositRequest->status === 'approved')
                        <span class="badge bg-success-500 text-white">{{ __('Approved') }}</span>
                    @elseif($depositRequest->status === 'rejected')
                        <span class="badge bg-danger-500 text-white">{{ __('Rejected') }}</span>
                    @endif
                </span>
            </div>
            <div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Submitted At') }}:</span>
                <span class="text-sm text-slate-900 dark:text-white ml-2">{{ $depositRequest->submitted_at ? $depositRequest->submitted_at->format('Y-m-d H:i:s') : 'N/A' }}</span>
            </div>
            @if($depositRequest->approved_at)
            <div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Processed At') }}:</span>
                <span class="text-sm text-slate-900 dark:text-white ml-2">{{ $depositRequest->approved_at->format('Y-m-d H:i:s') }}</span>
            </div>
            @endif
            @if($depositRequest->approvedBy)
            <div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Processed By') }}:</span>
                <span class="text-sm text-slate-900 dark:text-white ml-2">{{ $depositRequest->approvedBy->name }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Form Responses -->
    <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-lg">
        <h5 class="font-medium text-slate-900 dark:text-white mb-3">{{ __('Form Responses') }}</h5>
        <div class="space-y-3">
            @if($depositRequest->sanitized_fields)
                @foreach($depositRequest->sanitized_fields as $key => $value)
                    <div class="border-b border-slate-200 dark:border-slate-600 pb-2 last:border-b-0 last:pb-0">
                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400 capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                        <div class="text-sm text-slate-900 dark:text-white mt-1">
                            @if(is_array($value))
                                {{ implode(', ', $value) }}
                            @else
                                {{ $value }}
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('No form data available') }}</p>
            @endif
        </div>
    </div>

    <!-- Bank Details (if approved) -->
    @if($depositRequest->status === 'approved' && $depositRequest->bank_details)
    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800">
        <h5 class="font-medium text-green-900 dark:text-green-100 mb-3">{{ __('Bank Details Provided') }}</h5>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Bank Name') }}:</span>
                <span class="text-sm text-green-900 dark:text-green-100 ml-2">{{ $depositRequest->bank_details['bank_name'] ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Account Name') }}:</span>
                <span class="text-sm text-green-900 dark:text-green-100 ml-2">{{ $depositRequest->bank_details['account_name'] ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Account Number') }}:</span>
                <span class="text-sm text-green-900 dark:text-green-100 ml-2">{{ $depositRequest->bank_details['account_number'] ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Routing Number') }}:</span>
                <span class="text-sm text-green-900 dark:text-green-100 ml-2">{{ $depositRequest->bank_details['routing_number'] ?? 'N/A' }}</span>
            </div>
            @if(!empty($depositRequest->bank_details['swift_code']))
            <div>
                <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('SWIFT Code') }}:</span>
                <span class="text-sm text-green-900 dark:text-green-100 ml-2">{{ $depositRequest->bank_details['swift_code'] }}</span>
            </div>
            @endif
            @if(!empty($depositRequest->bank_details['bank_address']))
            <div class="md:col-span-2">
                <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Bank Address') }}:</span>
                <div class="text-sm text-green-900 dark:text-green-100 mt-1">{{ $depositRequest->bank_details['bank_address'] }}</div>
            </div>
            @endif
            @if(!empty($depositRequest->bank_details['additional_instructions']))
            <div class="md:col-span-2">
                <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Additional Instructions') }}:</span>
                <div class="text-sm text-green-900 dark:text-green-100 mt-1">{{ $depositRequest->bank_details['additional_instructions'] }}</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Rejection Reason (if rejected) -->
    @if($depositRequest->status === 'rejected' && $depositRequest->rejection_reason)
    <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-200 dark:border-red-800">
        <h5 class="font-medium text-red-900 dark:text-red-100 mb-3">{{ __('Rejection Reason') }}</h5>
        <p class="text-sm text-red-900 dark:text-red-100">{{ $depositRequest->rejection_reason }}</p>
    </div>
    @endif
</div>
