@extends('backend.ticket.index')
@section('title')
    {{ __('Tickets Status') }}
@endsection
@section('header-btn')
@can('ticket-status-create')
    <a href="javascript:;" class="btn btn-sm btn-primary inline-flex items-center" type="button" data-bs-toggle="modal" data-bs-target="#statusModal">
        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
        Add Status
    </a>
    @endcan
@endsection
@section('ticket-content')
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($statuses as $status)
                                    <tr>
                                        <td class="table-td">
                                            <strong>{{ $status->name }}</strong>
                                        </td>
                                        <td class="table-td">
                                            @if($status->status_type == 'open')
                                                <span class="inline-block px-3 text-center py-1 rounded-full bg-opacity-25 text-success bg-success">
                                                    {{ __('Open') }}
                                                </span>
                                            @elseif($status->status_type == 'closed')
                                                <span class="inline-block px-3 text-center py-1 rounded-full bg-opacity-25 text-danger bg-danger">
                                                    {{ __('Closed') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="table-td">
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                @can('ticket-status-edit')
                                                <button class="action-btn" id="editStatus" data-id="{{ $status->id }}">
                                                    <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                </button>
                                                @endcan
                                                @can('ticket-status-delete')
                                                <button type="button" data-id="{{ $status->id }}" data-name="{{ $status->name }}" class="action-btn deleteTicketStatus">
                                                    <iconify-icon icon="lucide:trash"></iconify-icon>
                                                </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto">
                            <div>
                                @php
                                    $from = $statuses->firstItem(); // The starting item number on the current page
                                    $to = $statuses->lastItem(); // The ending item number on the current page
                                    $total = $statuses->total(); // The total number of items
                                @endphp

                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ $from }}</span>
                                    to
                                    <span class="font-medium">{{ $to }}</span>
                                    of
                                    <span class="font-medium">{{ $total }}</span>
                                    results
                                </p>
                            </div>
                            {{ $statuses->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add New Status -->
    @can('ticket-status-create')
    @include('backend.ticket.modal.__new_status')
    @endcan
    <!-- Modal for Update Status -->
    @can('ticket-status-edit')
    @include('backend.ticket.modal.__edit_status')
    @endcan
    <!-- Modal for Delete Status -->
    @can('ticket-status-delete')
    @include('backend.ticket.modal.__delete_status')
    @endcan
@endsection

@section('script')
    <script !src="">
        $(document).ready(function() {

            $('body').on('click', '#editStatus', function (event) {
                "use strict";
                event.preventDefault();
                $('#edit-status-body').empty();
                var id = $(this).data('id');

                $.get('statuses/' + id + '/edit', function (data) {

                    $('#editStatusModal').modal('show');
                    $('#edit-status-body').append(data);

                })
            })


            $('body').on('click', '.deleteTicketStatus', function (event) {
                "use strict";
                event.preventDefault();
                var id = $(this).data('id');
                var name = $(this).data('name');

                var url = '{{ route("admin.ticket.statuses.destroy", ":id") }}';
                url = url.replace(':id', id);
                $('#statusDeleteForm').attr('action', url)

                $('.name').html(name);
                $('#deleteTicketStatus').modal('show');
            });
        });
    </script>
@endsection
