@extends('backend.layouts.app')
@section('title')
    {{ __('Ticket Details') }}
@endsection
@section('content')
<div class="flex chat-height overflow-hidden relative">
    <div class="parent flex h-full w-full">
        <!-- end main message body -->
        <div class="flex-1">
            <div class="h-full card">
                <div class="p-0 h-full body-class">
                    <!-- BEGIN: Messages -->
                    <div class="h-full">
                        <header class="border-b border-slate-100 dark:border-slate-700 py-6 md:px-6 px-3">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <h4 class="card-title"> {{ $ticket->title.' - '.$ticket->uuid }}
                                        @if( $ticket->status == 'open')
                                            <span class="site-badge pending">{{ __('Open') }}</span>
                                        @elseif($ticket->status == 'closed')
                                            <span class="site-badge primary-bg ">{{ __('Completed') }}</span>
                                        @endif
                                    </h4>
                                </div>
                                <div class="flex-none">
                                    @if( $ticket->status == 'open')
                                        <a href="{{ route('admin.ticket.close.now',$ticket->uuid) }}" class="btn btn-dark btn-sm inline-flex items-center">
                                            {{ __('Close it') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </header>
                        <div class="chat-content bg-white dark:bg-slate-800" style="height: calc(100% - 155px);">
                            <div class="msgs overflow-y-auto msg-height pt-6 space-y-6">
                                <div class="block md:px-6 px-4">
                                    <div class="flex space-x-2 items-end rtl:space-x-reverse">
                                        <div class="flex-none">
                                            @if( null != $ticket->user->avatar)
                                                <div class="h-10 w-10 rounded-full">
                                                    <img class="block w-full h-full object-cover rounded-full" src="{{ asset($ticket->user->avatar)}}" alt="">
                                                </div>
                                            @else
                                                <div class="h-10 w-10 rounded-full flex flex-col items-center justify-center text-sm bg-[#EAE6FF] dark:bg-slate-900 text-[#5743BE]">
                                                    {{ $ticket->user->first_name[0] }} {{ $ticket->user->last_name[0] }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 flex space-x-4 rtl:space-x-reverse">
                                            <div>
                                                <span class="font-normal text-xs text-slate-400 dark:text-slate-400 mb-1">
                                                    {{ $ticket->user->first_name }} {{ $ticket->user->last_name }}
                                                </span>
                                                <div class="text-contrent p-3 bg-slate-100 dark:bg-slate-600 dark:text-slate-300 text-slate-600 text-sm font-normal rounded-md">
                                                    {!! $ticket->message !!}
                                                    <div class="mt-1">
                                                        <a href="{{ asset($ticket->attach) }}" class="inline-flex p-2 rounded-[6px] bg-[#EAE5FF] dark:bg-slate-900" target="_blank">
                                                            <img src="{{ asset($ticket->attach) }}" class="block h-[70px] w-[70px]" style="object-fit: scale-down;" alt="">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @foreach($ticket->messages as $message )
                                    <div class="block md:px-6 px-4">
                                        <div class="flex space-x-2 items-end rtl:space-x-reverse @if( $message->model == 'admin') justify-end w-full @endif">
                                            @if( $message->model != 'admin')
                                                <div class="flex-none">
                                                    <div class="h-10 w-10 rounded-full">
                                                        <img class="block w-full h-full object-cover rounded-full" src="{{ asset($ticket->user->avatar ?? 'global/materials/user.png')}}" alt="">
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="@if( $message->model == 'admin') no @else flex-1 @endif flex space-x-4 rtl:space-x-reverse">
                                                <div class="@if( $message->model == 'admin') text-right @endif">
                                                    <span class="font-normal text-xs text-slate-400 dark:text-slate-400 mb-1">
                                                        @if( $message->model != 'admin')
                                                            {{ $ticket->user->first_name }} {{ $ticket->user->last_name }}
                                                        @else
                                                            {{ $message->user->name }}
                                                        @endif
                                                    </span>
                                                    <div class="text-contrent p-3 bg-slate-100 dark:bg-slate-600 dark:text-slate-300 text-slate-600 text-sm font-normal rounded-md">
                                                        {!! $message->message !!}
                                                        @if($message->attach)
                                                            <div class="flex items-start gap-3 mt-1">
                                                                <a href="{{ asset($message->attach) }}" class="inline-flex p-2 rounded-[6px] bg-[#EAE5FF] dark:bg-slate-900" target="_blank">
                                                                    <img src="{{ asset($message->attach) }}" class="block h-[70px] w-[70px]" style="object-fit: scale-down;" alt="">
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @if( $message->model == 'admin')
                                                <div class="flex-none">
                                                    <div class="h-10 w-10 rounded-full">
                                                        <img class="block w-full h-full object-cover rounded-full" src="{{ asset($ticket->user->avatar ?? 'global/materials/user.png')}}" alt="">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- message -->
                        <form action="{{ route('admin.ticket.reply') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="uuid" value="{{ $ticket->uuid }}">
                            <footer class="md:px-6 px-4 sm:flex md:space-x-4 sm:space-x-2 rtl:space-x-reverse border-t md:pt-6 pt-4 border-slate-100 dark:border-slate-700">
                                <input type="file" name="attach" id="attach" class="hidden" accept=".gif, .jpg, .png" />
                                <label class="h-8 w-8 cursor-pointer bg-slate-100 dark:bg-slate-900 dark:text-slate-400 flex flex-col justify-center items-center text-xl rounded-full" for="attach">
                                    <iconify-icon icon="heroicons-outline:link"> </iconify-icon>
                                </label>
                                <div class="flex-1 relative flex space-x-3 rtl:space-x-reverse">
                                    <div class="flex-1">
                                        <textarea name="message" placeholder="Type your message..." class="focus:ring-0 focus:outline-0 block w-full bg-transparent dark:text-white resize-none"></textarea>
                                    </div>
                                    <div class="flex-none md:pr-0 pr-3">
                                        <button type="submit" class="h-8 w-8 bg-slate-900 text-white flex flex-col justify-center items-center text-lg rounded-full">
                                            <iconify-icon icon="heroicons-outline:paper-airplane" class="transform rotate-[60deg]"></iconify-icon>
                                        </button>
                                    </div>
                                </div>
                            </footer>
                            <!-- end footer -->
                        </form>
                    </div>

                    <!-- END: Message -->
                </div>
            </div>
        </div>
        <!-- right info bar -->

    </div>
</div>
@endsection