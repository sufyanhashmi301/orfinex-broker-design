@extends('backend.setting.company.index')
@section('title')
    {{ __('Designations') }}
@endsection
@section('title-btn')
    <a href="{{ route('admin.designations.create') }}" class="btn btn-primary inline-flex items-center justify-center">
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
        {{ __('Add New') }}
    </a>
@endsection
@section('company-content')
    <div class="card">
        <div class="card-body p-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Designation Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            @foreach($designations as $designation)
                                <tr>
                                    <td class="table-td">
                                        <strong>{{ $designation->name }}</strong>
                                    </td>
                                    <td class="table-td">
                                        @if( $designation->status==1)
                                            <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">{{ __('Active') }}</div>
                                        @else
                                            <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">{{ __('Disabled') }}</div>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a href="{{ route('admin.designations.edit',$designation->id) }}" class="action-btn">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </a>
                                            <button type="button" data-id="{{ $designation->id }}" data-name="{{ $designation->name }}" class="action-btn deleteDesignation">
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
    @include('backend.designations.include.__delete')
    <!-- Modal for Delete deleteDepartment-->
@endsection
@section('script')
    <script>
        $('.deleteDesignation').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.designations.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#designationDeleteForm').attr('action', url)

            $('.name').html(name);
            $('#deleteDesignation').modal('show');
        })
    </script>
@endsection
