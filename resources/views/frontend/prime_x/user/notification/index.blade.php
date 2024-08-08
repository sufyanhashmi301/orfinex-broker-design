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
        <div class="card-body p-6">
            <ul class="list-item space-y-3 h-full overflow-x-auto">
                @foreach($notifications as $notification)
                <li class="flex items-center space-x-3 rtl:space-x-reverse border-b border-slate-100 dark:border-slate-700 last:border-b-0 pb-3 last:pb-0" @class(['single-list', 'read' => $notification->read ])>
                    <div>
                        <div class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                            <iconify-icon icon="lucide:{{ $notification->icon }}"></iconify-icon>
                        </div>
                    </div>
                    <div class="text-start overflow-hidden text-ellipsis whitespace-nowrap max-w-[63%]">
                        <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                            {{ $notification->notice }}
                        </h4>
                        <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="flex-1 ltr:text-right rtl:text-left">
                        <a href="{{ route('user.read-notification', $notification->id) }}" class="btn inline-flex justify-center btn-dark btn-sm">
                            <span class="flex items-center">
                                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="quill:link-out"></iconify-icon>
                                <span>{{ __('Explore') }}</span>
                            </span>
                        </a>
                    </div>
                </li>
                @endforeach
            </ul>
            {{ $notifications->links() }}
        </div>
    </div>
@endsection
