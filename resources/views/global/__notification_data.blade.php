<button type="button" class="item notification-dot lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer
rounded-full text-[20px] flex flex-col items-center justify-center" data-bs-toggle="dropdown" aria-expanded="false">
    <iconify-icon class="animate-tada text-slate-800 dark:text-white text-xl" @class(['bell-ringng' => $totalUnread > 0]) icon="heroicons-outline:bell" icon-name="bell-ring"></iconify-icon>
    <div class="number absolute -right-1 lg:top-0 -top-[6px] h-4 w-4 bg-red-500 text-[8px] font-semibold flex flex-col items-center
    justify-center rounded-full text-white z-[99]">{{ $totalUnread }}</div>
</button>
<div class="dropdown-menu dropdown-menu-end notification-pop dropdown-menu z-10 hidden bg-white shadow w-[335px] border dark:border-slate-700 !top-[23px] rounded-md overflow-hidden lrt:origin-top-right rtl:origin-top-left">
    <div class="d-flex align-items-center justify-content-between flex items-center justify-between py-4 px-4">
        <h3 class="text-sm font-Inter font-medium text-slate-700 dark:text-white">
            {{ __('Notifications') }}
        </h3>
        @if(count($notifications)>0)
        <a class="text-xs font-Inter font-normal underline text-slate-500 dark:text-white"
            href="{{ route($notifications->first()->for.'.notification.all') }}">
            {{ __('See All') }}
        </a>
        @endif
    </div>

    <div class="all-noti divide-y divide-slate-100 dark:divide-slate-700">
        {{--        {{dd($notifications)}}--}}
        @foreach($notifications as $notification)
            <div class="single-noti text-slate-600 dark:text-slate-300 block w-full px-4 py-2 text-sm">
                <a href="{{ route($notification->for.'.read-notification', $notification->id) }}"
                   class="flex ltr:text-left rtl:text-right" @class(['read' => $notification->read ])>
                <div class="icon flex-none ltr:mr-3 rtl:ml-3">
                    <div class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center">
                        <iconify-icon class="text-slate-800 dark:text-white text-lg"
                                      icon-name="{{ $notification->icon }}"
                                      icon="lucide:{{ $notification->icon }}"></iconify-icon>
                    </div>
                </div>
                <div class="content flex-1">
                    <div class="main-cont text-slate-600 dark:text-slate-300 text-sm mb-1">
                        <span class="font-medium">{{ $notification->user->full_name }}</span>
                        {{ $notification->notice }}
                    </div>
                    <div class="time text-xs">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
                </a>
            </div>
        @endforeach

        @if($totalCount == 0)
        <div class="text-slate-600 dark:text-slate-300 block w-full px-4 py-2 text-sm">
            <p class="text-center mb-2">{{ __('Notification Not Found') }}</p>
        </div>
        @endif
    </div>
</div>

