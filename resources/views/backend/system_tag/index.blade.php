@extends('backend.setting.customer.index')

@section('title')
    {{ __('System Tag') }}
@endsection

@section('title-btns')
@can('system-tag-create')
    <a href="javascript:;" class="btn btn-sm btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#systemTagModal">
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
        {{ __('Add New') }}
    </a>
@endcan
@endsection

@section('customer-content')
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Tag Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($systemTags as $systemTag)
                                <tr>
                                    <td class="table-td">
                                        <strong>{{ $systemTag->name }}</strong>
                                    </td>
                                    <td class="table-td">
                                        @if($systemTag->status)
                                            <div class="badge badge-success capitalize">{{ __('Active') }}</div>
                                        @else
                                            <div class="badge badge-danger capitalize">{{ __('Disabled') }}</div>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            @can('system-tag-edit')
                                            <button type="button" class="action-btn edit-system-tag" data-id="{{ $systemTag->id }}">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </button>
                                            @endcan

                                            @can('system-tag-delete')
                                            <button type="button" data-id="{{ $systemTag->id }}" data-name="{{ $systemTag->name }}" class="action-btn deletesystemTag">
                                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
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
                                    $from = $systemTags->firstItem();
                                    $to = $systemTags->lastItem();
                                    $total = $systemTags->total();
                                @endphp
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ $from }}</span> to <span class="font-medium">{{ $to }}</span> of <span class="font-medium">{{ $total }}</span> results
                                </p>
                            </div>
                            {{ $systemTags->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Create System Tag -->
    @can('system-tag-create')
    @include('backend.system_tag.modal.__create_tag')
    @endcan
    <!-- Modal for Edit System Tag -->
    @can('system-tag-edit')
    @include('backend.system_tag.modal.__edit_tag')
    @endcan
    <!-- Modal for Delete System Tag -->
    @can('system-tag-delete')
    @include('backend.system_tag.modal.__delete_tag')
    @endcan
@endsection

@section('user-management-script')
    <script>
        // Edit system tag
       // Edit system tag
        $('body').on('click', '.edit-system-tag', function (event) { // Use the class selector here
            event.preventDefault();
            $('#edit-tag-body').empty();
            var id = $(this).data('id'); // Get the ID from the clicked button

            var url = '{{ route("admin.system-tag.edit", ":id") }}'; // Use the correct route name
            url = url.replace(':id', id);

            $.get(url, function (data) {
                $('#edit-tag-body').append(data);
                $('#editSystemTagModal').modal('show'); // Correct modal ID
                tippy(".shift-Away", {
                    placement: "top",
                    animation: "shift-away"
                });
            }).fail(function () {
                alert('Error loading the edit form.'); // Error handling
            });
        });

        // Delete system tag
        $('.deletesystemTag').on('click', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.system-tag.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#systemTagDeleteForm').attr('action', url)

            $('.name').html(name);
            $('#deleteSystemTag').modal('show');
        });
    </script>
@endsection
