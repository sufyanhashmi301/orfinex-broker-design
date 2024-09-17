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
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Ticket Priority Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($priorities as $priority)
                                <tr>
                                    <td class="table-td">
                                        <strong>{{ $priority->id }}</strong>
                                    </td>
                                    <td class="table-td">
                                        <span class="flex space-x-2 rtl:space-x-reverse items-center">
                                            <span class="inline-flex h-[10px] w-[10px] rounded-full" style="background-color: {{ $priority->color }}"></span>
                                            <span>{{ $priority->name }}</span>
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <button class="action-btn" id="editPriority" data-id="{{ $priority->id }}">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </button>
                                            <button type="button" data-id="{{ $priority->id }}" data-name="{{ $priority->name }}" class="action-btn deleteTicketPriority">
                                                <iconify-icon icon="lucide:trash"></iconify-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto">
                            <div>
                                @php
                                    $from = $priorities->firstItem(); // The starting item number on the current page
                                    $to = $priorities->lastItem(); // The ending item number on the current page
                                    $total = $priorities->total(); // The total number of items
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
                            {{ $priorities->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add New Status -->
    @include('backend.ticket.modal.__new_priority')

    <!-- Modal for Update Status -->
    @include('backend.ticket.modal.__edit_priority')

    <!-- Modal for Delete Status -->
    @include('backend.ticket.modal.__delete_priority')

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Function to synchronize text and color inputs in the same group
            function syncGroupInputs(group) {
                var $textInput = $(group).find('.text-input');
                var $colorInput = $(group).find('.color-input');

                $textInput.on('input', function() {
                    var colorValue = $(this).val();
                    if (isValidColor(colorValue)) {
                        $colorInput.val(colorValue).css('background-color', colorValue);
                    }
                });

                $colorInput.on('input', function() {
                    var colorValue = $(this).val();
                    $textInput.val(colorValue);
                });
            }

            // Function to validate color input
            function isValidColor(value) {
                var s = new Option().style;
                s.color = value;
                return s.color !== '';
            }

            // Initialize synchronization for each input group
            $('.color-input-group').each(function() {
                syncGroupInputs(this);
            });
        });

        $(document).ready(function() {

            $('body').on('click', '#editPriority', function (event) {
                "use strict";
                event.preventDefault();
                $('#edit-priority-body').empty();
                var id = $(this).data('id');

                $.get('priorities/' + id + '/edit', function (data) {

                    $('#editPriorityModal').modal('show');
                    $('#edit-priority-body').append(data);

                })
            })

            $('body').on('click', '.deleteTicketPriority', function (event) {
                "use strict";
                event.preventDefault();
                var id = $(this).data('id');
                var name = $(this).data('name');

                var url = '{{ route("admin.ticket.priorities.destroy", ":id") }}';
                url = url.replace(':id', id);
                $('#priorityDeleteForm').attr('action', url)

                $('.name').html(name);
                $('#deleteTicketPriority').modal('show');
            });
        });
    </script>
@endsection
