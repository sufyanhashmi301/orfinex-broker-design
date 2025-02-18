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
                                <div class="h-full">
                                    <div class="chat-content bg-white dark:bg-slate-800" style="height: calc(100% - 75px);">
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
        </div>
        <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
            <div class="profiel-wrap h-full p-6 pt-10 rounded-lg bg-white dark:bg-slate-800 lg:space-y-0 space-y-6 relative z-[1]">
                <div class="customer-profile-cover absolute left-0 top-0 h-[115px] w-full z-[-1] rounded-t-lg">
                    <p class="text-sm space-x-3 p-3">
                        <span class="font-medium text-slate-900">{{ __('Ticket ID:') }}</span>
                        <span class="text-slate-600">{{ $ticket->uuid }}</span>
                    </p>
                </div>
                <div class="profile-box flex flex-col h-full">
                    <div class="h-[100px] w-[100px] ml-auto mr-auto mb-4 rounded-full ring-4 ring-slate-100 relative bg-slate-300 dark:bg-slate-900 dark:text-white text-slate-900 flex flex-col items-center justify-center">
                        <img class="w-full h-full object-cover rounded-full" src="{{asset($ticket->user->avatar)}}" alt="{{$ticket->user->first_name}}">
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-medium text-slate-900 dark:text-slate-200 mb-[3px]">
                            {{ $ticket->user->first_name }} {{ $ticket->user->last_name }}
                        </div>
                        <div class="text-sm font-light text-slate-600 dark:text-slate-400">
                            {{ $ticket->user->rankAchieved() }}
                        </div>
                        <div class="text-sm font-light text-slate-600 dark:text-slate-400 my-5">
                            <span class="font-medium">{{ __('Member since:') }} </span>
                            {{ $ticket->user->created_at }}
                        </div>
                    </div>
                    <div class="flex justify-center space-x-3 rtl:space-x-reverse mb-5">
                        @can('customer-mail-send')
                            <span type="button" data-bs-toggle="modal" data-bs-target="#sendEmail">
                                <a href="javascript:void(0);" class="toolTip onTop action-btn"
                                   data-tippy-theme="dark" data-tippy-content="Send Email">
                                    <iconify-icon icon="lucide:mail"></iconify-icon>
                                </a>
                            </span>
                        @endcan
                        @can('customer-login')
                            <a href="{{ route('admin.user.login',$ticket->user->id) }}" target="_blank"
                               class="toolTip onTop action-btn" data-tippy-theme="dark" data-tippy-content="Login As User">
                                <iconify-icon icon="lucide:user-plus"></iconify-icon>
                            </a>
                        @endcan
                        @can('customer-balance-add-or-subtract')
                            <span data-bs-toggle="modal" data-bs-target="#addSubBal">
                                <a href="javascript:void(0);" type="button" class="toolTip onTop action-btn"
                                   data-tippy-theme="dark" data-tippy-content="Add Funds">
                                    <iconify-icon icon="lucide:wallet"></iconify-icon>
                                </a>
                            </span>
                        @endcan
                        {{--@can('Delete User')--}}
                        <span data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal">
                            <a href="javascript:void(0);" type="button" class="toolTip onTop action-btn"
                               data-tippy-theme="dark" data-tippy-content="Delete User">
                                <iconify-icon icon="lucide:user-minus"></iconify-icon>
                            </a>
                        </span>
                    </div>
                    <ul class="space-y-5 mb-4">

                        <li class="flex justify-between text-xs text-slate-600 dark:text-slate-300">
                            <span>Risk Profile</span>
                            <span class="flex items-center gap-2"></span>
                        </li>
                    </ul>
                    <div class="flex items-center justify-around border-t border-b border-slate-100 dark:border-slate-700 py-4 mb-5">
                        <div class="text-center">
                            <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                {{ __('Current Balance') }}
                            </div>
                            <div class="text-slate-900 dark:text-white text-xl font-medium">
                                $0.00
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                {{ __('Current Equity') }}
                            </div>
                            <div class="text-slate-900 dark:text-white text-xl font-medium">
                                $0.00
                            </div>
                        </div>
                    </div>
                    <ul class="space-y-5 mb-4">
                        <li class="flex justify-between items-center text-sm text-slate-600 dark:text-slate-300">
                            <span>{{ __('Status') }}</span>
                            <div class="bg-slate-50 dark:bg-slate-900 rounded px-3 py-2">
                                <select name="" class="bg-transparent outline-none">
                                    <option value="{{ $ticket->status }}" selected>{{ $ticket->status }}</option>
                                    <option value="open">open</option>
                                    <option value="on-hold">on-hold</option>
                                    <option value="closed">closed</option>
                                </select>
                            </div>
                        </li>
                        <li class="flex justify-between items-center text-sm text-slate-600 dark:text-slate-300">
                            <span>{{ __('Priority') }}</span>
                            <select name="" class="bg-transparent outline-none">
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </li>
                        <li class="flex justify-between items-center text-sm text-slate-600 dark:text-slate-300">
                            <span>{{ __('Department') }}</span>
                            <span>{{ __('Marketing') }}</span>
                        </li>
                        <li class="flex justify-between items-center text-sm text-slate-600 dark:text-slate-300">
                            <span>{{ __('Created On') }}</span>
                            <span>{{ $ticket->created_at }}</span>
                        </li>
                    </ul>
                    <div class="flex items-center justify-between gap-5 mt-auto">
                        @if( $ticket->status == 'open')
                            <a href="{{ route('admin.ticket.close.now',$ticket->uuid) }}" class="btn btn-dark inline-flex items-center justify-center w-full">
                                {{ __('Mark as Closed') }}
                            </a>
                        @endif
                        <a href="" class="btn btn-outline-dark inline-flex items-center justify-center w-full">
                            {{ __('Assign to') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Send Email -->
    @can('customer-mail-send')
        @include('backend.user.modals.__mail_send',['name' => $ticket->user->first_name.' '.$ticket->user->last_name, 'id' => $ticket->user->id])
    @endcan

    {{--    @can('delete-user')--}}
    @include('backend.user.modals.__delete_user',[ 'id' => $ticket->user->id])

@endsection
