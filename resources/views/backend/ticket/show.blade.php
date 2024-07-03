@extends('backend.layouts.app')
@section('title')
    {{ __('Ticket Details') }}
@endsection
@section('content')
    <div class="card overflow-hidden">
        <div class="card-header">
            <h4 class="card-title"> {{ $ticket->title.' - '.$ticket->uuid }}
                @if( $ticket->status == 'open')
                    <span class="site-badge pending">{{ __('Open') }}</span>
                @elseif($ticket->status == 'closed')
                    <span class="site-badge primary-bg ">{{ __('Completed') }}</span>
                @endif
            </h4>
            <div class="card-header-links">
                @if( $ticket->status == 'open')
                    <a href="{{ route('admin.ticket.close.now',$ticket->uuid) }}" class="btn btn-dark btn-sm inline-flex items-center">
                        {{ __('Close it') }}
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body p-6">
            <div class="block md:px-6 px-4 user">
                <div class="card ring-1 ring-slate-700 overflow-hidden md:w-2/3 w-3/4 mb-3">
                    <div class="bg-slate-100 dark:bg-slate-700 p-3">
                        <div class="flex items-center">
                            <div class="flex-none">
                                <div class="w-8 h-8 rounded-[100%] bg-[#EAE6FF] dark:bg-slate-900 text-[#5743BE] flex flex-col items-center justify-center font-normal capitalize ltr:mr-3 rtl:ml-3">
                                    @if( null != $ticket->user->avatar)
                                        <img class="block w-full h-full object-cover rounded-full" src="{{ asset($ticket->user->avatar)}}" alt="">
                                    @else
                                        {{ $ticket->user->first_name[0] }}{{ $ticket->user->last_name[0] }}
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 text-start">
                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                    {{ $ticket->user->first_name }} {{ $ticket->user->last_name }}
                                </h4>
                                <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                    {{ $ticket->user->email }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-6">
                        <div class="card-text message-body">
                            {!! $ticket->message !!}
                        </div>
                        <div class="message-attachments mt-4">
                            <h5 class="card-subtitle">{{ __('Attachments:') }}</h5>
                            <div class="single-attachment">
                                <div class="attach">
                                    <a href="{{ asset($ticket->attach) }}" class="flex items-center" target="_blank">
                                        <iconify-icon class="text-xl mr-1" icon="ant-design:picture-filled"></iconify-icon>
                                        {{ substr($ticket->attach,14) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach($ticket->messages as $message )
                <div class="block md:px-6 px-4">
                    <div class="card ring-1 ring-slate-700 overflow-hidden md:w-2/3 w-3/4 mb-3 support-ticket-single-message @if($message->model == 'admin') float-right @endif">
                        <div class="bg-slate-100 dark:bg-slate-700 p-3">
                            <div class="flex items-center">
                                <div class="flex-none">
                                    <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                        @if( $message->model != 'admin')
                                            <img class="block w-full h-full object-cover rounded-full" src="{{ asset($ticket->user->avatar ?? 'global/materials/user.png')}}" alt="">
                                        @else
                                            <img class="block w-full h-full object-cover rounded-full" src="{{ asset($message->user->avatar ?? 'global/materials/user.png' )}}" alt="">
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1 text-start">
                                    @if($message->model != 'admin')
                                    <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                        {{ $ticket->user->full_name }}
                                    </h4>
                                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                        {{ $ticket->user->email }}
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
                                <h5 class="card-subtitle">{{ __('Attachments:') }}</h5>
                                <div class="single-attachment">
                                    <div class="attach">
                                        <a href="{{ asset($message->attach) }}" class="flex items-center" target="_blank">
                                            <iconify-icon class="text-xl mr-1" icon="ant-design:picture-filled"></iconify-icon>
                                            {{ substr($message->attach,14) }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            

        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <div class="progress-steps-form">
                <form action="{{ route('admin.ticket.reply') }}" method="post"
                    enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <input type="hidden" name="uuid" value="{{ $ticket->uuid }}">
                    <div class="wrap-custom-file">
                        <input
                            type="file"
                            name="attach"
                            id="attach"
                            accept=".gif, .jpg, .png"
                        />
                        <label for="attach">
                            <img
                                class="upload-icon"
                                src="{{ asset('admin-assets/images/avatar/upload.svg') }}"
                                alt=""
                            />
                            <span>{{ __('Attach Image') }}</span>
                        </label>
                    </div>
                    <div class="input-area">
                        <textarea class="form-control" placeholder="Write Replay" name="message" rows="6"></textarea>
                    </div>
                    <div class="buttons text-right">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:send"></iconify-icon>
                            {{ __('Submit') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection