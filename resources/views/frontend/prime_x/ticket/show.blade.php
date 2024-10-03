@extends('frontend::layouts.user')
@section('title')
    {{ __('Add New Support Ticket') }}
@endsection
@section('content')
    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-4 col-span-12">
            <div class="card">
                <div class="card-body p-6">
                    <di class="space-y-5">
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">Tell Us!</p>
                            <p class="text-sm dark:text-slate-100">Please provide us with as much information as possible when creating your support ticket. The more details you share, the better we can assist you.</p>
                        </div>
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">Show Us!</p>
                            <p class="text-sm dark:text-slate-100">
                                If you're encountering any issues, consider attaching screenshots or images to your ticket. Visual aids can help us better understand and address your concerns.
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">Caution</p>
                            <p class="text-sm dark:text-slate-100">
                                Please be aware that our ticket response time may extend up to 2 business days.
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">Response Time</p>
                            <p class="text-sm dark:text-slate-100">
                                Our dedicated support team is available from Monday to Friday, operating from 8:00 AM to 8:00 PM (Australian Timezone) and 9:00 AM to 6:00 PM (Dubai Time Zone). We make every effort to handle tickets promptly. However, during weekends or government holidays, our response time may experience a delay of one or two business days.
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-danger-600 font-medium mb-2">Important Notice</p>
                            <p class="text-sm dark:text-slate-100">
                                Tickets that remain unresponsive for more than one or two business days or are unrelated to our support items may be locked. Additionally, please avoid creating duplicate tickets, as this may also result in ticket locking. We appreciate your cooperation in helping us provide efficient support.
                            </p>
                        </div>
                    </di>
                </div>
            </div>
        </div>
        <div class="lg:col-span-8 col-span-12">
            <div class="h-screen card">
                <div class="h-full">
                    <header class="border-b border-slate-100 dark:border-slate-700">
                        <div class="flex flex-wrap items-center py-6 md:px-6 px-3 gap-5">
                            <div class="md:flex-1">
                                <h4 class="card-title flex items-center">
                                    {{ $ticket->title.' - '.$ticket->uuid }}
                                    <span class="badge badge-primary bg-opacity-30 capitalize rounded-3xl ml-2">
                                        {{ __('Opened') }}
                                    </span>
                                </h4>
                            </div>
                            <div class="flex-none flex md:space-x-3 space-x-1 items-center rtl:space-x-reverse">
                                <a href="{{ route('user.ticket.close.now',$ticket->uuid) }}" class="btn btn-dark btn-sm inline-flex items-center">
                                    {{ __('Mark it close') }}
                                </a>
                            </div>
                        </div>
                    </header>
                    <!-- header -->
                    <div class="chat-content parent-height">
                        <div class="msgs overflow-y-auto msg-height clearfix p-6 space-y-6">
                            <div class="card ring-1 ring-slate-700 overflow-hidden float-right w-11/12 md:w-2/3 mb-3">
                                <div class="bg-slate-100 dark:bg-slate-700 p-3">
                                    <div class="flex items-center">
                                        <div class="flex-none">
                                            <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                <img src="{{ asset($ticket->user->avatar ?? 'global/materials/user.png')}}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                            </div>
                                        </div>
                                        <div class="flex-1 text-start">
                                            <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                {{ $user->full_name }}
                                            </h4>
                                            <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-6">
                                    <div class="card-text message-body">
                                        {!! $ticket->message !!}
                                    </div>
                                    <div class="message-attachments mt-4">
                                        <h5 class="card-subtitle">{{ __('Attachments') }}</h5>
                                        <div class="single-attachment">
                                            <div class="attach">
                                                <a href="{{ asset($ticket->attach) }}" class="btn-link" target="_blank">
                                                    <i class="anticon anticon-picture"></i>
                                                    {{ substr($ticket->attach,14) }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @foreach($ticket->messages as $message )
                                <div class="card ring-1 ring-slate-700 overflow-hidden w-11/12 md:w-2/3 mb-3 support-ticket-single-message @if($message->model == 'admin') admin @else float-right @endif ">
                                    <div class="bg-slate-100 dark:bg-slate-700 p-3">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                    @if( $message->model != 'admin')
                                                    <img class="w-full h-full rounded-[100%] object-cover" src="{{ asset($ticket->user->avatar ?? 'global/materials/user.png')}}" alt="">
                                                    @else
                                                    <img class="w-full h-full rounded-[100%] object-cover" src="{{ asset($message->user->avatar ?? 'global/materials/user.png' )}}" alt="">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-1 text-start">
                                                @if($message->model != 'admin')
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    {{ $user->full_name }}
                                                </h4>
                                                <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                    {{ $user->email }}
                                                </div>
                                                @else
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    {{ $message->user->name }}
                                                </h4>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-6">
                                        <div class="card-text message-body">
                                            {!! $message->message !!}
                                        </div>
                                        @if($message->attach)
                                        <div class="message-attachments mt-4">
                                            <h5 class="card-subtitle">{{ __('Attachments') }}</h5>
                                            <div class="single-attachment">
                                                <div class="attach">
                                                    <a href="{{ asset($message->attach) }}" class="btn-link" target="_blank">
                                                        <i class="anticon anticon-picture"></i>
                                                        {{ substr($message->attach,14) }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- message -->
                    <footer class="md:px-6 px-4 border-t md:pt-6 pt-4 border-slate-100 dark:border-slate-700">
                        <form action="{{ route('user.ticket.reply') }}" method="post" enctype="multipart/form-data" class="sm:flex md:space-x-4 sm:space-x-2 rtl:space-x-reverse">
                            @csrf
                            <input type="hidden" name="uuid" value="{{ $ticket->uuid }}">
                            <input type="file" name="attach" id="attach" class="hidden" accept=".gif, .jpg, .png" />
                            <div class="flex-none sm:flex hidden md:space-x-3 space-x-1 rtl:space-x-reverse">
                                <label class="h-8 w-8 cursor-pointer bg-slate-100 dark:bg-slate-900 dark:text-slate-400 flex flex-col justify-center items-center text-xl rounded-full" for="attach">
                                    <iconify-icon icon="heroicons-outline:link"> </iconify-icon>
                                </label>
                            </div>
                            <div class="flex-1 relative flex space-x-3 rtl:space-x-reverse">
                                <div class="flex-1">
                                    <textarea placeholder="Type your message..." class="focus:ring-0 focus:outline-0 block w-full bg-transparent dark:text-white resize-none" name="message"></textarea>
                                </div>
                                <div class="flex-none md:pr-0 pr-3">
                                    <button type="submit" class="h-8 w-8 bg-slate-900 text-white flex flex-col justify-center items-center text-lg rounded-full">
                                        <iconify-icon icon="heroicons-outline:paper-airplane" class="transform rotate-[60deg]"></iconify-icon>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </footer>
                    <!-- end footer -->
                </div>
            </div>
        </div>
    </div>
@endsection
