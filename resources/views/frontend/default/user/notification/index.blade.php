@extends('frontend::layouts.user')
@section('title')
    {{ __('All Notifications') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary font-Inter ">
                {{ __('Notifications') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('All Notifications') }}
            </li>
        </ul>
    </div>
    <div class="card">
        <header class=" card-header">
            <h4 class="card-title">
                {{ __('All Notifications') }}
            </h4>
        </header>
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
