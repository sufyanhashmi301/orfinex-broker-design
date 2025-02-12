<div class="lg:col-span-8 col-span-12">
    <div class="card h-full">
        <div class="card-header noborder">
            <h3 class="card-title">{{ __('Latest Tickets') }}</h3>
            @canany(['support-ticket-list','support-ticket-action'])
                <a href="{{ route('admin.ticket.index') }}" class="inline-flex items-center underline">
                    {{ __('See All') }}
                    <iconify-icon class="text-lg ltr:ml-1 rtl:mr-1" icon="lucide:chevron-right"></iconify-icon>
                </a>
            @endcanany
        </div>
        <div class="card-body px-6 pb-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Tickets #') }}</th>
                                    <th scope="col" class="table-th">{{ __('Ticket Subject') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Requested On') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($data['latest_tickets'] as $ticket)
                                    <tr>
                                        <td class="table-td">
                                            {{ $ticket->uuid }}
                                        </td>
                                        <td class="table-td">
                                            @can('support-ticket-action')
                                                <a href="{{ route('admin.ticket.show',$ticket->uuid) }}" class="font-semibold hover:underline">
                                                    {{ $ticket->title }}
                                                </a>
                                            @endcan
                                        </td>
                                        <td class="table-td">
                                            <span class="inline-block text-center mx-auto py-1">
                                                <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                    @if($ticket->status == 'open')
                                                        <span class="h-[6px] w-[6px] bg-danger-500 rounded-full inline-block ring-4 ring-opacity-30 ring-danger-500"></span>
                                                    @elseif($ticket->status == 'closed')
                                                        <span class="h-[6px] w-[6px] bg-success-500 rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                    @elseif($ticket->status == 'archived')
                                                        <span class="h-[6px] w-[6px] bg-warning-500 rounded-full inline-block ring-4 ring-opacity-30 ring-warning-500"></span>
                                                    @endif
                                                    <span>{{ $ticket->status }}</span>
                                                </span>
                                            </span>
                                        </td>
                                        <td class="table-td">
                                            {{ $ticket->created_at }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="lg:col-span-4 col-span-12">
    <div class="card h-full">
        <div class="card-header">
            <h3 class="card-title">{{ __('Ticket Statistics') }}</h3>
        </div>
        <div class="card-body p-6">
            <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                <div class="bg-slate-50 dark:bg-slate-900 rounded p-4">
                    <div class="text-sm text-slate-600 dark:text-slate-300 mb-[6px]">
                        {{ __('Total Tickets') }}
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ $data['total_ticket'] }}
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-900 rounded p-4">
                    <div class="text-sm text-slate-600 dark:text-slate-300 mb-[6px]">
                        {{ __('Closed Tickets') }}
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ $data['closed_tickets'] }}
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-900 rounded p-4">
                    <div class="text-sm text-slate-600 dark:text-slate-300 mb-[6px]">
                        {{ __('Open Tickets') }}
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ $data['open_tickets'] }}
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-900 rounded p-4">
                    <div class="text-sm text-slate-600 dark:text-slate-300 mb-[6px]">
                        {{ __('Resolved Tickets') }}
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ $data['resolved_tickets'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
