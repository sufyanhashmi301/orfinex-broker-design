<div class="flex items-center justify-between p-5">
    <h3 class="text-xl font-medium dark:text-white capitalize">
        {{ __('Withdraw Account Action') }}
    </h3>
    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal" aria-label="Close">
        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                        11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
        <span class="sr-only">Close modal</span>
    </button>
</div>
<div class="max-h-[calc(100vh-200px)] overflow-y-auto p-6">
    <ul class="divide-y divide-slate-100 dark:divide-slate-700 border border-slate-100 dark:border-slate-700 rounded mb-5">
        @php
            // Debug user data
            $userData = $data->user;
            $userName = 'N/A';
            if ($userData) {
                $userName = $userData->full_name ?? 
                           ($userData->first_name && $userData->last_name ? $userData->first_name . ' ' . $userData->last_name : 'N/A');
            }
        @endphp
        <li class="list-group-item dark:text-slate-300 block py-2 px-3">
            <span class="mr-2">{{ __('User:') }}</span>
            <span class="font-medium">{{ $userName }}</span>
           
        </li>
        <li class="list-group-item dark:text-slate-300 block py-2 px-3">
            <span class="mr-2">{{ __('Email:') }}</span>
            <span class="font-medium">{{ $data->user->email ?? 'N/A' }}</span>
        </li>
        
        <li class="list-group-item dark:text-slate-300 block py-2 px-3">
            <span class="mr-2">{{ __('Created At:') }}</span>
            <span class="font-medium">{{ $data->created_at->format('Y-m-d H:i:s') }}</span>
        </li>
        <li class="list-group-item dark:text-slate-300 block py-2 px-3">
            <span class="mr-2">{{ __('Method Name:') }}</span>
            <span class="font-medium">{{ $data->method_name }}</span>
        </li>
        <li class="list-group-item dark:text-slate-300 block py-2 px-3">
            <span class="mr-2">{{ __('Currency:') }}</span>
            <span class="font-medium">{{ $data->method->currency ?? 'N/A' }}</span>
        </li>
        <li class="list-group-item dark:text-slate-300 block py-2 px-3">
            <span class="mr-2">{{ __('Current Status:') }}</span>
            <span class="font-medium">
                @if($data->status === 'pending')
                    <span class="badge badge-warning">{{ __('Pending') }}</span>
                @elseif($data->status === 'approved')
                    <span class="badge bg-success-500 text-white">{{ __('Approved') }}</span>
                @elseif($data->status === 'rejected')
                    <span class="badge badge-danger">{{ __('Rejected') }}</span>
                @endif
            </span>
        </li>
    </ul>

    <ul class="divide-y divide-slate-100 dark:divide-slate-700 border border-slate-100 dark:border-slate-700 rounded mb-5">
        @php
            $credentials = is_string($data->credentials) ? json_decode($data->credentials, true) : $data->credentials;
            $credentials = is_array($credentials) ? $credentials : [];
        @endphp
        @foreach($credentials as $name => $field_data)
            <li class="list-group-item dark:text-slate-300 block py-2 px-3">
                <span class="mr-2">{{ $name }}:</span>
                @if( isset($field_data['type']) && $field_data['type'] == 'file' )
                    @if(!empty($field_data['value']) && is_string($field_data['value']))
                        <div class="h-[260px] bg-no-repeat bg-contain bg-center bg-slate-100 my-2"
                             style="background-image: url('{{ asset($field_data['value']) }}')"></div>
                        <div class="flex justify-end gap-3">
                            <a href="{{ asset($field_data['value']) }}" class="btn-link" download>{{ __('Download') }}</a>
                            <a href="{{ asset($field_data['value']) }}" class="btn-link" target="_blank">{{ __('View') }}</a>
                        </div>
                    @endif
                @else
                    <span class="font-medium">{{ $field_data['value'] ?? 'N/A' }}</span>
                @endif
            </li>
        @endforeach
    </ul>

    {{-- Description Field --}}
    <div class="mb-5">
        <label for="account_description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
            {{ __('Description:') }} <span class="text-slate-400">({{ __('Optional') }})</span>
        </label>
        <textarea 
            id="account_description" 
            name="description" 
            rows="3" 
            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-slate-700 dark:text-white"
            placeholder="{{ __('Enter any comments or notes about this action...') }}"
        >{{ $data->description ?? '' }}</textarea>
    </div>

    <div class="action-btns text-right">
        @if($data->status === 'pending')
            {{-- Pending Status: Show both approve and reject buttons enabled --}}
            @can('withdraw-approve')
            <button type="button" id="approveAccount" class="btn btn-dark inline-flex items-center justify-center mr-2" data-account-id="{{ the_hash($data->id) }}">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                {{ __('Approve') }}
            </button>
            @endcan
            @can('withdraw-reject')
            <button type="button" id="rejectAccount" class="btn btn-danger inline-flex items-center justify-center" data-account-id="{{ the_hash($data->id) }}">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                {{ __('Reject') }}
            </button>
            @endcan
        @elseif($data->status === 'approved')
            {{-- Approved Status: Show disabled approve and enabled reject --}}
            @can('withdraw-approve')
            <button type="button" class="btn btn-dark inline-flex items-center justify-center mr-2" disabled>
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                {{ __('Approved') }}
            </button>
            @endcan
            @can('withdraw-reject')
            <button type="button" id="rejectAccount" class="btn btn-danger inline-flex items-center justify-center" data-account-id="{{ the_hash($data->id) }}">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                {{ __('Reject') }}
            </button>
            @endcan
        @elseif($data->status === 'rejected')
            {{-- Rejected Status: Show enabled approve and disabled reject --}}
            @can('withdraw-approve')
            <button type="button" id="approveAccount" class="btn btn-dark inline-flex items-center justify-center mr-2" data-account-id="{{ the_hash($data->id) }}">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                {{ __('Approve') }}
            </button>
            @endcan
            @can('withdraw-reject')
            <button type="button" class="btn btn-danger inline-flex items-center justify-center" disabled>
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                {{ __('Rejected') }}
            </button>
            @endcan
        @endif
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle approve button (only if button exists and is enabled)
    $(document).on('click', '#approveAccount:not(:disabled)', function() {
        const accountId = $(this).data('account-id');
        const description = $('#account_description').val();
        
        // Show loading state
        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html(`
            <iconify-icon class="spining-icon text-xl ltr:mr-2 rtl:ml-2" icon="svg-spinners:180-ring"></iconify-icon>
            {{ __("Processing...") }}
        `);
        
        $.ajax({
            url: '{{ route("admin.withdraw.account.action") }}',
            method: 'POST',
            data: {
                account_id: accountId,
                action: 'approve',
                description: description,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Hide modal
                    $('#account-action-modal').modal('hide');
                    
                    // Show success notification
                    if (typeof tNotify === 'function') {
                        tNotify('success', response.message);
                    }
                    
                    // Reload table
                    if (typeof table !== 'undefined') {
                        table.draw();
                    }
                } else {
                    if (typeof tNotify === 'function') {
                        tNotify('error', response.message);
                    }
                }
            },
            error: function(xhr) {
                let errorMessage = '{{ __("An error occurred while processing the request.") }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                if (typeof tNotify === 'function') {
                    tNotify('error', errorMessage);
                }
            },
            complete: function() {
                // Reset button state
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Handle reject button (only if button exists and is enabled)
    $(document).on('click', '#rejectAccount:not(:disabled)', function() {
        const accountId = $(this).data('account-id');
        const description = $('#account_description').val();
        
        // Show loading state
        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html(`
            <iconify-icon class="spining-icon text-xl ltr:mr-2 rtl:ml-2" icon="svg-spinners:180-ring"></iconify-icon>
            {{ __("Processing...") }}
        `);
        
        $.ajax({
            url: '{{ route("admin.withdraw.account.action") }}',
            method: 'POST',
            data: {
                account_id: accountId,
                action: 'reject',
                description: description,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Hide modal
                    $('#account-action-modal').modal('hide');
                    
                    // Show success notification
                    if (typeof tNotify === 'function') {
                        tNotify('success', response.message);
                    }
                    
                    // Reload table
                    if (typeof table !== 'undefined') {
                        table.draw();
                    }
                } else {
                    if (typeof tNotify === 'function') {
                        tNotify('error', response.message);
                    }
                }
            },
            error: function(xhr) {
                let errorMessage = '{{ __("An error occurred while processing the request.") }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                if (typeof tNotify === 'function') {
                    tNotify('error', errorMessage);
                }
            },
            complete: function() {
                // Reset button state
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script> 