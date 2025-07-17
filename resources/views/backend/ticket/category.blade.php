@extends('backend.ticket.index')
@section('title')
    {{ __('Ticket Categories') }}
@endsection
@can('ticket-category-create')
@section('header-btn')
    <a href="javascript:;" class="btn btn-sm btn-primary inline-flex items-center" type="button" data-bs-toggle="modal" data-bs-target="#categoryModal">
        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
        {{ __('Add Category') }}
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
                                    <th scope="col" class="table-th">{{ __('Ticket Category') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="table-td">
                                        {{ $category->id }}
                                    </td>
                                    <td class="table-td">
                                        <span class="font-semibold">{{ $category->name }}</span>
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            @can('ticket-category-edit')
                                            <button class="action-btn" id="editCategory" data-id="{{ $category->id }}">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </button>
                                            @endcan
                                            @can('ticket-category-delete')
                                            <button type="button" data-id="{{ $category->id }}" data-name="{{ $category->name }}" class="action-btn deleteTicketCategory">
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
                                    $from = $categories->firstItem(); // The starting item number on the current page
                                    $to = $categories->lastItem(); // The ending item number on the current page
                                    $total = $categories->total(); // The total number of items
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
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add New Category -->
    @include('backend.ticket.modal.__new_category')

    <!-- Modal for Update Category -->
    @include('backend.ticket.modal.__edit_category')

    <!-- Modal for Delete Category -->
    @include('backend.ticket.modal.__delete_category')

@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('body').on('click', '#editCategory', function (event) {
                "use strict";
                event.preventDefault();
                $('#edit-category-body').empty();
                var id = $(this).data('id');

                $.get('category/' + id + '/edit', function (data) {

                    $('#editCategoryModal').modal('show');
                    $('#edit-category-body').append(data);
                    tippy(".shift-Away", {
                        placement: "top",
                        animation: "shift-away"
                    });

                })
            })

            $('body').on('click', '.deleteTicketCategory', function (event) {
                "use strict";
                event.preventDefault();
                var id = $(this).data('id');
                var name = $(this).data('name');

                var url = '{{ route("admin.ticket.category.destroy", ":id") }}';
                url = url.replace(':id', id);
                $('#categoryDeleteForm').attr('action', url)

                $('.name').html(name);
                $('#deleteTicketCategory').modal('show');
            });
        });
    </script>
@endsection
