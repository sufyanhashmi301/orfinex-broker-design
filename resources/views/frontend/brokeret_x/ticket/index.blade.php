@extends('frontend::layouts.user')
@section('title')
    {{ __('Support hub') }}
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            @yield('title')
        </h2>
    </div>

    <div x-data="simpleTicketManager()" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="h-full flex flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="overflow-hidden rounded-t-lg">
                    <img src="{{ asset('common/images/contact-assistance.webp') }}" alt="card" class="overflow-hidden">
                </div>

                <div class="flex-1 flex flex-col items-start p-5">
                    <h4 class="mb-1 text-theme-lg font-medium text-gray-800 dark:text-white/90">
                        {{ __('Need assistance?') }}
                    </h4>
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400 mb-5">
                        {{ __('Complete the form and we will get back to you shortly.') }}
                    </p>
                    <x-frontend::link-button href="#" class="mt-auto" variant="primary" icon="plus" icon-position="left" @click="$store.modals.open('newTicket')">
                        {{ __('Open a ticket') }}
                    </x-frontend::link-button>
                </div>
            </div>

            <div class="h-full flex flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="overflow-hidden rounded-t-lg">
                    <img src="{{ asset('common/images/contact-chat.webp') }}" alt="card" class="overflow-hidden">
                </div>

                <div class="flex-1 flex flex-col items-start p-5">
                    <h4 class="mb-1 text-theme-lg font-medium text-gray-800 dark:text-white/90">
                        {{ __('Live chat') }}
                    </h4>
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400 mb-5">
                        {{ __("Can't find the answers you're looking for? Chat with our Intelligent Assistant.") }}
                    </p>
                    <x-frontend::link-button href="javascript:void(0)" class="mt-auto" variant="secondary" icon="message-square-more" icon-position="left">
                        {{ __('Start chat') }}
                    </x-frontend::link-button>
                </div>
            </div>

            <div class="h-full flex flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="overflow-hidden rounded-t-lg">
                    <img src="{{ asset('common/images/contact-help.webp') }}" alt="card" class="overflow-hidden">
                </div>

                <div class="flex-1 flex flex-col items-start p-5">
                    <h4 class="mb-1 text-theme-lg font-medium text-gray-800 dark:text-white/90">
                        {{ __('Still need help?') }}
                    </h4>
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400 mb-1">
                        {{ __('To speak with our support team, call us at') }}
                    </p>
                    <x-frontend::text-link href="tel:{{ setting('company_phone', 'common_settings') }}" variant="secondary" icon="phone" icon-position="left">
                        {{ setting('company_phone', 'common_settings') }}
                    </x-frontend::text-link>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
                {{ __('My tickets') }}
            </h2>
        </div>

        <!-- Desktop Table -->
    <div class="rounded-lg border border-gray-200 bg-white shadow-xs dark:border-gray-800 dark:bg-white/[0.03] hidden md:block">
        <div class="flex items-center justify-between p-5 sm:p-6">
            <div class="relative min-w-48">
                <x-frontend::forms.select
                    id="statusFilter"
                    name="statusFilter"
                    x-model="filterStatus"
                    size="sm"
                    placeholder="{{ __('Select status') }}"
                    :options="[
                        'all' => __('All'),
                        'open' => __('Open'),
                        'closed' => __('Closed'),
                        'resolved' => __('Resolved'),
                    ]"
                />
            </div>
            <div class="relative min-w-48">
                <span class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <x-frontend::forms.input
                    id="search"
                    class="pl-12"
                    name="search"
                    x-model="search"
                    size="sm"
                    placeholder="{{ __('Search') }}"
                    @input="search = $event.target.value"
                />
            </div>
        </div>
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
                                    x-bind:class="getPriorityBadgeClass(ticket?.priority)">
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
            
            <x-frontend::empty-state x-show="filteredTickets.length === 0" class="!my-10" icon="inbox">
                <x-slot name="title">
                    {{ __('No matching results found') }}
                </x-slot>
                <x-slot name="subtitle">
                    {{ __('Please try a different keyword.') }}
                </x-slot>
            </x-frontend::empty-state>
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
                        x-bind:class="getPriorityBadgeClass(ticket?.priority)">
                        <span 
                            x-text="ticket && ticket.priority 
                                ? ticket.priority.charAt(0).toUpperCase() + ticket.priority.slice(1) 
                                : ''">
                        </span>
                    </x-badge>
                </div>
                
                <div class="flex space-x-2" x-show="ticket">
                    <template x-if="ticket && ticket.status === 'open'">
                        <x-frontend::link-button href="getCloseUrl(ticket.uuid)" class="flex-1" variant="secondary" size="sm" icon="check" iconPosition="left">
                            {{ __('Close') }}
                        </x-frontend::link-button>
                    </template>
                    <template x-if="ticket">
                        <x-frontend::link-button href="getShowUrl(ticket.uuid)" class="flex-1" variant="primary" size="sm" icon="eye" iconPosition="left">
                            {{ __('View') }}
                        </x-frontend::link-button>
                    </template>
                </div>
            </div>
        </template>
        
        <x-frontend::empty-state x-show="filteredTickets.length === 0" class="!my-10" icon="inbox">
            <x-slot name="title">
                {{ __('No matching results found') }}
            </x-slot>
            <x-slot name="subtitle">
                {{ __('Please try a different keyword.') }}
            </x-slot>
        </x-frontend::empty-state>
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
                search: '',
                tickets: @json($tickets ?? []),
                routes: {
                    show: '{{ route("user.ticket.show", ":uuid") }}',
                    close: '{{ route("user.ticket.close.now", ":uuid") }}'
                },
                
                get filteredTickets() {
                    if (!Array.isArray(this.tickets)) {
                        return [];
                    }
                    
                    let filtered = this.tickets.filter(ticket => ticket && ticket.id);
                    
                    // Apply status filter
                    if (this.filterStatus !== 'all') {
                        filtered = filtered.filter(ticket => {
                            if (this.filterStatus === 'resolved') {
                                return ticket.is_resolved;
                            }
                            return ticket.status === this.filterStatus;
                        });
                    }
                    
                    // Apply search filter
                    if (this.search && this.search.trim()) {
                        const searchTerm = this.search.toLowerCase();
                        filtered = filtered.filter(ticket => 
                            ticket.title?.toLowerCase().includes(searchTerm) ||
                            ticket.uuid?.toLowerCase().includes(searchTerm)
                        );
                    }
                    
                    return filtered;
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
                
                getPriorityBadgeClass(priority) {
                    if (!priority) return 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80';
                    const classes = {
                        'low': 'bg-success-50 text-success-700 dark:bg-success-900/20 dark:text-success-400',
                        'normal': 'bg-warning-50 text-warning-700 dark:bg-warning-900/20 dark:text-warning-400',
                        'high': 'bg-error-50 text-error-700 dark:bg-error-900/20 dark:text-error-400'
                    };
                    return classes[priority] || 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80';
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
                    return this.routes.show.replace(':uuid', uuid);
                },
                
                getCloseUrl(uuid) {
                    if (!uuid) return '#';
                    return this.routes.close.replace(':uuid', uuid);
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
                            fileTitle.classList.remove('text-gray-400');
                            fileTitle.classList.add('text-gray-900', 'dark:text-white');
                        } else {
                            fileTitle.textContent = 'Choose a file or drop it here...';
                            fileTitle.classList.add('text-gray-400');
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
