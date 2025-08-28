@extends('frontend::layouts.user')
@section('title')
    {{ __('Support Tickets') }}
@endsection
@section('content')
<div x-data="simpleTicketManager()">
    <div class="pageTitle mb-6 grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-5 xl:grid-cols-4">
        <div class="flex gap-5 rounded-xl border border-gray-200 bg-white p-4 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="bg-brand-500/10 text-brand-500 inline-flex h-14 w-14 items-center justify-center rounded-xl">
                <i data-lucide="ticket"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-title-xs mb-1 font-semibold text-gray-800 dark:text-white/90">
                    {{ $totalTickets }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Total Tickets') }}
                </p>
            </div>
            <button @click="filterStatus = 'all'" 
                :class="filterStatus === 'all' ? 'text-brand-600' : 'text-gray-400'"
                class="inline-flex items-center justify-center ml-auto hover:text-brand-500">
                <i data-lucide="chevron-right"></i>
            </button>
        </div>
        <div class="flex gap-5 rounded-xl border border-gray-200 bg-white p-4 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="bg-error-500/10 text-error-500 inline-flex h-14 w-14 items-center justify-center rounded-xl">
                <i data-lucide="circle-x"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-title-xs mb-1 font-semibold text-gray-800 dark:text-white/90">
                    {{ $closedTickets }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Closed Tickets') }}
                </p>
            </div>
            <button @click="filterStatus = 'closed'" 
                :class="filterStatus === 'closed' ? 'text-error-600' : 'text-gray-400'"
                class="inline-flex items-center justify-center ml-auto hover:text-error-500">
                <i data-lucide="chevron-right"></i>
            </button>
        </div>
        <div class="flex gap-5 rounded-xl border border-gray-200 bg-white p-4 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="bg-warning-500/10 text-warning-500 inline-flex h-14 w-14 items-center justify-center rounded-xl">
                <i data-lucide="hourglass"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-title-xs mb-1 font-semibold text-gray-800 dark:text-white/90">
                    {{ $openTickets }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Open Tickets') }}
                </p>
            </div>
            <button @click="filterStatus = 'open'" 
                :class="filterStatus === 'open' ? 'text-warning-600' : 'text-gray-400'"
                class="inline-flex items-center justify-center ml-auto hover:text-warning-500">
                <i data-lucide="chevron-right"></i>
            </button>
        </div>
        <div class="flex gap-5 rounded-xl border border-gray-200 bg-white p-4 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="bg-success-500/10 text-success-500 inline-flex h-14 w-14 items-center justify-center rounded-xl">
                <i data-lucide="circle-check"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-title-xs mb-1 font-semibold text-gray-800 dark:text-white/90">
                    {{ $resolvedTickets }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Resolved Tickets') }}
                </p>
            </div>
            <button @click="filterStatus = 'resolved'" 
                :class="filterStatus === 'resolved' ? 'text-success-600' : 'text-gray-400'"
                class="inline-flex items-center justify-center ml-auto hover:text-success-500">
                <i data-lucide="chevron-right"></i>
            </button>
        </div>
    </div>
    <div class="innerMenu flex justify-between flex-wrap items-center mb-3">
        <h4 class="text-xl font-semibold text-gray-800 dark:text-white/90">
            {{ __('Tickets') }}
        </h4>
        <div class="flex space-x-2 sm:justify-end items-center">
            <x-forms.button type="button" size="md" variant="primary" icon="plus" icon-position="left" @click="$store.modals.open('newTicket')">
                {{ __('Create Ticket') }}
            </x-forms.button>
        </div>
    </div>
    <!-- Desktop Table -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-xs dark:border-gray-800 dark:bg-white/[0.03] hidden md:block">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-5 py-3 text-left sm:px-6">
                            <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Ticket #') }}
                            </span>
                        </th>
                        <th class="px-5 py-3 text-left sm:px-6">
                            <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Subject') }}
                            </span>
                        </th>
                        <th class="px-5 py-3 text-left sm:px-6">
                            <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Created') }}
                            </span>
                        </th>
                        <th class="px-5 py-3 text-left sm:px-6">
                            <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Priority') }}
                            </span>
                        </th>
                        <th class="px-5 py-3 text-left sm:px-6">
                            <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Status') }}
                            </span>
                        </th>
                        <th class="px-5 py-3 text-left sm:px-6">
                            <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-for="(ticket, index) in filteredTickets" :key="ticket ? ticket.uuid || `ticket-${index}` : `empty-${index}`">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50" x-show="ticket">
                            <td x-text="ticket?.uuid || ''" class="px-5 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"></td>
                            <td x-text="ticket?.title || ''" class="px-5 py-4 text-sm text-gray-900 dark:text-white"></td>
                            <td x-text="ticket ? formatDate(ticket.created_at) : ''" class="px-5 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"></td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <x-badge 
                                    size="sm"
                                    x-bind:class="ticket.priority === 'low' 
                                        ? 'bg-success-50 text-success-700 dark:bg-success-900/20 dark:text-success-400' : ticket.priority === 'normal' 
                                        ? 'bg-warning-50 text-warning-700 dark:bg-warning-900/20 dark:text-warning-400' : ticket.priority === 'high' 
                                        ? 'bg-error-50 text-error-700 dark:bg-error-900/20 dark:text-error-400' : 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80'"    >
                                    <span 
                                        x-text="ticket && ticket.priority 
                                            ? ticket.priority.charAt(0).toUpperCase() + ticket.priority.slice(1) 
                                            : ''">
                                    </span>
                                </x-badge>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full" 
                                        :class="ticket ? getStatusDotClass(ticket.status) : 'bg-gray-400'"></span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400" x-text="ticket ? getStatusText(ticket.status) : ''"></span>
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm">
                                <div class="flex space-x-2" x-show="ticket">
                                    <template x-if="ticket && ticket.status === 'open'">
                                        <a :href="getCloseUrl(ticket.uuid)" 
                                            class="h-7 w-7 flex items-center justify-center text-gray-500 dark:text-gray-400 border border-gray-200 rounded hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
                                            title="{{ __('Close Ticket') }}">
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                        </a>
                                    </template>
                                    <template x-if="ticket">
                                        <a :href="getShowUrl(ticket.uuid)" 
                                            class="h-7 w-7 flex items-center justify-center text-gray-500 dark:text-gray-400 border border-gray-200 rounded hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
                                            title="{{ __('View Ticket') }}">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            
            <div x-show="filteredTickets.length === 0" class="flex flex-col items-center justify-center gap-4 text-center py-8">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <svg width="32" height="32" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M26 19.875V30.9167" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M25.988 37.5417H26.0075" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400">{{ __('No tickets found for the selected filter.') }}</p>
            </div>
        </div>
    </div>

    <!-- Mobile Cards -->
    <div class="md:hidden space-y-4">
        <template x-for="(ticket, index) in filteredTickets" :key="ticket ? ticket.uuid || `mobile-ticket-${index}` : `mobile-empty-${index}`">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700" x-show="ticket">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400" x-text="ticket ? '#' + ticket.uuid : ''"></span>
                        <h3 class="font-medium text-gray-900 dark:text-white mt-1" x-text="ticket?.title || ''"></h3>
                    </div>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full" :class="ticket ? getStatusDotClass(ticket.status) : 'bg-gray-400'"></span>
                        <span class="text-sm" x-text="ticket ? getStatusText(ticket.status) : ''"></span>
                    </span>
                </div>
                
                <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400 mb-3">
                    <span x-text="ticket ? formatDate(ticket.created_at) : ''"></span>
                    <x-badge 
                        x-bind:class="ticket.priority === 'low' 
                            ? 'bg-success-50 text-success-700 dark:bg-success-900/20 dark:text-success-400' : ticket.priority === 'normal' 
                            ? 'bg-warning-50 text-warning-700 dark:bg-warning-900/20 dark:text-warning-400' : ticket.priority === 'high' 
                            ? 'bg-error-50 text-error-700 dark:bg-error-900/20 dark:text-error-400' : 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80'"    >
                        <span 
                            x-text="ticket && ticket.priority 
                                ? ticket.priority.charAt(0).toUpperCase() + ticket.priority.slice(1) 
                                : ''">
                        </span>
                    </x-badge>
                </div>
                
                <div class="flex space-x-2" x-show="ticket">
                    <template x-if="ticket && ticket.status === 'open'">
                        <a :href="getCloseUrl(ticket.uuid)" 
                           class="flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                            {{ __('Close') }}
                        </a>
                    </template>
                    <template x-if="ticket">
                        <a :href="getShowUrl(ticket.uuid)" 
                           class="flex items-center justify-center w-full px-3 py-2 font-medium text-white rounded-lg bg-brand-500 text-theme-sm shadow-theme-xs hover:bg-brand-600">
                            {{ __('View') }}
                        </a>
                    </template>
                </div>
            </div>
        </template>
        
        <div x-show="filteredTickets.length === 0" class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] text-center py-8">
            <i data-lucide="inbox" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400">{{ __('No tickets found for the selected filter.') }}</p>
        </div>
    </div>

    {{-- Modal for new ticket--}}
    @include('frontend::ticket.modal.__new_ticket')
</div>
@endsection
@section('script')
    <script>
        document.addEventListener('alpine:init', () => {
            // Modal store for ticket modal
            Alpine.store('modals', {
                current: null,
                data: {},
                
                open(modalName, payload = {}) {
                    this.current = modalName;
                    this.data = payload;
                    
                    // Defer icon creation to prevent forced reflow
                    setTimeout(() => {
                        if (window.lucide) lucide.createIcons();
                    }, 0);
                },
                
                close() {
                    this.current = null;
                    this.data = {};
                },
                
                isOpen(modalName) {
                    return this.current === modalName;
                }
            });
            
            Alpine.data('simpleTicketManager', () => ({
                filterStatus: 'all',
                tickets: @json($tickets ?? []),
                
                get filteredTickets() {
                    if (!Array.isArray(this.tickets)) {
                        return [];
                    }
                    
                    if (this.filterStatus === 'all') {
                        return this.tickets.filter(ticket => ticket && ticket.id);
                    }
                    
                    return this.tickets.filter(ticket => {
                        if (!ticket || !ticket.id) return false;
                        
                        if (this.filterStatus === 'resolved') {
                            return ticket.is_resolved;
                        }
                        return ticket.status === this.filterStatus;
                    });
                },
                
                formatDate(dateString) {
                    if (!dateString) return '';
                    try {
                        return new Date(dateString).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    } catch (e) {
                        return dateString;
                    }
                },
                
                getPriorityClass(priority) {
                    if (!priority) return 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80';
                    const classes = {
                        'high': 'bg-error-500/10 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                        'medium': 'bg-warning-500/10 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500',
                        'low': 'bg-success-500/10 text-success-600 dark:bg-success-500/15 dark:text-success-500'
                    };
                    return classes[priority] || classes['low'];
                },
                
                getStatusDotClass(status) {
                    if (!status) return 'bg-gray-400';
                    const classes = {
                        'open': 'bg-warning-500',
                        'closed': 'bg-success-500',
                        'resolved': 'bg-success-500'
                    };
                    return classes[status] || 'bg-gray-400';
                },
                
                getStatusText(status) {
                    if (!status) return '';
                    const texts = {
                        'open': '{{ __("Open") }}',
                        'closed': '{{ __("Closed") }}',
                        'resolved': '{{ __("Resolved") }}'
                    };
                    return texts[status] || status;
                },
                
                getShowUrl(uuid) {
                    if (!uuid) return '#';
                    return `{{ route('user.ticket.show', ':uuid') }}`.replace(':uuid', uuid);
                },
                
                getCloseUrl(uuid) {
                    if (!uuid) return '#';
                    return `{{ route('user.ticket.close.now', ':uuid') }}`.replace(':uuid', uuid);
                }
            }));
        });

        // File input handling for the modal
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('attach-input');
            const fileTitle = document.getElementById('fileTile');
            
            if (fileInput && fileTitle) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    // Use requestAnimationFrame to avoid forced reflow
                    requestAnimationFrame(() => {
                        if (file) {
                            fileTitle.textContent = file.name;
                            fileTitle.classList.remove('text-slate-400');
                            fileTitle.classList.add('text-gray-900', 'dark:text-white');
                        } else {
                            fileTitle.textContent = 'Choose a file or drop it here...';
                            fileTitle.classList.add('text-slate-400');
                            fileTitle.classList.remove('text-gray-900', 'dark:text-white');
                        }
                    });
                });
            }

            // Initialize Lucide icons once after DOM is ready
            if (window.lucide) {
                requestAnimationFrame(() => {
                    lucide.createIcons();
                });
            }
        });
    </script>
@endsection
