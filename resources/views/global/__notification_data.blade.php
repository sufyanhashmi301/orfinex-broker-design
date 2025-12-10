<button type="button" class="item notification-dot lg:h-[32px] lg:w-[32px] dark:text-slate-900 text-white cursor-pointer rounded-lg text-[20px] flex flex-col items-center justify-center" data-bs-toggle="dropdown" aria-expanded="false">
    <iconify-icon class="animate-tada header-text-color text-xl" @class(['bell-ringng' => $totalUnread > 0]) icon="heroicons-outline:bell" icon-name="bell-ring"></iconify-icon>
    <div class="number absolute -right-1 lg:top-0 -top-[6px] h-4 w-4 bg-red-500 text-[8px] font-semibold flex flex-col items-center justify-center rounded-full text-white z-[99]">
        {{ $totalUnread }}
    </div>
</button>
<!-- Notification Dropdown -->
<div class="dropdown-menu dropdown-menu-end notification-pop dropdown-menu z-10 hidden bg-white shadow w-[335px] border dark:border-slate-700 !top-[10px] rounded-md overflow-hidden lrt:origin-top-right rtl:origin-top-left">
    <div class="flex items-center justify-between py-4 px-4">
        <h3 class="text-sm font-Inter font-medium text-slate-700 dark:text-white">
            {{ __('Notifications') }}
        </h3>
        @if(count($notifications) > 0)
            <a class="loaderBtn text-xs font-Inter font-normal underline text-slate-500 dark:text-white"
               href="{{ route($notifications->first()->for.'.notification.all') }}">
                {{ __('See All') }}
            </a>
        @endif
    </div>

    <div class="all-noti divide-y divide-slate-100 dark:divide-slate-700">
        @forelse($notifications as $notification)
            <div class="single-noti text-slate-600 dark:text-slate-300 block w-full px-4 py-2 text-sm">
                <a href="{{ route($notification->for.'.read-notification', $notification->id) }}"
                   class="loaderBtn flex ltr:text-left rtl:text-right @if($notification->read) opacity-50 @endif">
                    <div class="icon flex-none ltr:mr-3 rtl:ml-3">
                        <div class="lg:h-[32px] lg:w-[32px] bg-slate-100 dark:bg-slate-900 rounded-full flex items-center justify-center text-[20px]">
                            <iconify-icon class="text-slate-800 dark:text-white text-lg"
                                          icon="lucide:{{ $notification->icon ?? 'bell' }}"></iconify-icon>
                        </div>
                    </div>
                    <div class="content flex-1">
                        <div class="main-cont text-slate-600 dark:text-slate-300 text-sm mb-1">
                            @if($notification->for === 'admin' && $notification->notice)
                                {{-- For admin notifications, notice already contains the user's name, so don't duplicate --}}
                                {{ $notification->notice }}
                            @else
                                {{-- For user notifications, show user name + notice --}}
                                <span class="font-medium">{{ optional($notification->user)->full_name ?? __('System') }}</span>
                                {{ $notification->notice }}
                            @endif
                        </div>
                        <div class="time text-xs">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                </a>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-6 text-slate-400 dark:text-slate-500 text-center">
                <iconify-icon icon="lucide:bell-off" class="text-2xl mb-2"></iconify-icon>
                <p>{{ __('No new notifications') }}</p>
            </div>
        @endforelse
    </div>
</div>
