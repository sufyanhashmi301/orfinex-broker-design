@extends('frontend::layouts.user')
@section('title')
    {{ __('Support Tickets') }}
@endsection
@section('content')
    <div class="flex justify-end flex-wrap items-center mb-5">
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('user.ticket.new') }}" class="btn btn-primary loaderBtn inline-flex items-center justify-center">
                {{ __('Create Ticket') }}
            </a>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <div class="card">
                <div class="card-body px-6 pb-6">
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead class=" border-t border-slate-100 dark:border-slate-800">
                                        <th scope="col" class="table-th">{{ __('Description') }}</th>
                                        <th scope="col" class="table-th">{{ __('Status') }}</th>
                                        <th scope="col" class="table-th">{{ __('Action') }}</th>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                        @foreach($tickets as $ticket)
                                        <tr>
                                            <td class="table-td">
                                                <div class="flex items-center">
                                                    <div class="flex-none">
                                                        <div class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                                            <iconify-icon icon="heroicons:flag"></iconify-icon>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 text-start">
                                                        <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                            {{ $ticket->title }}
                                                        </h4>
                                                        <div class="text-xs !text-nowrap font-normal text-slate-600 dark:text-slate-400">
                                                            {{ __('Created ').$ticket->created_at }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="table-td">
                                                <span class="block text-left">
                                                    <span class="inline-block text-center mx-auto py-1">
                                                        <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                            @if($ticket->isOpen())
                                                                <span class="h-[6px] w-[6px] bg-warning-500 rounded-full inline-block ring-4 ring-opacity-30 ring-warning-500"></span>
                                                                <span>{{ __('Opened') }}</span>
                                                            @elseif($ticket->isClosed())
                                                                <span class="h-[6px] w-[6px] bg-success-500 rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                                <span>{{ __('Completed') }}</span>
                                                            @endif
                                                        </span>
                                                    </span>
                                                </span>
                                            </td>
                                            <td class="table-td">
                                                <div class="flex space-x-3 rtl:space-x-reverse">
                                                    @if($ticket->isOpen())
                                                        <a href="{{ route('user.ticket.close.now',$ticket->uuid) }}" class="action-btn cancel"
                                                        data-bs-toggle="tooltip" title="Complete Ticket"
                                                        data-bs-original-title="Complete Ticket">
                                                            <iconify-icon icon="heroicons:check-16-solid"></iconify-icon>
                                                        </a>
                                                        <a href="{{ route('user.ticket.show',$ticket->uuid) }}" class="action-btn loaderBtn"
                                                        data-bs-toggle="tooltip" title="Show Ticket"
                                                        data-bs-original-title="Show Ticket">
                                                            <iconify-icon icon="heroicons:eye"></iconify-icon>
                                                        </a>
                                                    @elseif($ticket->isClosed())
                                                        <a href="#" class="action-btn cancel disabled">
                                                            <iconify-icon icon="heroicons:check-16-solid"></iconify-icon>
                                                        </a>
                                                        <a href="{{ route('user.ticket.show',$ticket->uuid) }}" class="action-btn loaderBtn"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Re-open the Ticket">
                                                            <iconify-icon icon="heroicons:book-open"></iconify-icon>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @if($tickets->isEmpty())
                                            <tr>
                                                <td class="table-td text-center" colspan="3">{{ __('No Data Found') }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
