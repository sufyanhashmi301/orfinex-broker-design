@extends('backend.setting.lead.index')
@section('title')
    {{ __('Lead Source') }}
@endsection
@can('lead-source-create')
@section('title-btns')
    <a href="javascript:;" class="btn btn-sm btn-primary inline-flex items-center justify-center"
       type="button"
       data-bs-toggle="modal"
       data-bs-target="#newSourceModal">
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
        {{ __('Add New Source') }}
    </a>
@endsection
@endcan
@section('lead-setting-content')
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('#') }}</th>
                                    <th scope="col" class="table-th">{{ __('Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($sources as $source)
                                <tr>
                                    <td class="table-td">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="table-td">
                                        <span class="font-semibold">{{ $source->name }}</span>
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            @can('lead-source-edit')
                                            <button class="action-btn" id="editSource" data-id="{{ $source->id }}">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </button>
                                            @endcan
                                            @can('lead-source-delete')
                                            <button type="button" data-id="{{ $source->id }}" data-name="{{ $source->name }}" class="action-btn deleteSourceBtn">
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
                                    $from = $sources->firstItem(); // The starting item number on the current page
                                    $to = $sources->lastItem(); // The ending item number on the current page
                                    $total = $sources->total(); // The total number of items
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
                            {{ $sources->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--    Modal for new lead source --}}
    @include('backend.setting.lead.source.modal.__create_modal')

    {{--    Modal for edit source --}}
    @include('backend.setting.lead.source.modal.__edit_modal')

    {{--    Modal for delete source --}}
    @include('backend.setting.lead.source.modal.__delete_modal')

@endsection
@section('user-management-script')
    <script>
        $(document).ready(function() {

            $('body').on('click', '#editSource', function (event) {
                "use strict";
                event.preventDefault();
                $('#edit-source-body').empty();
                var id = $(this).data('id');

                $.get('source/' + id + '/edit', function (data) {

                    $('#editSourceModal').modal('show');
                    $('#edit-source-body').append(data);

                })
            });

            $('body').on('click', '.deleteSourceBtn', function (event) {
                "use strict";
                event.preventDefault();
                var id = $(this).data('id');
                var name = $(this).data('name');

                var url = '{{ route("admin.lead.source.destroy", ":id") }}';
                url = url.replace(':id', id);
                $('#sourceDeleteForm').attr('action', url)

                $('.name').html(name);
                $('#deleteSource').modal('show');
            });

        });
    </script>
@endsection
