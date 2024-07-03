@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Staff') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Manage Staffs') }}</h4>
            @can('staff-create')
                <a href="" class="btn btn-dark btn-sm inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#staffModal">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus-circle"></iconify-icon>
                    {{ __('Add New Staff') }}
                </a>
            @endcan
        </div>
        <div class="card-body p-6 pt-0">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Email') }}</th>
                                    <th scope="col" class="table-th">{{ __('Role') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($staffs as $staff)
                                <tr>
                                    <td class="table-td">
                                        <strong>{{$staff->name}}</strong>
                                    </td>
                                    <td class="table-td">{{ $staff->email }}</td>
                                    <td class="table-td">
                                        <strong class="bg-slate-900 text-white capitalize rounded-full py-1 px-3">
                                            {{ $staff->getRoleNames()->first() }}
                                        </strong>
                                    </td>
                                    <td class="table-td">
                                        @if($staff->status)
                                            <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                                {{ __('Active') }}
                                            </div>
                                        @else
                                            <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
                                                {{ __('InActive') }}
                                            </div>
                                        @endif

                                    </td>
                                    <td class="table-td">
                                        @if($staff->getRoleNames()->first() === 'Super-Admin')
                                            <button class="toolTip onTop action-btn" type="button"
                                                data-tippy-theme="tooltip"
                                                data-tippy-content="Not Editable">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </button>
                                        @else
                                            @can('staff-edit')
                                                <button class="toolTip onTop action-btn"
                                                    data-id="{{$staff->id}}" type="button" id="edit"
                                                    data-tippy-theme="tooltip"
                                                    data-tippy-content="Edit Staff">
                                                    <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                </button>
                                            @endcan
                                        @endif
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

    <!-- Modal for Add New Staff -->
    @can('staff-create')
        @include('backend.staff.modal.__new_staff')
    @endcan
    <!-- Modal for Add New Staff-->

    <!-- Modal for Edit Staff -->
    @can('staff-edit')
        @include('backend.staff.modal.__edit_staff')
    @endcan
    <!-- Modal for Edit Staff-->

@endsection

@section('script')
    <script>

        $('body').on('click', '#edit', function (event) {
            "use strict";
            event.preventDefault();
            $('#edit-staff-body').empty();
            var id = $(this).data('id');

            $.get('staff/' + id + '/edit', function (data) {

                $('#editModal').modal('show');
                $('#edit-staff-body').append(data);

            })
        })

    </script>
@endsection
