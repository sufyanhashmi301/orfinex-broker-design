@extends('backend.setting.company.index')
@section('title')
    {{ __('Departments') }}
@endsection
@section('title-btn')
    <a href="#" class="btn btn-sm btn-primary inline-flex items-center justify-center addDepartment">
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
        {{ __('Add New') }}
    </a>
@endsection
@section('company-content')
    @if($dataCount > 0)
        <div class="card">
            <div class="card-body relative px-6 pt-3">
                <div class="overflow-x-auto -mx-6 dashcode-data-table">
                    <span class=" col-span-8 hidden"></span>
                    <span class="  col-span-4 hidden"></span>
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden ">
                            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="departments-dataTable">
                                <thead>
                                    <tr>
                                        <th scope="col" class="table-th">{{ __('Name') }}</th>
                                        <th scope="col" class="table-th">{{ __('Parent Category') }}</th>
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
                <div id="processingIndicator" class="text-center">
                    {{-- <img src="{{ asset('global/images/loading.gif') }}" class="inline-block h-20" alt="Loader"> --}}
                    <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
                </div>
            </div>
        </div>
    @else
        <div class="card basicTable_wrapper items-center justify-center">
            <div class="card-body p-6">
                <div class="max-w-4xl mx-auto text-center">
                    <img src="{{ asset('backend/images/territory_get_started.svg') }}" class="inline-flex mb-5" alt="">
                    <p class="text-base font-semibold mb-3 dark:text-white">
                        {{ __('Department Management') }}
                    </p>
                    <p class="card-text">
                        {{ __('Department Management allows you to organize teams and departments within your company for better coordination and oversight. Use this feature to streamline operations and enhance collaboration across your organization.') }}
                    </p>
                    <div class="text-center mt-5">
                        <a href="javascript:;" type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="material-symbols:resume"></iconify-icon>
                            {{ __('Resume') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal for Delete deleteDepartment -->
    @include('backend.departments.include.__create')
    @include('backend.departments.include.__delete')
    @include('backend.departments.include.__edit')
    <!-- Modal for Delete deleteDepartment-->
@endsection
@section('organization-script')
    <script>
        (function ($) {
            "use strict";

            var table = $('#departments-dataTable')
            .on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
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
                ajax: "{{ route('admin.departments.index') }}",
                columns: [
                    {data: 'name', name: 'name',orderable : false},
                    {data: 'parent_category', name: 'parent_category',orderable : false},
                    {data: 'status', name: 'status',orderable : false},
                    {data: 'action', name: 'action',orderable : false},
                ]
            });
        })(jQuery);
        $('.addDepartment').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("admin.departments.create") }}',
                method: 'GET',
                success: function(response) {
                    console.log('dep',response);
                    var departments = response.departments;

                    $('#addDepartment select[name="parent_id"]').empty().append('<option value="">This is Parent</option>');

                    $.each(departments, function(index, dept) {
                        $('#addDepartment select[name="parent_id"]').append('<option value="'+dept.id+'">'+dept.name+'</option>');
                    });

                    $('#addDepartment').modal('show');
                }
            });
        });
        $('body').on('click', '.deleteDepartment', function (event) {

            "use strict";
            event.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.departments.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#departmentDeleteForm').attr('action', url)

            $('.name').html(name);
            $('#deleteDepartment').modal('show');
        })

        $('body').on('click', '.editDepartment', function (event) {
            "use strict";
            event.preventDefault();
            $('#edit-department-body').empty();
            var id = $(this).data('id');

            $.get('departments/' + id + '/edit', function (data) {

                $('#editDepartmentModal').modal('show');
                $('#edit-department-body').append(data);

            })
        })
    </script>
@endsection
