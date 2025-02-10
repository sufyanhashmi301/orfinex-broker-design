@extends('frontend::layouts.user')
@section('title')
    {{ __('Add New Support Ticket') }}
@endsection
@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
            <div class="card h-full">
                <div class="card-body p-6">
                    <div class="space-y-5">
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">{{ __('Tell Us!') }}</p>
                            <p class="text-sm dark:text-slate-100">
                                {{ __('Please provide as much detail as possible when submitting your support ticket. Clear and complete information helps us assist you more efficiently.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">{{ __('Show Us!') }}</p>
                            <p class="text-sm dark:text-slate-100">
                                {{ __('If you encounter any issues, attach relevant images or screenshots to your ticket. Visual references help us understand your concern better and resolve it faster.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">{{ __('Caution') }}</p>
                            <p class="text-sm dark:text-slate-100">
                                {{ __('Please note that the response time for tickets may extend up to 2 business days based on the nature of the inquiry.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">{{ __('Response Time') }}</p>
                            <p class="text-sm dark:text-slate-100">
                                {{ __('Our support team operates during standard business hours, Monday through Friday. We aim to resolve all inquiries promptly. However, response times may be affected during weekends or public holidays.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-danger600 font-medium mb-2">{{ __('Important Notice') }}</p>
                            <p class="text-sm dark:text-slate-100">
                                {{ __('Tickets that remain inactive for a specified period or are unrelated to support inquiries may be closed. To ensure prompt resolution, please avoid submitting duplicate tickets. We appreciate your understanding and cooperation in providing seamless support.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
            <div class="flex chat-height overflow-hidden relative">
                <div class="parent flex h-full w-full">
                    <!-- end main message body -->
                    <div class="flex-1">
                        <div class="h-full card">
                            <div class="p-0 h-full body-class">
                                <!-- BEGIN: Messages -->
                                <div class="relative h-full">
                                    <header class="border-b border-slate-100 dark:border-slate-700">
                                        <div class="flex py-6 md:px-6 px-3 items-center">
                                            <div class="flex-1">
                                                <div class="flex space-x-3 rtl:space-x-reverse">
                                                    <span class="text-slate-900 dark:text-white cursor-pointer text-xl self-center ltr:mr-3 rtl:ml-3 lg:hidden block start-chat">
                                                        <iconify-icon icon="heroicons-outline:menu-alt-1"></iconify-icon>
                                                    </span>
                                                    <div class="flex-1 text-start">
                                                        <span class="text-slate-800 dark:text-slate-300 text-lg font-medium mb-[2px] truncate mr-2">
                                                            {{ $ticket->title.' - '.$ticket->uuid }}
                                                        </span>
                                                        <span class="badge badge-primary">
                                                            {{ $ticket->status}}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            @if( $ticket->status != 'closed')
                                                <div class="flex-none flex md:space-x-3 space-x-1 items-center rtl:space-x-reverse">
                                                    <a href="{{ route('user.ticket.close.now',$ticket->uuid) }}"
                                                       class="btn btn-dark btn-sm loaderBtn inline-flex items-center">
                                                        {{ __('Mark it close') }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </header>
                                    <div class="chat-content bg-white dark:bg-dark" style="height: calc(100% - 155px);">
                                        <div class="msgs overflow-y-auto msg-height pt-6 space-y-6">
                                            <div class="block md:px-6 px-4">
                                                <div class="flex space-x-2 items-end justify-end rtl:space-x-reverse">
                                                    <div class="flex space-x-4 rtl:space-x-reverse">
                                                        <div class="text-right">
                                                            <span class="font-normal text-xs text-slate-400 dark:text-slate-400 mb-1">
                                                                {{ $ticket->user->first_name }} {{ $ticket->user->last_name }}
                                                            </span>
                                                            <div class="text-contrent p-3 bg-slate-100 dark:bg-slate-600 dark:text-slate-300 text-slate-600 text-sm font-normal rounded-md">
                                                                {!! $ticket->message !!}
                                                                @if($ticket->attach)
                                                                    <div class="mt-1">
                                                                        <a href="{{ asset($ticket->attach) }}" class="inline-flex p-2 rounded-[6px] bg-[#EAE5FF] dark:bg-slate-900" target="_blank">
                                                                            <img src="{{ asset($ticket->attach) }}" class="block h-[70px] w-[70px]" style="object-fit: scale-down;" alt="">
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-none">
                                                        <div class="h-10 w-10 rounded-full">
                                                            <img class="block w-full h-full object-cover rounded-full" src="{{ asset($ticket->user->avatar ?? 'global/materials/user.png')}}" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @foreach($ticket->messages as $message )
                                                <div class="block md:px-6 px-4">
                                                    <div class="flex space-x-2 items-end rtl:space-x-reverse @if( $message->model != 'admin') justify-end w-full @endif">
                                                        @if( $message->model == 'admin')
                                                            {{--                                                                {{dd($ticket->user)}}--}}
                                                            <div class="flex-none">
                                                                <div class="h-10 w-10 rounded-full">
                                                                    <img class="block w-full h-full object-cover rounded-full" src="{{ asset($message->user->avatar ?? 'global/materials/user.png')}}" alt="">
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="@if( $message->model != 'admin') no @else flex-1 @endif flex space-x-4 rtl:space-x-reverse">
                                                            <div class="@if( $message->model != 'admin') text-right @endif">
                                                                <span class="font-normal text-xs text-slate-400 dark:text-slate-400 mb-1">
                                                                    @if( $message->model != 'admin')
                                                                        {{ $user->full_name }}
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
                                                        @if( $message->model != 'admin')
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
                                    <div id="image-container" class="hidden absolute bg-white border-t border-slate-100 dark:border-slate-700 flex items-center gap-3 p-2">
                                        <div class="relative rounded border border-slate-100 dark:border-slate-700 p-2">
                                            <img id="image-preview" src="" alt="Image Preview" class="h-16 object-cover rounded-md">
                                            <button type="button" id="remove-image" class="h-6 w-6 flex flex-col justify-center items-center text-lg rounded-full absolute top-1 right-1 bg-red-500 text-white">
                                                <iconify-icon icon="mdi:window-close"></iconify-icon>
                                            </button>
                                        </div>
                                    </div>
                                    <footer class="relative md:px-6 px-4 border-t md:pt-6 pt-4 border-slate-100 dark:border-slate-700">
                                        <form action="{{ route('user.ticket.reply') }}" method="post" enctype="multipart/form-data" class="sm:flex md:space-x-4 sm:space-x-2 rtl:space-x-reverse">
                                            @csrf
                                            <input type="hidden" name="uuid" value="{{ $ticket->uuid }}">
                                            <input type="file" name="attach" id="attach" class="hidden" accept=".gif, .jpg, .png"/>
                                            <div class="flex-none sm:flex hidden md:space-x-3 space-x-1 rtl:space-x-reverse">
                                                <label class="h-8 w-8 cursor-pointer bg-slate-100 dark:bg-slate-900 dark:text-slate-400 flex flex-col justify-center items-center text-xl rounded-full" for="attach">
                                                    <iconify-icon icon="heroicons-outline:link"></iconify-icon>
                                                </label>
                                            </div>
                                            <div class="flex-1 relative flex space-x-3 rtl:space-x-reverse">
                                                <div class="flex-1">
                                                    <textarea placeholder="{{ __('Type your message...') }}" class="focus:ring-0 focus:outline-0 block w-full bg-transparent dark:text-white resize-none" name="message"></textarea>
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
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <style>
        #image-container{
            position: absolute;
            bottom: 4.5rem;
            width: 98%;
        }
    </style>
@endsection
@section('script')
    <script !src="">
        $(document).ready(function() {
            // Show image preview when file is selected
            $('#attach').change(function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-preview').attr('src', e.target.result);
                        $('#image-container').removeClass('hidden'); // Show the image container
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Remove image and hide container
            $('#remove-image').click(function() {
                $('#image-preview').attr('src', ''); // Clear the image preview
                $('#image-container').addClass('hidden'); // Hide the image container
                $('#attach').val(''); // Clear file input
            });
        });
    </script>
@endsection
