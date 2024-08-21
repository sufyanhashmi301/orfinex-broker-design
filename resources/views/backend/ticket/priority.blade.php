@extends('backend.ticket.index')
@section('title')
    {{ __('Tickets Priority') }}
@endsection
@section('header-btn')
    <a href="javascript:;" class="btn btn-primary inline-flex items-center" type="button" data-bs-toggle="modal" data-bs-target="#priorityModal">
        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
        {{ __('Add Priority') }}
    </a>
@endsection
@section('ticket-content')
    <div class="card">
        <div class="card-body p-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Ticket Priority Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-td">
                                        <strong>600</strong>
                                    </td>
                                    <td class="table-td">
                                        <span class="flex space-x-2 rtl:space-x-reverse items-center">
                                            <span class="inline-flex h-[10px] w-[10px] bg-success-500 rounded-full"></span>
                                            <span>Low</span>
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <button class="action-btn" type="button" data-bs-toggle="modal" data-bs-target="#editPriorityModal">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </button>
                                            <button class="action-btn" type="button">
                                                <iconify-icon icon="lucide:trash"></iconify-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">
                                        <strong>561</strong>
                                    </td>
                                    <td class="table-td">
                                        <span class="flex space-x-2 rtl:space-x-reverse items-center">
                                            <span class="inline-flex h-[10px] w-[10px] bg-danger-500 rounded-full"></span>
                                            <span>High</span>
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <button class="action-btn" type="button" data-bs-toggle="modal" data-bs-target="#editPriorityModal">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </button>
                                            <button class="action-btn" type="button">
                                                <iconify-icon icon="lucide:trash"></iconify-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add New Status -->
    @include('backend.ticket.modal.__new_priority')

    <!-- Modal for Update Status -->
    @include('backend.ticket.modal.__edit_priority')

@endsection

@section('script')

@endsection
