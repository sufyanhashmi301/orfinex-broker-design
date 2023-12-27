@extends('frontend::layouts.user')
@section('title')
    {{ __('Add New Support Ticket') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                {{ __('Support Tickets') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ $ticket->title.' - '.$ticket->uuid }}
            </li>
        </ul>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header flex-col md:flex-row items-start md:items-center">
                    <h4 class="card-title flex items-center">
                        {{ $ticket->title.' - '.$ticket->uuid }} 
                        <span class="badge bg-primary-500 text-primary-500 bg-opacity-30 capitalize rounded-3xl ml-2">
                            {{ __('Opened') }}
                        </span>    
                    </h4>
                    <div class="pt-3 md:pt-0 text-right md:text-left">
                        <a href="{{ route('user.ticket.close.now',$ticket->uuid) }}" class="btn btn-dark">
                            {{ __('Mark it close') }}
                        </a>
                    </div>
                </div>
                <div class="card-body overflow-hidden p-6">
                    <div class="card ring-1 ring-slate-700 overflow-hidden float-right w-2/3 mb-3">
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
                        <div class="card ring-1 ring-slate-700 overflow-hidden w-2/3 mb-3 support-ticket-single-message @if($message->model == 'admin') admin @else float-right @endif ">
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
        </div>
        <div class="col-span-12">
            <div class="card">
                <div class="card-body p-6">
                    <div class="progress-steps-form">
                        <form action="{{ route('user.ticket.reply') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="uuid" value="{{ $ticket->uuid }}">
                            <div class="wrap-custom-file mb-3">
                                <input
                                    type="file"
                                    name="attach"
                                    id="attach"
                                    accept=".gif, .jpg, .png"
                                />
                                <label for="attach">
                                    <img
                                        class="upload-icon"
                                        src="{{ asset('global/materials/upload.svg') }}"
                                        alt=""
                                    />
                                    <span>{{ __('Attach Image') }}</span>
                                </label>
                            </div>
                            <textarea class="form-control" rows="5" placeholder="Write Replay" name="message"></textarea>

                            <div class="buttons mt-4">
                                <button type="submit" class="btn btn-dark">
                                    {{ __('Submit') }}<i class="anticon anticon-double-right"></i>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
