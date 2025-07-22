@extends('backend.setting.customer.index')

@section('title')
    {{ __('IB Group') }}
@endsection
@can('ib-group-create')
@section('title-btns')
    <a href="javascript:;" class="btn btn-sm btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#ibGroupModal">
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
        {{ __('Add New') }}
    </a>
@endsection
@endcan
@section('customer-content')
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Group Name') }}</th>
{{--                                <th scope="col" class="table-th">{{ __('Schema') }}</th>--}}
                                <th scope="col" class="table-th">{{ __('Rebate Rules') }}</th>
                                <th scope="col" class="table-th">{{ __('Global Account Type') }}</th>
                                <th scope="col" class="table-th">{{ __('Status') }}</th>
                                <th scope="col" class="table-th">{{ __('Action') }}</th>

                            </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            @foreach($ibGroups as $ibGroup)
                                <tr>
                                    <td class="table-td">
                                        <strong>{{ $ibGroup->name }}</strong>
                                    </td>

{{--                                    <td class="table-td">--}}
{{--                                        @if($ibGroup->forexSchemas->isNotEmpty())--}}
{{--                                            <ul>--}}
{{--                                                @foreach($ibGroup->forexSchemas as $schema)--}}
{{--                                                    <li>{{ $schema->title }}</li>--}}
{{--                                                @endforeach--}}
{{--                                            </ul>--}}
{{--                                        @else--}}
{{--                                            <span>{{ __('N/A') }}</span>--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
                                    <td class="table-td">
                                        @if($ibGroup->rebateRules->isNotEmpty())
                                            <ul>
                                                @foreach($ibGroup->rebateRules as $rule)
                                                    <li>{{ $rule->title }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span>{{ __('N/A') }}</span>
                                        @endif
                                    </td>
                                     <td class="table-td">
                                        @if($ibGroup->is_global_account)
                                            <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">{{ __('Active') }}</div>
                                        @else
                                            <div class="badge bg-warning-500 text-warning-500 bg-opacity-30 capitalize">{{ __('Disabled') }}</div>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        @if($ibGroup->status)
                                            <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">{{ __('Active') }}</div>
                                        @else
                                            <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">{{ __('Disabled') }}</div>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <button type="button" class="action-btn edit-ib-group" data-id="{{ $ibGroup->id }}">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </button>
                                            <button type="button" data-id="{{ $ibGroup->id }}" data-name="{{ $ibGroup->name }}" class="action-btn deleteIbGroup">
                                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
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
                                    $from = $ibGroups->firstItem();
                                    $to = $ibGroups->lastItem();
                                    $total = $ibGroups->total();
                                @endphp
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ $from }}</span> to <span class="font-medium">{{ $to }}</span> of <span class="font-medium">{{ $total }}</span> results
                                </p>
                            </div>
                            {{ $ibGroups->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Create IB Group -->
    @include('backend.ib_group.modal.__create_group')

    <!-- Modal for Edit IB Group -->
    @include('backend.ib_group.modal.__edit_group')

    <!-- Modal for Delete IB Group -->
    @include('backend.ib_group.modal.__delete_group')

@endsection

@section('user-management-script')

    <script>
        // Edit IB Group
        $('body').on('click', '.edit-ib-group', function (event) {
            event.preventDefault();
            $('#edit-group-body').empty();
            var id = $(this).data('id'); // Get the ID from the clicked button

            var url = '{{ route("admin.ib-group.edit", ":id") }}'; // Use the correct route name
            url = url.replace(':id', id);

            $.get(url, function (data) {
                $('#edit-group-body').append(data);
                $('#editIbGroupModal').modal('show'); // Correct modal ID
                $('.summernote').summernote({
                    height: 150,
                    minHeight: null,
                    maxHeight: null,
                    focus: true,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    callbacks: {
                        onChange: function(contents, $editable) {

                            var markupStr = contents;
                            markupStr = markupStr.replace(/</g, '{').replace(/>/g, '}');

                            var html_container = $(this).closest('.input-area').find('input[type="hidden"]');

                            html_container.val(markupStr);
                        }
                    }
                });
                $('#rebate_rule_id_edit').select2();
            }).fail(function () {
                alert('Error loading the edit form.');
            });
        });

        // Delete IB Group
        $('.deleteIbGroup').on('click', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.ib-group.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#ibGroupDeleteForm').attr('action', url)

            $('.name').html(name);
            $('#deleteIbGroup').modal('show');
        });
    </script>
@endsection
