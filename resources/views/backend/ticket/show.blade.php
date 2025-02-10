@extends('backend.layouts.app')
@section('title')
    {{ __('Ticket Details') }}
@endsection
@section('content')
    <div class="grid grid-cols-12 gap-6">
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
                                                    <div class="flex-none">
                                                        <div class="h-10 w-10 rounded-full relative">
                                                            <img src="{{ asset($ticket->user->avatar ?? 'global/materials/user.png')}}" alt="" class="w-full h-full object-cover rounded-full">
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 text-start">
                                                        <span class="block text-slate-800 dark:text-slate-300 text-sm font-medium mb-[2px] truncate">
                                                            {{ $ticket->user->full_name }}
                                                        </span>
                                                        <span class="block text-slate-500 dark:text-slate-300 text-xs font-normal">
                                                            {{ $ticket->user->email }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="javascript:;" class="ticketDetail-open-btn lg:hidden items-center justify-center dark:text-white p-1">
                                                <iconify-icon class="text-lg font-medium" icon="mdi:dots-vertical"></iconify-icon>
                                            </a>
                                        </div>
                                    </header>
                                    <div class="chat-content parent-height bg-white dark:bg-dark">
                                        <div class="msgs overflow-y-auto msg-height pt-6 space-y-6">
                                            <div class="block md:px-6 px-4">
                                                <div class="flex space-x-2 items-end rtl:space-x-reverse">
                                                    <div class="flex-none">
                                                        @if( null != $ticket->user->avatar)
                                                            <div class="h-10 w-10 rounded-full">
                                                                <img class="block w-full h-full object-cover rounded-full" src="{{ asset($ticket->user->avatar ?? 'global/materials/user.png') }}" alt="">
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
                                                </div>
                                            </div>
                                            @foreach($ticket->messages as $message)
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
{{--                                                                {{dd($ticket->user)}}--}}
                                                            <div class="flex-none">
                                                                <div class="h-10 w-10 rounded-full">
                                                                    <img class="block w-full h-full object-cover rounded-full" src="{{ asset($message->user->avatar ?? 'global/materials/user.png')}}" alt="">
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
                                        <form action="{{ route('admin.ticket.reply') }}" method="post" enctype="multipart/form-data" class="sm:flex md:space-x-4 sm:space-x-2 rtl:space-x-reverse">
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
                                                    <textarea name="message" placeholder="Type your message..." class="focus:ring-0 focus:outline-0 block w-full bg-transparent dark:text-white resize-none"></textarea>
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

                                <!-- END: Message -->
                            </div>
                        </div>
                    </div>
                    <!-- right info bar -->

                </div>
            </div>
        </div>
        <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
            <div class="mobile-close-overlay w-full h-full" id="close-settings-overlay"></div>
            <div class="h-full" id="ticket-details__container">
                <a href="javascript:;" class="ticketDetail-close-btn btn-primary absolute items-center justify-center p-2">
                    <iconify-icon class="text-lg font-medium" icon="material-symbols:close-rounded"></iconify-icon>
                </a>
                <div class="profile-wrap h-full p-6 pt-10 rounded-lg bg-white dark:bg-dark relative z-[1]">
                    <div class="customer-profile-cover absolute left-0 top-0 h-[115px] w-full z-[-1] rounded-t-lg mb-6">
                        <p class="text-sm space-x-3 p-3">
                            <span class="font-medium text-slate-900">{{ __('Ticket ID:') }}</span>
                            <span class="text-slate-600">{{ $ticket->uuid }}</span>
                        </p>
                    </div>
                    <div class="profile-box flex flex-col h-full">
                        <div class="h-[100px] w-[100px] ml-auto mr-auto mb-4 rounded-full ring-4 ring-slate-100 relative bg-slate-300 dark:bg-slate-900 dark:text-white text-slate-900 flex flex-col items-center justify-center">
                            <img class="w-full h-full object-cover rounded-full" src="{{ asset($ticket->user->avatar ?? 'global/materials/user.png') }}" alt="{{$ticket->user->first_name}}">
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-medium text-slate-900 dark:text-slate-200 mb-[3px]">
                                {{ $ticket->user->first_name }} {{ $ticket->user->last_name }}
                            </div>
                            <div class="text-sm font-light text-slate-600 dark:text-slate-400 my-5">
                                <span class="font-medium">{{ __('Member since:') }} </span>
                                {{ $ticket->user->created_at }}
                            </div>
                        </div>
                        <div class="flex justify-center space-x-3 rtl:space-x-reverse mb-5">
                            @can('customer-mail-send')
                                <span type="button" data-bs-toggle="modal" data-bs-target="#sendEmail">
                                    <a href="javascript:void(0);" class="toolTip onTop action-btn dark:text-slate-300"
                                       data-tippy-theme="dark" data-tippy-content="Send Email">
                                        <iconify-icon icon="lucide:mail"></iconify-icon>
                                    </a>
                                </span>
                            @endcan
                            @can('customer-login')
                                <a href="{{ route('admin.user.login',$ticket->user->id) }}" target="_blank"
                                   class="toolTip onTop action-btn dark:text-slate-300" data-tippy-theme="dark" data-tippy-content="Login As User">
                                    <iconify-icon icon="lucide:user-plus"></iconify-icon>
                                </a>
                            @endcan
                            @can('customer-balance-add-or-subtract')
                                <span data-bs-toggle="modal" data-bs-target="#addSubBal">
                                    <a href="javascript:void(0);" type="button" class="toolTip onTop action-btn dark:text-slate-300"
                                       data-tippy-theme="dark" data-tippy-content="Add Funds">
                                        <iconify-icon icon="lucide:wallet"></iconify-icon>
                                    </a>
                                </span>
                            @endcan
                            {{--@can('Delete User')--}}
                            <span data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal">
                                <a href="javascript:void(0);" type="button" class="toolTip onTop action-btn dark:text-slate-300"
                                   data-tippy-theme="dark" data-tippy-content="Delete User">
                                    <iconify-icon icon="lucide:user-minus"></iconify-icon>
                                </a>
                            </span>
                        </div>
                        <div class="flex items-center justify-around border-t border-slate-100 dark:border-slate-700 pt-4">
                            <form action="{{ route('admin.ticket.update', $ticket) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <div class="space-y-4">
                                    <div class="input-area">
                                        <label for="" class="form-label">{{ __('status') }}</label>
                                        <select name="status" class="select2 form-control">
                                            @foreach(\Coderflex\LaravelTicket\Enums\Status::cases() as $status)
                                                <option value="{{ $status->value }}" @selected(old('status', $ticket->status) == $status->value)>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-area">
                                        <label for="" class="form-label">{{ __('Priority') }}</label>
                                        <select name="priority" class="select2 form-control">
                                            @foreach(\Coderflex\LaravelTicket\Enums\Priority::cases() as $priority)
                                                <option value="{{ $priority->value }}" @selected(old('priority', $ticket->priority) == $priority->value)>
                                                    {{ $priority->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-area">
                                        <label for="" class="form-label">{{ __('Ticket Type') }}</label>
                                        <select name="label" class="select2 form-control">
                                            @foreach($labels as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-area">
                                        <label for="" class="form-label">{{ __('Agent') }}</label>
                                        <select name="assigned_to" class="select2 form-control" data-placeholder="Select Agent">
                                            <option value="">{{ __('Select Agent') }}</option>
                                            @foreach($staff as $id => $name)
                                                <option value="{{ $id }}" @selected(old('assigned_to', $ticket->assigned_to) == $id)>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-10">
                                    <button type="submit" class="btn btn-outline-dark inline-flex items-center justify-center w-full">
                                        {{ __('Update Ticket') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Send Email -->
    @can('customer-mail-send')
        @include('backend.user.include.__mail_send',['name' => $ticket->user->first_name.' '.$ticket->user->last_name, 'id' => $ticket->user->id])
    @endcan

    {{-- Modal for assign ticket--}}
    @include('backend.ticket.modal.__assign_ticket')

    {{--    @can('delete-user')--}}
    @include('backend.user.include.__delete_user',[ 'id' => $ticket->user->id])

@endsection
@section('style')
    <style>
        #image-container{
            position: absolute;
            bottom: 4.5rem;
            width: 100%;
        }
    </style>
@endsection
@section('script')
    <script !src="">
        $('.ticketDetail-open-btn').click(function(){
            $('#ticket-details__container, .mobile-close-overlay').addClass('in');
        });

        $('.ticketDetail-close-btn').click(function(){
            $('#ticket-details__container, .mobile-close-overlay').removeClass('in');
        })

        $(document).ready(function() {
            $('#attach').change(function(e) {
                var file = e.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function() {
                        $('#image-preview').attr('src', reader.result);
                        $('#image-container').removeClass('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('#remove-image').click(function() {
                $('#image-container').addClass('hidden');
                $('#attach').val('');
                $('#image-preview').attr('src', '');
            });
        });

        $('body').on('click', '#assignTicket', function (event) {
            "use strict";
            event.preventDefault();
            $('#assign-ticket-body').empty();
            var id = $(this).data('id');

            var url = '{{ route("admin.ticket.showAssignModal", ":id") }}';
            url = url.replace(':id', id);

            $.get(url , function (data) {

                $('#assignTicketModal').modal('show');
                $('#assign-ticket-body').append(data);
            });

        });

    </script>
@endsection
