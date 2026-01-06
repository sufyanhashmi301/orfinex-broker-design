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
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @foreach($logs as $log)
                <tr class="{{ $log->resent_at ? 'opacity-60' : '' }}">
                    <td class="table-td">{{ $log->id }}</td>
                    <td class="table-td">
                        <span class="text-gray-600 dark:text-gray-400">
                            {{ toSiteTimezone($log->created_at, 'Y-m-d H:i:s') }}
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
                            <span class="badge bg-success-500 text-white capitalize" data-tippy-content="Resent on {{ toSiteTimezone($log->resent_at, 'M d, Y H:i') }}">
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

