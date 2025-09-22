<button class="size-9.5 relative inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded border border-transparent text-gray-800 hover:bg-gray-100 hover:border-gray-600 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" @click.prevent="dropdownOpen = ! dropdownOpen; notifying = false">
    @if($totalUnread > 0)
        <span :class="!notifying ? 'hidden' : 'flex'" class="absolute top-0.5 right-0 z-1 h-2 w-2 rounded-full bg-orange-400">
            <span class="absolute -z-1 inline-flex h-full w-full animate-ping rounded-full bg-orange-400 opacity-75"></span>
        </span>
    @endif
    <i data-lucide="bell" class="shrink-0 size-5"></i>
</button>

<!-- Dropdown Start -->
<div x-show="dropdownOpen"
    class="shadow-theme-lg dark:bg-gray-dark absolute mt-[17px] flex h-[480px] flex-col rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-800 z-50 w-[calc(100vw-3rem)] max-w-[320px] sm:w-[350px] lg:w-[361px] right-2 lg:right-0">
    <div class="mb-3 flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800">
        <h5 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            {{ __('Notification') }}
        </h5>

        <button @click="dropdownOpen = false" class="text-gray-500 dark:text-gray-400">
            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92775 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C17.4854 18.0721 17.0105 18.0721 16.7176 17.7792L11.999 13.0607L7.28033 17.7794C6.98744 18.0722 6.51256 18.0722 6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z"
                    fill="" />
            </svg>
        </button>
    </div>

    <ul class="custom-scrollbar flex h-auto flex-col overflow-y-auto">
        @forelse($notifications as $notification)
            <li>
                <a class="flex gap-3 rounded-lg border-b border-gray-100 p-3 px-4.5 py-3 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-white/5"
                    href="{{ route($notification->for.'.read-notification', $notification->id) }}">
                    <span class="relative z-1 block h-8 w-full max-w-8 rounded-full">
                        <img src="{{ getFilteredPath(optional($notification->user)->avatar, 'fallback/user.png') }}" alt="User" class="overflow-hidden rounded-full" />
                    </span>

                    <span class="block">
                        <span class="text-theme-sm mb-1.5 block text-gray-500 dark:text-gray-400">
                            <span class="font-medium text-gray-800 dark:text-white/90">
                                {{ optional($notification->user)->full_name ?? __('System') }}
                            </span>
                            {{ $notification->notice }}
                        </span>

                        <span class="text-theme-xs flex items-center gap-2 text-gray-500 dark:text-gray-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </span>
                </a>
            </li>
        @empty
            <li class="flex flex-col items-center justify-center py-6 text-slate-400 dark:text-slate-500 text-center">
                <iconify-icon icon="lucide:bell-off" class="text-2xl mb-2"></iconify-icon>
                <p>{{ __('No new notifications') }}</p>
            </li>
        @endforelse
    </ul>

    @if(count($notifications) > 0)
        <a href="{{ route($notifications->first()->for.'.notification.all') }}" class="text-theme-sm shadow-theme-xs mt-3 flex justify-center rounded-lg border border-gray-300 bg-white p-3 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
            {{ __('View All Notification') }}
        </a>
    @endif
</div>
<!-- Dropdown End -->