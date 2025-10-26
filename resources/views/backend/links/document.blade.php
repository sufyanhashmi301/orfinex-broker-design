@extends('backend.links.index')
@section('page-title')
    <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
        {{ __('Document Links') }}
    </h4>
    @can('document-link-create')
    <a href="javascript:;" class="btn btn-sm btn-dark inline-flex items-center justify-center" data-bs-toggle="modal" data-bs-target="#newDocumentModal">
        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="lucide:plus"></iconify-icon>
        {{ __('Add New') }}
    </a>
    @endcan
@endsection
@section('links-content')
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8 hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="documentLink-dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Title') }}</th>
                                    <th scope="col" class="table-th">{{ __('URL') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="processingIndicator text-center">
                {{-- <img src="{{ asset('global/images/loading.gif') }}" class="inline-block h-20" alt="Loader"> --}}
                <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
            </div>
        </div>
    </div>

    {{--Modal for new document link--}}
    @can('document-link-create')
    @include('backend.links.modal.__new_document')
    @endcan

    {{--Modal for update document link--}}
    @can('document-link-edit')
    @include('backend.links.modal.__edit_document')
    @endcan

    {{--Modal for delete document link--}}
    @can('document-link-delete')
    @include('backend.links.modal.__delete_document')
    @endcan

@endsection
@section('script')
    <script !src="">
        (function ($) {
            "use strict";
            var table = $('#documentLink-dataTable')

            .on('processing.dt', function(e, settings, processing) {
                $('.processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
                dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                searching: false,
                lengthChange: false,
                info: true,
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                        next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                    },
                    search: "Search:"
                },
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('admin.links.document.index') }}",
                columns: [
                    {data: 'title', name: 'title'},
                    {data: 'link', name: 'link'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        })(jQuery);

        $('body').on('click', '.editBtn', function (event){
            "use strict";
            event.preventDefault();
            $('#edit-document-body').empty();
            var recordId = $(this).data('id');
            var url = "{{ route('admin.links.document.edit', ':id') }}".replace(':id', recordId);

            $.get(url, function (response) {
                $('#editDocumentModal').modal('show');
                $('#edit-document-body').append(response);
                tippy(".shift-Away", {
                    placement: "top",
                    animation: "shift-away"
                });
            });
        });

        $('body').on('click', '.deleteBtn', function (event) {
            "use strict";
            event.preventDefault();
            var id = $(this).data('id');

            var url = '{{ route("admin.links.document.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#documentLinkDeleteForm').attr('action', url)

            $('#deleteDocumentLink').modal('show');

        });
    </script>
@endsection
