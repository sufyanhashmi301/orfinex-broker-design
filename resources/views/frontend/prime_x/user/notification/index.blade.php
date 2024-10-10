@extends('frontend::layouts.user')
@section('title')
    {{ __('All Notifications') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-5">
        <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('All Notifications') }}
        </h4>
    </div>
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <ul class="list-item space-y-3 h-full overflow-hidden mb-3">
                        @foreach($notifications as $notification)
                        <li class="flex items-center space-x-3 rtl:space-x-reverse border-b border-slate-100 dark:border-slate-700 last:border-b-0 px-3 pb-3 last:pb-0" @class(['single-list', 'read' => $notification->read ])>
                            <div>
                                <div class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                    <iconify-icon icon="lucide:{{ $notification->icon }}"></iconify-icon>
                                </div>
                            </div>
                            <div class="text-start overflow-hidden text-ellipsis whitespace-nowrap max-w-[63%]">
                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                    {{ __('Notice:') }} {{ $notification->notice }}
                                </h4>
                                <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="flex-1 ltr:text-right rtl:text-left">
                                <a href="{{ route('user.read-notification', $notification->id) }}" class="btn inline-flex justify-center btn-primary btn-sm">
                                    <span class="flex items-center">
                                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="quill:link-out"></iconify-icon>
                                        <span>{{ __('Explore') }}</span>
                                    </span>
                                </a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 py-3 mt-auto">
                        <div>
                            @php
                                $from = $notifications->firstItem(); // The starting item number on the current page
                                $to = $notifications->lastItem(); // The ending item number on the current page
                                $total = $notifications->total(); // The total number of items
                            @endphp

                            <p class="text-sm text-gray-700 dark:text-slate-300 px-3">
                                {{ __('Showing') }}
                                <span class="font-medium">{{ $from }}</span>
                                {{ __('to') }}
                                <span class="font-medium">{{ $to }}</span>
                                {{ __('of') }}
                                <span class="font-medium">{{ $total }}</span>
                                {{ __('results') }}
                            </p>
                        </div>
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
