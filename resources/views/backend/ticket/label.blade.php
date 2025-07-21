@extends('backend.ticket.index')
@section('title')
    {{ __('Ticket Types') }}
@endsection
@can('ticket-type-create')
@section('header-btn')
    <a href="javascript:;" class="btn btn-sm btn-primary inline-flex items-center" type="button" data-bs-toggle="modal" data-bs-target="#labelModal">
        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
        {{ __('Add Type') }}
    </a>
@endsection
@endcan
@section('ticket-content')
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Ticket Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($labels as $label)
                                    <tr>
                                        <td class="table-td">
                                            {{ $label->id }}
                                        </td>
                                        <td class="table-td">
                                            <span class="font-semibold">{{ $label->name }}</span>
                                        </td>
                                        <td class="table-td">
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                @can('ticket-type-edit')
                                                <button class="action-btn" id="editLabel" data-id="{{ $label->id }}">
                                                    <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                </button>
                                                @endcan
                                                @can('ticket-type-delete')
                                                <button type="button" data-id="{{ $label->id }}" data-name="{{ $label->name }}" class="action-btn deleteTicketLabel">
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
                                    $from = $labels->firstItem(); // The starting item number on the current page
                                    $to = $labels->lastItem(); // The ending item number on the current page
                                    $total = $labels->total(); // The total number of items
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
                            {{ $labels->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add New Status -->
    @include('backend.ticket.modal.__new_label')

    <!-- Modal for Update Status -->
    @include('backend.ticket.modal.__edit_label')

    <!-- Modal for Delete Status -->
    @include('backend.ticket.modal.__delete_label')

@endsection

@section('script')
    <script !src="">
        $(document).ready(function() {

            $('body').on('click', '#editLabel', function (event) {
                "use strict";
                event.preventDefault();
                $('#edit-label-body').empty();
                var id = $(this).data('id');

                $.get('label/' + id + '/edit', function (data) {

                    $('#editLabelModal').modal('show');
                    $('#edit-label-body').append(data);
                    tippy(".shift-Away", {
                        placement: "top",
                        animation: "shift-away"
                    });

                })
            })


            $('body').on('click', '.deleteTicketLabel', function (event) {
                "use strict";
                event.preventDefault();
                var id = $(this).data('id');
                var name = $(this).data('name');

                var url = '{{ route("admin.ticket.label.destroy", ":id") }}';
                url = url.replace(':id', id);
                $('#labelDeleteForm').attr('action', url)

                $('.name').html(name);
                $('#deleteTicketLabel').modal('show');
            });
        });
    </script>
@endsection
