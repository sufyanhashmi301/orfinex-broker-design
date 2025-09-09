@extends('frontend::layouts.user')
@section('title')
    {{ __('All Notifications') }}
@endsection
@section('content')
    <div class="pageTitle flex flex-wrap items-center justify-between gap-3 mb-3">
        <h4 class="text-xl font-semibold text-gray-800 dark:text-white/90">
            @yield('title')
        </h4>

        <x-link-button href="{{ route('user.read-notification', 0) }}" variant="primary" icon="check" iconPosition="left">
            {{ __(' Mark all read') }}
        </x-link-button>
    </div>

    <x-card class="!pb-0">
        <div class="overflow-x-auto -mx-6">
            <div class="inline-block min-w-full align-middle">
                <ul class="list-item space-y-3 h-full overflow-hidden mb-3">
                    @foreach($notifications as $notification)
                        <li class="flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 last:border-b-0 px-3 pb-3 last:pb-0" @class(['single-list', 'read' => $notification->read ])>
                            <div>
                                <div class="w-10 h-10 lg:bg-gray-100 lg:dark:bg-gray-900 dark:text-white text-gray-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                    <i data-lucide="{{ $notification->icon }}"></i>
                                </div>
                            </div>
                            <div class="text-start overflow-hidden text-ellipsis whitespace-nowrap max-w-[63%]">
                                <h4 class="text-sm font-medium text-gray-600 whitespace-nowrap">
                                    {{ __('Notice:') }} {{ $notification->notice }}
                                </h4>
                                <div class="text-xs font-normal text-gray-600 dark:text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <x-text-link href="{{ route('user.read-notification', $notification->id) }}" class="ms-auto" variant="primary" icon="link" iconPosition="left">
                                {{ __('Explore') }}
                            </x-text-link>
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
    </x-card>
@endsection
