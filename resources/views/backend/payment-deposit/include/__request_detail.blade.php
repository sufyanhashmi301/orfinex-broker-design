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
                <span
                    class="text-sm text-slate-900 dark:text-white ml-2">{{ $depositRequest->user->phone ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Country') }}:</span>
                <span
                    class="text-sm text-slate-900 dark:text-white ml-2">{{ $depositRequest->user->country ?? 'N/A' }}</span>
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
                    @if ($depositRequest->status === 'pending')
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
                <span
                    class="text-sm text-slate-900 dark:text-white ml-2">{{ $depositRequest->submitted_at ? $depositRequest->submitted_at->format('Y-m-d H:i:s') : 'N/A' }}</span>
            </div>
            @if ($depositRequest->approved_at)
                <div>
                    <span
                        class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Processed At') }}:</span>
                    <span
                        class="text-sm text-slate-900 dark:text-white ml-2">{{ $depositRequest->approved_at->format('Y-m-d H:i:s') }}</span>
                </div>
            @endif
            @if ($depositRequest->approvedBy)
                <div>
                    <span
                        class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Processed By') }}:</span>
                    <span
                        class="text-sm text-slate-900 dark:text-white ml-2">{{ $depositRequest->approvedBy->name }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Form Responses -->
    <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-lg">
        <h5 class="font-medium text-slate-900 dark:text-white mb-3">{{ __('Form Responses') }}</h5>
        <div class="space-y-3">
            @if ($depositRequest->sanitized_fields)
                @foreach ($depositRequest->sanitized_fields as $key => $value)
                    <div class="border-b border-slate-200 dark:border-slate-600 pb-2 last:border-b-0 last:pb-0">
                        <span
                            class="text-sm font-medium text-slate-500 dark:text-slate-400 capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                        <div class="text-sm text-slate-900 dark:text-white mt-1">
                            @if (is_array($value))
                                {{ implode(', ', $value) }}
                            @else
                                {{-- Check if this is a file field --}}
                                @php
                                    $isFile = false;
                                    $fileExtension = '';
                                    // Check if this is a file path (contains Custom-Payments directory or has file extension)
                                    if (
                                        is_string($value) &&
                                        (str_contains($value, 'Custom-Payments/') ||
                                            str_contains($value, 'payment-deposit/') ||
                                            str_contains($value, 'global/images/') ||
                                            preg_match(
                                                '/\.(jpeg|jpg|png|gif|svg|pdf|doc|docx|txt)$/i',
                                                $value,
                                                $matches,
                                            ))
                                    ) {
                                        $isFile = true;
                                        // Extract file extension
                                        if (preg_match('/\.([a-z0-9]+)$/i', $value, $matches)) {
                                            $fileExtension = strtolower($matches[1]);
                                        }
                                    }
                                @endphp

                                @if ($isFile)
                                    <div class="space-y-3">
                                        @if (in_array($fileExtension, ['jpeg', 'jpg', 'png', 'gif', 'svg']))
                                            {{-- Image display with overlay download icon --}}
                                            <div class="relative inline-block">
                                                <img src="{{ asset($value) }}" alt="{{ basename($value) }}"
                                                    class="max-w-xs max-h-48 rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer hover:opacity-75 transition-opacity"
                                                    onclick="viewImage('{{ asset($value) }}', '{{ basename($value) }}')">

                                                {{-- Download icon overlay --}}
                                                <a href="{{ route('admin.payment-deposit.download.file', [$depositRequest->id, $key]) }}"
                                                    class="absolute top-2 right-2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all"
                                                    title="{{ __('Download') }}">
                                                    <iconify-icon icon="lucide:download" class="text-sm"></iconify-icon>
                                                </a>
                                            </div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                                <span class="font-medium">{{ basename($value) }}</span>
                                                <span class="uppercase ml-1">({{ $fileExtension }})</span>
                                            </div>
                                        @else
                                            {{-- Non-image file display --}}
                                            <div
                                                class="flex items-center space-x-3 p-3 bg-slate-100 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                                                {{-- File icon --}}
                                                <div class="flex items-center space-x-2">
                                                    @if ($fileExtension === 'pdf')
                                                        <iconify-icon icon="lucide:file-text"
                                                            class="text-red-500 text-2xl"></iconify-icon>
                                                    @elseif(in_array($fileExtension, ['doc', 'docx']))
                                                        <iconify-icon icon="lucide:file-text"
                                                            class="text-blue-600 text-2xl"></iconify-icon>
                                                    @else
                                                        <iconify-icon icon="lucide:file"
                                                            class="text-gray-500 text-2xl"></iconify-icon>
                                                    @endif
                                                </div>

                                                {{-- File info --}}
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-slate-900 dark:text-white">
                                                        {{ basename($value) }}</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">
                                                        {{ $fileExtension }} {{ __('File') }}</p>
                                                </div>

                                                {{-- Download icon --}}
                                                <a href="{{ route('admin.payment-deposit.download.file', [$depositRequest->id, $key]) }}"
                                                    class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200 p-2"
                                                    title="{{ __('Download') }}">
                                                    <iconify-icon icon="lucide:download" class="text-xl"></iconify-icon>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    {{ $value }}
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('No form data available') }}</p>
            @endif
        </div>
    </div>

    {{-- Image Preview Modal --}}
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()"
                class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75">
                <iconify-icon icon="lucide:x" class="text-xl"></iconify-icon>
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
            <div class="absolute bottom-4 left-4 right-4 text-center">
                <p id="modalImageName" class="text-white bg-black bg-opacity-50 rounded px-3 py-1 text-sm"></p>
            </div>
        </div>
    </div>

    <!-- Bank Details (if approved) -->
    @if ($depositRequest->status === 'approved' && $depositRequest->bank_details)
        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800">
            <h5 class="font-medium text-green-900 dark:text-green-100 mb-3">{{ __('Bank Details Provided') }}</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <span class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Bank Name') }}:</span>
                    <span
                        class="text-sm text-green-900 dark:text-green-100 ml-2">{{ $depositRequest->bank_details['bank_name'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span
                        class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Account Name') }}:</span>
                    <span
                        class="text-sm text-green-900 dark:text-green-100 ml-2">{{ $depositRequest->bank_details['account_name'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span
                        class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Account Number') }}:</span>
                    <span
                        class="text-sm text-green-900 dark:text-green-100 ml-2">{{ $depositRequest->bank_details['account_number'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span
                        class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Routing Number') }}:</span>
                    <span
                        class="text-sm text-green-900 dark:text-green-100 ml-2">{{ $depositRequest->bank_details['routing_number'] ?? 'N/A' }}</span>
                </div>
                @if (!empty($depositRequest->bank_details['swift_code']))
                    <div>
                        <span
                            class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('SWIFT Code') }}:</span>
                        <span
                            class="text-sm text-green-900 dark:text-green-100 ml-2">{{ $depositRequest->bank_details['swift_code'] }}</span>
                    </div>
                @endif
                @if (!empty($depositRequest->bank_details['bank_address']))
                    <div class="md:col-span-2">
                        <span
                            class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Bank Address') }}:</span>
                        <div class="text-sm text-green-900 dark:text-green-100 mt-1">
                            {{ $depositRequest->bank_details['bank_address'] }}</div>
                    </div>
                @endif
                @if (!empty($depositRequest->bank_details['additional_instructions']))
                    <div class="md:col-span-2">
                        <span
                            class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Additional Instructions') }}:</span>
                        <div class="text-sm text-green-900 dark:text-green-100 mt-1">
                            {{ $depositRequest->bank_details['additional_instructions'] }}</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Rejection Reason (if rejected) -->
    @if ($depositRequest->status === 'rejected' && $depositRequest->rejection_reason)
        <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-200 dark:border-red-800">
            <h5 class="font-medium text-red-900 dark:text-red-100 mb-3">{{ __('Rejection Reason') }}</h5>
            <p class="text-sm text-red-900 dark:text-red-100">{{ $depositRequest->rejection_reason }}</p>
        </div>
    @endif
</div>

<script>
    function viewImage(imageSrc, imageName) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const modalImageName = document.getElementById('modalImageName');

        modalImage.src = imageSrc;
        modalImageName.textContent = imageName;
        modal.classList.remove('hidden');

        // Prevent body scroll when modal is open
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');

        // Restore body scroll
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside the image
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
</script>
