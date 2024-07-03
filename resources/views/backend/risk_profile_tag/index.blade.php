@extends('backend.layouts.app')
@section('title')
    {{ __('Risk Profile Tag') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Risk Profile Tag Forms') }}</h4>
            <a href="{{ route('admin.risk-profile-tag.create') }}" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus-circle"></iconify-icon>
                {{ __('Add New') }}
            </a>
        </div>
        <div class="card-body p-6 pt-0">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Tag Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            @foreach($riskProfileTags as $riskProfileTag)
                                <tr>
                                    <td class="table-td">
                                        <strong>{{ $riskProfileTag->name }}</strong>
                                    </td>
                                    <td class="table-td">
                                        @if( $riskProfileTag->status)
                                            <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">{{ __('Active') }}</div>
                                        @else
                                            <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">{{ __('Disabled') }}</div>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a href="{{ route('admin.risk-profile-tag.edit',$riskProfileTag->id) }}" class="action-btn">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </a>
                                            <button type="button" data-id="{{ $riskProfileTag->id }}" data-name="{{ $riskProfileTag->name }}" class="action-btn deleteRiskProfileTag">
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
    <!-- Modal for Delete deleteRiskProfileTagType -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="deleteRiskProfileTag"
        tabindex="-1"
        aria-labelledby="deleteRiskProfileTag"
        aria-hidden="true"
    >
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body p-6 py-8 text-center space-y-5">
                    <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger-500 text-danger-500 bg-opacity-30">
                        <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                    </div>
                    <div class="title">
                        <h4 class="text-xl font-medium dark:text-white capitalize">
                            {{ __('Are you sure?') }}
                        </h4>
                    </div>
                    <p>
                        {{ __('You want to Delete') }} 
                        <strong class="name"></strong> {{ __('Risk Profile Tag?') }}
                    </p>
                    <form method="post" id="riskProfileTagEditForm">
                        @method('DELETE')
                        @csrf
                        <div class="action-btns">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __(' Confirm') }}
                            </button>
                            <a href="" class="btn btn-danger inline-flex items-center justify-center" type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Delete deleteRiskProfileTagType-->
@endsection
@section('script')
    <script>
        $('.deleteRiskProfileTag').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.risk-profile-tag.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#riskProfileTagEditForm').attr('action', url)

            $('.name').html(name);
            $('#deleteRiskProfileTag').modal('show');
        })
    </script>
@endsection