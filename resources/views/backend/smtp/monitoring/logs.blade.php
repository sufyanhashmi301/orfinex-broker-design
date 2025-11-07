@extends('backend.layouts.app')

@section('title')
    {{ __('SMTP Failure Logs') }}
@endsection

@section('content')
    <div class="main-content">
        <div class="pageTitle mb-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h4 class="font-medium text-xl capitalize dark:text-white inline-block ltr:pr-4 rtl:pl-4 mb-1">
                        @yield('title')
                    </h4>
                    <p class="text-sm text-slate-500 dark:text-slate-300">
                        {{ __('Monitor and troubleshoot email delivery failures.') }}
                    </p>
                </div>
                <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                    <a href="{{ route('admin.smtp.monitoring.settings') }}" class="btn btn-sm btn-outline-dark inline-flex items-center justify-center">
                        <iconify-icon icon="lucide:settings" class="text-base ltr:mr-2 rtl:ml-2"></iconify-icon>
                        <span>{{ __('Settings') }}</span>
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger inline-flex items-center justify-center" onclick="handleClearLogs()">
                        <iconify-icon icon="lucide:trash-2" class="text-base ltr:mr-2 rtl:ml-2"></iconify-icon>
                        <span>{{ __('Clear logs') }}</span>
                    </button>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="grid md:grid-cols-4 gap-4 mb-6">
                <div class="card p-4 cursor-pointer hover:shadow-md transition-shadow duration-200" data-filter="all" onclick="applyFilter('all')">
                    <div class="flex space-x-3 rtl:space-x-reverse">
                        <div class="flex-1">
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Failures') }}</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
                        </div>
                        <div class="inline-flex items-center justify-center ml-auto">
                            <iconify-icon class="text-lg" icon="heroicons-outline:chevron-right"></iconify-icon>
                        </div>
                    </div>
                </div>
                <div class="card p-4 cursor-pointer hover:shadow-md transition-shadow duration-200" data-filter="failed" onclick="applyFilter('failed')">
                    <div class="flex space-x-3 rtl:space-x-reverse">
                        <div class="flex-1">
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Failed') }}</div>
                            <div class="text-3xl font-bold text-red-600">{{ $stats['failed'] }}</div>
                        </div>
                        <div class="inline-flex items-center justify-center ml-auto">
                            <iconify-icon class="text-lg" icon="heroicons-outline:chevron-right"></iconify-icon>
                        </div>
                    </div>
                </div>
                <div class="card p-4 cursor-pointer hover:shadow-md transition-shadow duration-200" data-filter="resent" onclick="applyFilter('resent')">
                    <div class="flex space-x-3 rtl:space-x-reverse">
                        <div class="flex-1">
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Resent') }}</div>
                            <div class="text-3xl font-bold text-green-600">{{ $stats['resent'] }}</div>
                        </div>
                        <div class="inline-flex items-center justify-center ml-auto">
                            <iconify-icon class="text-lg" icon="heroicons-outline:chevron-right"></iconify-icon>
                        </div>
                    </div>
                </div>
                <div class="card p-4 cursor-pointer hover:shadow-md transition-shadow duration-200" data-filter="today" onclick="applyFilter('today')">
                    <div class="flex space-x-3 rtl:space-x-reverse">
                        <div class="flex-1">
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Today') }}</div>
                            <div class="text-3xl font-bold text-orange-600">{{ $stats['today'] }}</div>
                        </div>
                        <div class="inline-flex items-center justify-center ml-auto">
                            <iconify-icon class="text-lg" icon="heroicons-outline:chevron-right"></iconify-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Logs Table --}}
        <div class="card">
            <div class="card-body px-6 pt-3">
                <div class="overflow-x-auto -mx-6">
                    <div class="inline-block min-w-full align-middle" id="logsTableContainer">
                    @if($logs->count() > 0)
                        <div class="overflow-hidden basicTable_wrapper">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead>
                                    <tr>
                                        <th scope="col" class="table-th">{{ __('ID') }}</th>
                                        <th scope="col" class="table-th">{{ __('Time') }}</th>
                                        <th scope="col" class="table-th">{{ __('Recipient') }}</th>
                                        <th scope="col" class="table-th">{{ __('Template') }}</th>
                                        <th scope="col" class="table-th">{{ __('Status') }}</th>
                                        <th scope="col" class="table-th">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700" id="logsTableBody">
                                @foreach($logs as $log)
                                    <tr class="{{ $log->resent_at ? 'opacity-60' : '' }}">
                                        <td class="table-td">{{ $log->id }}</td>
                                        <td class="table-td">
                                            <span class="text-gray-600 dark:text-gray-400">
                                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                                            </span>
                                            <br>
                                            <span class="text-gray-500">
                                                {{ $log->created_at->diffForHumans() }}
                                            </span>
                                        </td>
                                        <td class="table-td">
                                            @if($log->user)
                                                <a href="{{ route('admin.user.edit',$log->user->id) }}" class="flex items-center" target="_blank">
                                                    <div class="flex-none">
                                                        <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                            <img src="{{ getFilteredPath($log->user->avatar, 'fallback/user.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 text-start">
                                                        <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                            {{ $log->user->first_name.' '.$log->user->last_name }}
                                                        </h4>
                                                        <div class="text-xs font-normal lowercase text-slate-600 dark:text-slate-400">
                                                            {{ safe($log->user->email) }}
                                                        </div>
                                                    </div>
                                                </a>
                                            @else
                                                <span class="text-slate-500 lowercase">
                                                    {{ $log->recipient ?? __('N/A') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="table-td">
                                            <span class="capitalize">
                                                {{ $log->template_name }}
                                            </span>
                                        </td>
                                        <td class="table-td">
                                            @if($log->resent_at)
                                                <span class="badge bg-success-500 text-white capitalize" data-tippy-content="Resent on {{ $log->resent_at->format('M d, Y H:i') }}">
                                                    <iconify-icon icon="heroicons:check-circle" class="mr-1"></iconify-icon>
                                                    {{ __('Resent') }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger-500 text-white capitalize">
                                                    <iconify-icon icon="heroicons:x-circle" class="mr-1"></iconify-icon>
                                                    {{ __('Failed') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="table-td">
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                @if(!$log->resent_at)
                                                    <button type="button" 
                                                        class="shift-Away action-btn" 
                                                        data-tippy-content="Resend Email"
                                                        onclick="resendEmail({{ $log->id }})">
                                                        <iconify-icon icon="heroicons:envelope"></iconify-icon>
                                                    </button>
                                                @else
                                                    <button type="button" 
                                                        class="shift-Away action-btn opacity-50 cursor-not-allowed" 
                                                        data-tippy-content="Already Resent"
                                                        disabled>
                                                        <iconify-icon icon="heroicons:check"></iconify-icon>
                                                    </button>
                                                @endif
                                                <button type="button" 
                                                    class="shift-Away action-btn"
                                                    data-tippy-content="View Details"
                                                    onclick="showDetails({{ $log->id }})"
                                                    data-error="{{ $log->error_message }}"
                                                    data-trace="{{ $log->stack_trace }}"
                                                    data-context="{{ json_encode($log->context) }}"
                                                    data-shortcodes="{{ json_encode($log->shortcodes) }}"
                                                    data-resent-at="{{ $log->resent_at }}">
                                                    <iconify-icon icon="heroicons:eye"></iconify-icon>
                                                </button>
                                                <button type="button" 
                                                    class="shift-Away action-btn"
                                                    data-tippy-content="Delete Log"
                                                    onclick="deleteLog({{ $log->id }})">
                                                    <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="border-t border-slate-100 dark:border-slate-700 gap-3 py-2 mt-auto">
                                {{ $logs->links() }}
                            </div>
                        </div>
                        @else
                            <div class="py-8 basicTable_wrapper items-center justify-center">
                                <iconify-icon icon="lucide:check-circle" class="text-5xl text-success mb-4"></iconify-icon>
                                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">{{ __('No Failures Detected') }}</h3>
                                <p class="text-gray-600 dark:text-gray-400">{{ __('Your SMTP service is running smoothly!') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for error details -->
    @include('backend.smtp.monitoring.__error_details')

    <!-- Modal for resend email confirmation -->
    @include('backend.smtp.monitoring.__resendEmail_confirm')

    <!-- Modal for delete log confirmation -->
    @include('backend.smtp.monitoring.__deleteLog_confirm')

    <!-- Modal for clear logs confirmation -->
    @include('backend.smtp.monitoring.__clearLogs_confirm')
@endsection

@section('script')
    <script>
        let currentFilter = '{{ request()->get('filter', 'all') }}';
        let currentPage = 1;

        function applyFilter(filter) {
            currentFilter = filter;
            currentPage = 1;
            
            // Update active state
            document.querySelectorAll('[data-filter]').forEach(card => {
                if (card.dataset.filter === filter) {
                    card.classList.add('ring-1', 'ring-primary', 'shadow-md');
                } else {
                    card.classList.remove('ring-1', 'ring-primary', 'shadow-md');
                }
            });

            loadLogs();
        }

        function loadLogs() {
            const container = document.getElementById('logsTableContainer');
            
            // Show loading state
            const loadingHtml = `
                <div class="py-8 basicTable_wrapper items-center justify-center">
                <iconify-icon class="spining-icon text-5xl dark:text-slate-100 mb-4" icon="lucide:loader"></iconify-icon>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">{{ __('Loading...') }}</h3>
                </div>
            `;
            container.innerHTML = loadingHtml;

            fetch(`{{ route('admin.smtp.monitoring.logs') }}?filter=${currentFilter}&page=${currentPage}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.html) {
                    container.innerHTML = data.html;
                    
                    // Reinitialize tooltips if you have any
                    if (typeof tippy !== 'undefined') {
                        tippy('[data-tippy-content]');
                    }
                } else {
                    tNotify('error', 'Failed to load logs');
                }
            })
            .catch(error => {
                container.innerHTML = `
                    <div class="py-8 basicTable_wrapper items-center justify-center">
                        <iconify-icon icon="lucide:alert-circle" class="text-5xl text-danger mb-4"></iconify-icon>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">{{ __('Error loading logs') }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">${error.message}</p>
                    </div>
                `;
            });
        }

        // Initialize filter on page load
        document.addEventListener('DOMContentLoaded', function() {
            applyFilter(currentFilter);
        });

        function showDetails(logId) {
            const btn = event.target.closest('button');
            const error = btn.getAttribute('data-error');
            const trace = btn.getAttribute('data-trace');
            const context = btn.getAttribute('data-context');
            const resentAt = btn.getAttribute('data-resent-at');
            
            document.getElementById('errorMessage').textContent = error;
            document.getElementById('stackTrace').textContent = trace || 'N/A';
            
            // Show/hide resent info
            const resentInfo = document.getElementById('resentInfo');
            if (resentAt && resentAt !== 'null') {
                resentInfo.classList.remove('hidden');
                document.getElementById('resentDetails').innerHTML = `
                    <strong>Resent At:</strong> ${new Date(resentAt).toLocaleString()}
                `;
            } else {
                resentInfo.classList.add('hidden');
            }
            
            // Parse context to extract and display shortcodes
            try {
                const contextData = JSON.parse(context);
                const shortcodesDiv = document.getElementById('shortcodes');
                const contextDiv = document.getElementById('context');
                
                // Get shortcodes from dedicated column (preferred) or context (fallback)
                const shortcodesFromColumn = btn.getAttribute('data-shortcodes');
                let shortcodesData = null;
                
                // Try dedicated column first
                if (shortcodesFromColumn && shortcodesFromColumn !== 'null') {
                    try {
                        shortcodesData = JSON.parse(shortcodesFromColumn);
                    } catch (e) {
                        // Fall back to context
                    }
                }
                
                // Fallback to context if no dedicated column data
                if (!shortcodesData && contextData.shortcodes) {
                    shortcodesData = contextData.shortcodes;
                }
                
                // Display shortcodes if available
                if (shortcodesData && Object.keys(shortcodesData).length > 0) {
                    let shortcodesHtml = '<table class="w-full text-xs">';
                    for (const [key, value] of Object.entries(shortcodesData)) {
                        shortcodesHtml += `<tr><td class="font-medium py-1 pr-3">${key}:</td><td class="py-1">${value}</td></tr>`;
                    }
                    shortcodesHtml += '</table>';
                    shortcodesDiv.innerHTML = shortcodesHtml;
                } else {
                    shortcodesDiv.textContent = 'No shortcodes available';
                }
                
                // Display remaining context (remove shortcodes to avoid duplication)
                const displayContext = {...contextData};
                delete displayContext.shortcodes;
                contextDiv.textContent = JSON.stringify(displayContext, null, 2);
            } catch (e) {
                document.getElementById('shortcodes').textContent = 'Unable to parse shortcodes';
                document.getElementById('context').textContent = context;
            }
            
            $('#detailsModal').modal('show');
        }

        let currentResendLogId = null;

        function resendEmail(logId) {
            currentResendLogId = logId;
            $('#confirmResendModal').modal('show');
        }

        document.getElementById('confirmResendButton').addEventListener('click', function() {
            if (!currentResendLogId) return;

            $('#confirmResendModal').modal('hide');

            fetch(`{{ route('admin.smtp.monitoring.logs') }}/../resend-email/${currentResendLogId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    tNotify('success', data.message + ' Page will refresh...');
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    tNotify('error', data.message);
                }
            })
            .catch(error => {
                tNotify('error', 'Failed to resend email: ' + error.message);
            })
            .finally(() => {
                currentResendLogId = null;
            });
        });

        let currentDeleteLogId = null;

        function deleteLog(logId) {
            currentDeleteLogId = logId;
            document.getElementById('log-id').textContent = '#' + logId;
            $('#confirmDeleteLog').modal('show');
        }

        document.getElementById('confirmDeleteLogButton').addEventListener('click', function() {
            if (!currentDeleteLogId) return;

            $('#confirmDeleteLog').modal('hide');

            fetch(`{{ route('admin.smtp.monitoring.logs') }}/../delete-log/${currentDeleteLogId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    tNotify('success', data.message);
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    tNotify('error', data.message);
                }
            })
            .catch(error => {
                tNotify('error', 'Failed to delete log: ' + error.message);
            })
            .finally(() => {
                currentDeleteLogId = null;
            });
        });

        function handleClearLogs() {
            $('#confirmClearLogs').modal('show');
        }

        document.getElementById('confirmClearLogsButton').addEventListener('click', function() {
            $('#confirmClearLogs').modal('hide');

            fetch('{{ route('admin.smtp.monitoring.clear-logs') }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    tNotify('success', data.message);
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    tNotify('error', data.message);
                }
            })
            .catch(error => {
                tNotify('error', 'Failed to clear logs: ' + error.message);
            });
        });
    </script>
@endsection

