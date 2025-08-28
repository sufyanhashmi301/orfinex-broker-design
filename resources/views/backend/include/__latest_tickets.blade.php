<!-- Latest Tickets Section -->
<div class="lg:col-span-8 col-span-12">
    <div class="card enhanced-card h-full">
        <div class="enhanced-card-header">
            <div class="flex items-center justify-between">
                <h3 class="enhanced-card-title">
                    <iconify-icon icon="lucide:ticket" class="text-slate-600 dark:text-slate-400"></iconify-icon>
                    {{ __('Latest Tickets') }}
                </h3>
                @canany(['support-ticket-list'])
                    <a href="{{ route('admin.ticket.index') }}" class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition-colors duration-200">
                        {{ __('See All') }}
                        <iconify-icon class="text-base ltr:ml-2 rtl:mr-2" icon="lucide:arrow-right"></iconify-icon>
                    </a>
                @endcanany
            </div>
        </div>
        <div class="card-body p-0">
            <div class="overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                    {{ __('Ticket ID') }}
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                    {{ __('Subject') }}
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                    {{ __('Created') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($data['latest_tickets'] as $ticket)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.ticket.show',$ticket->uuid) }}" class="inline-flex items-center space-x-2 text-sm font-mono text-slate-600 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 transition-colors duration-200">
                                            <iconify-icon icon="lucide:hash" class="text-xs"></iconify-icon>
                                            <span>{{ $ticket->uuid }}</span>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-1 min-w-0">
                                                <a href="{{ route('admin.ticket.show',$ticket->uuid) }}" class="text-sm font-medium text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                                    {{ $ticket->title }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center">
                                            @if($ticket->status == 'open')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                    <span class="w-1.5 h-1.5 mr-1.5 bg-red-500 rounded-full"></span>
                                                    {{ $ticket->status }}
                                                </span>
                                            @elseif($ticket->status == 'closed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span>
                                                    {{ $ticket->status }}
                                                </span>
                                            @elseif($ticket->status == 'archived')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                                    <span class="w-1.5 h-1.5 mr-1.5 bg-amber-500 rounded-full"></span>
                                                    {{ $ticket->status }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-slate-500 dark:text-slate-400">
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

<!-- Ticket Statistics Section -->
<div class="lg:col-span-4 col-span-12">
    <div class="card enhanced-card h-full">
        <div class="enhanced-card-header">
            <h3 class="enhanced-card-title">
                <iconify-icon icon="lucide:bar-chart-3" class="text-slate-600 dark:text-slate-400"></iconify-icon>
                {{ __('Ticket Statistics') }}
            </h3>
        </div>
        <div class="card-body p-6">
            <div class="grid grid-cols-1 gap-4">
                <!-- Total Tickets -->
                <div class="bg-gradient-to-r from-slate-50 to-slate-100/50 dark:from-slate-800 dark:to-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-slate-600 rounded-lg flex items-center justify-center">
                                <iconify-icon icon="lucide:ticket" class="text-white text-lg"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">{{ __('Total Tickets') }}</p>
                                <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $data['total_ticket'] }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.ticket.index') }}" data-status="all" class="widget-filter-status w-8 h-8 rounded-lg bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors duration-200">
                            <iconify-icon icon="lucide:arrow-right" class="text-sm text-slate-600 dark:text-slate-300"></iconify-icon>
                        </a>
                    </div>
                </div>

                <!-- Open Tickets -->
                <div class="bg-gradient-to-r from-red-50 to-red-100/50 dark:from-red-900/20 dark:to-red-900/10 rounded-xl p-4 border border-red-200 dark:border-red-800 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                <iconify-icon icon="lucide:alert-circle" class="text-white text-lg"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-sm text-red-600 dark:text-red-400 font-medium">{{ __('Open Tickets') }}</p>
                                <p class="text-xl font-bold text-red-700 dark:text-red-300">{{ $data['open_tickets'] }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.ticket.index') }}" data-status="opened" class="widget-filter-status w-8 h-8 rounded-lg bg-white dark:bg-red-800 border border-red-200 dark:border-red-700 flex items-center justify-center hover:bg-red-50 dark:hover:bg-red-700 transition-colors duration-200">
                            <iconify-icon icon="lucide:arrow-right" class="text-sm text-red-600 dark:text-red-300"></iconify-icon>
                        </a>
                    </div>
                </div>

                <!-- Closed Tickets -->
                <div class="bg-gradient-to-r from-green-50 to-green-100/50 dark:from-green-900/20 dark:to-green-900/10 rounded-xl p-4 border border-green-200 dark:border-green-800 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                <iconify-icon icon="lucide:check-circle" class="text-white text-lg"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-sm text-green-600 dark:text-green-400 font-medium">{{ __('Closed Tickets') }}</p>
                                <p class="text-xl font-bold text-green-700 dark:text-green-300">{{ $data['closed_tickets'] }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.ticket.index') }}" data-status="closed" class="widget-filter-status w-8 h-8 rounded-lg bg-white dark:bg-green-800 border border-green-200 dark:border-green-700 flex items-center justify-center hover:bg-green-50 dark:hover:bg-green-700 transition-colors duration-200">
                            <iconify-icon icon="lucide:arrow-right" class="text-sm text-green-600 dark:text-green-300"></iconify-icon>
                        </a>
                    </div>
                </div>

                <!-- Resolved Tickets -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100/50 dark:from-blue-900/20 dark:to-blue-900/10 rounded-xl p-4 border border-blue-200 dark:border-blue-800 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                <iconify-icon icon="lucide:check-square" class="text-white text-lg"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">{{ __('Resolved Tickets') }}</p>
                                <p class="text-xl font-bold text-blue-700 dark:text-blue-300">{{ $data['resolved_tickets'] }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.ticket.index') }}" data-status="resolved" class="widget-filter-status w-8 h-8 rounded-lg bg-white dark:bg-blue-800 border border-blue-200 dark:border-blue-700 flex items-center justify-center hover:bg-blue-50 dark:hover:bg-blue-700 transition-colors duration-200">
                            <iconify-icon icon="lucide:arrow-right" class="text-sm text-blue-600 dark:text-blue-300"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>