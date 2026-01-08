<div class="grid sm:grid-cols-2 grid-cols-1 gap-3">
    @forelse($tickets as $ticket)
        <div class="card shadow">
            <div class="card-header !p-4">
                <h4 class="card-title">
                    <span class="mr-1">
                        #{{ $ticket->uuid }}
                    </span>
                    @if($ticket->status == 'open')
                        <span class="badge badge-warning">{{ __('Opened') }}</span>
                    @elseif($ticket->status == 'closed')
                        <span class="badge badge-success">{{ __('Completed') }}</span>
                    @endif
                </h4>
                <div>
                    <div class="dropstart relative">
                        <button class="inline-flex justify-center items-center" type="button" id="tableDropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                            <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="heroicons-outline:dots-vertical"></iconify-icon>
                        </button>
                        <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                            @if($ticket->status == 'open')
                                <li>
                                    <a href="{{ route('user.ticket.close.now', $ticket->uuid) }}" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-100  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                        <iconify-icon icon="heroicons:check-16-solid"></iconify-icon>
                                        <span>{{ __('Complete') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.ticket.show', $ticket->uuid) }}" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-100  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                        <iconify-icon icon="heroicons:eye"></iconify-icon>
                                        <span>{{ __('View') }}</span>
                                    </a>
                                </li>
                            @elseif($ticket->status == 'closed')
                                <li>
                                    <a href="#" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-100  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                        <iconify-icon icon="heroicons:check-16-solid"></iconify-icon>
                                        <span>{{ __('Complete') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.ticket.show', $ticket->uuid) }}" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-100  last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize  rtl:space-x-reverse">
                                        <iconify-icon icon="heroicons:book-open"></iconify-icon>
                                        <span>{{ __('Re-open') }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body flex flex-col gap-3 p-4">
                <p class="text-sm mb-3">
                    <span class="text-slate-600 dark:text-slate-100 mb-[6px]">{{ __('Subject :') }}</span>
                    <span class="block text-slate-900 dark:text-white">{{ $ticket->title }}</span>
                </p>
                <div class="flex justify-between space-x-4 rtl:space-x-reverse">
                    <div>
                        <span class="block date-label">{{ __('Created At:') }}</span>
                        <span class="block date-text">{{ toSiteTimezone($ticket->created_at) }}</span>
                    </div>
                    <div>
                        <span class="block date-label">{{ __('Priority:') }}</span>
                        <span class="block date-text">{{ ucfirst($ticket->priority) }}</span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="basicTable_wrapper card flex items-center justify-center flex-col p-4">
            <svg width="42" height="43" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-sm text-slate-600 dark:text-slate-100 my-3">
                {{ __('No tickets found.') }}
            </p>
        </div>
    @endforelse
    {{ $tickets->links() }}
</div>
