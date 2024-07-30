@extends('backend.layouts.app')
@section('title')
    {{ __('Departments') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Departments') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.departments.create') }}" class="inline-flex items-center justify-center text-success-500">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus-circle"></iconify-icon>
                {{ __('Add New') }}
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-6 pt-0">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Department Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            @foreach($departments as $department)
                                <tr>
                                    <td class="table-td">
                                        <strong>{{ $department->name }}</strong>
                                    </td>
                                    <td class="table-td">
                                        @if( $department->status==1)
                                            <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">{{ __('Active') }}</div>
                                        @else
                                            <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">{{ __('Disabled') }}</div>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a href="{{ route('admin.departments.edit',$department->id) }}" class="action-btn">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </a>
                                            <button type="button" data-id="{{ $department->id }}" data-name="{{ $department->name }}" class="action-btn deleteDepartment">
                                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Delete deleteDepartment -->
    @include('backend.departments.include.__delete')
    <!-- Modal for Delete deleteDepartment-->
@endsection
@section('script')
    <script>
        $('.deleteDepartment').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.departments.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#departmentDeleteForm').attr('action', url)

            $('.name').html(name);
            $('#deleteDepartment').modal('show');
        })
    </script>
@endsection