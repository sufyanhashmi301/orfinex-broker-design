@extends('backend.ticket.index')
@section('title')
    {{ __('Tickets Status') }}
@endsection
@section('header-btn')
    <a href="javascript:;" class="btn btn-primary inline-flex items-center" type="button" data-bs-toggle="modal" data-bs-target="#statusModal">
        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
        Add Status
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
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-td">
                                        <strong>Open</strong>
                                    </td>
                                    <td class="table-td">
                                        <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                            Active
                                        </div>
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <button class="action-btn" type="button" data-bs-toggle="modal" data-bs-target="#editStatusModal">
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
    @include('backend.ticket.modal.__new_status')

    <!-- Modal for Update Status -->
    @include('backend.ticket.modal.__edit_status')

@endsection

@section('script')

@endsection
