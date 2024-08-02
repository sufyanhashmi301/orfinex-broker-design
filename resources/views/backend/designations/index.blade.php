@extends('backend.setting.company.index')
@section('title')
    {{ __('Designations') }}
@endsection
@section('title-btn')
    <a href="#" class="btn btn-primary inline-flex items-center justify-center addDesignation">
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
        {{ __('Add New') }}
    </a>
@endsection
@section('company-content')

    <div class="card">
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="designations-dataTable">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Delete deleteDepartment -->
    @include('backend.designations.include.__create')
    @include('backend.designations.include.__edit')
    @include('backend.designations.include.__delete')
    <!-- Modal for Delete deleteDepartment-->
@endsection
@section('script')
    <script>
        (function ($) {
            "use strict";

            var table = $('#designations-dataTable')
            .on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
                dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-end items-center'p>",
                paging: true,
                ordering: true,
                info: false,
                searching: true,
                lengthChange: true,
                lengthMenu: [10, 25, 50, 100],
                language: {
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                    next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                },
                search: "Search:"
                },
                processing: true,
                serverSide: true,
                autoWidth: false,
                searching: false,
                ajax: "{{ route('admin.designations.index') }}",
                columns: [
                    {"class": "table-td", data: 'name', name: 'name',orderable : false},
                    {"class": "table-td", data: 'status', name: 'status',orderable : false},
                    {"class": "table-td", data: 'action', name: 'action',orderable : false},
                ]
            });
        })(jQuery);
        $('.addDesignation').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("admin.designations.create") }}',
                method: 'GET',
                success: function(response) {

                    var designations = response.designations;

                    $('#addDesignation select[name="parent_id"]').empty().append('<option value="">This is Parent</option>');

                    $.each(designations, function(index, dept) {
                        $('#addDesignation select[name="parent_id"]').append('<option value="'+dept.id+'">'+dept.name+'</option>');
                    });

                    $('#addDesignation').modal('show');
                }
            });
        });
        $('body').on('click', '.deleteDesignation', function (event) {

            "use strict";
            event.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.designations.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#designationDeleteForm').attr('action', url)

            $('.name').html(name);
            $('#deleteDesignation').modal('show');
        })
        $('body').on('click', '.editDesignation', function (event) {
            "use strict";
            event.preventDefault();
            $('#edit-designation-body').empty();
            var id = $(this).data('id');

            $.get('designations/' + id + '/edit', function (data) {

                $('#editDesignationModal').modal('show');
                $('#edit-designation-body').append(data);

            })
        })
    </script>
@endsection
