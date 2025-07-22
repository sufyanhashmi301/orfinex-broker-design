@extends('backend.layouts.app')
@section('title')
    {{ __('Levels') }}
@endsection
@section('content')

    <!-- Title and Buttons Section -->
    <div class="pageTitle flex justify-between items-center mb-6">
        <h1 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Levels') }}
        </h1>
        @can('multi-ib-level-create')
        <div>
            <a href="javascript:;" class="btn btn-sm btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#levelModal">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                {{ __('Add New') }}
            </a>
        </div>
        @endcan
    </div>
    <!-- /Title and Buttons Section -->

    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700">
                            <thead>
                            <tr>
                                <th class="table-th">{{ __('Title') }}</th>
                                <th class="table-th">{{ __('Level Order') }}</th>
                                <th class="table-th">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($levels as $level)
                                <tr>
                                    <td class="table-td">
                                        <strong>{{ $level->title }}</strong>
                                    </td>
                                    <td class="table-td">
                                        {{ $level->level_order }}
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            @can('multi-ib-level-edit')
                                            <button type="button" class="action-btn edit-level" data-bs-toggle="modal" data-bs-target="#editLevelModal" data-id="{{ $level->id }}">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </button>
                                            @endcan
                                            @can('multi-ib-level-delete')
                                            <button type="button" class="action-btn delete-level" data-bs-toggle="modal" data-bs-target="#deleteLevelModal" data-id="{{ $level->id }}" data-title="{{ $level->title }}">
                                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                            </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="flex justify-between items-center border-t border-slate-100 dark:border-slate-700 px-4 py-5 mt-auto">
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium">{{ $levels->firstItem() }}</span> to <span class="font-medium">{{ $levels->lastItem() }}</span> of <span class="font-medium">{{ $levels->total() }}</span> results
                            </p>
                            {{ $levels->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('multi-ib-level-create')
    <!-- Modals -->
    @include('backend.multi_ib_level.modal.__create_level')
    @endcan
    @can('multi-ib-level-edit')
    @include('backend.multi_ib_level.modal.__edit_level')
    @endcan
    @can('multi-ib-level-delete')
    @include('backend.multi_ib_level.modal.__delete_level')
    @endcan

@endsection

@section('script')
    <script>
        $('body').on('click', '.edit-level', function (event) { // Use the class selector here
            event.preventDefault();
            $('#edit-level-body').empty();
            var id = $(this).data('id'); // Get the ID from the clicked button

            var url = '{{ route("admin.multi-ib-level.edit", ":id") }}'; // Use the correct route name
            url = url.replace(':id', id);

            $.get(url, function (data) {
                $('#edit-level-body').append(data);
                $('#editLevelModal').modal('show'); // Correct modal ID
                tippy(".shift-Away", {
                    placement: "top",
                    animation: "shift-away"
                });
            }).fail(function () {
                alert('Error loading the edit form.'); // Error handling
            });
        });
        $('.delete-level').on('click', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.multi-ib-level.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#levelDeleteForm').attr('action', url)

            $('.name').html(name);
            $('#deleteLevelModal').modal('show');
        });
    </script>
@endsection
