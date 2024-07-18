@extends('backend.layouts.app')
@section('title')
    {{ __('KYC Levels') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('KYC Levels') }}
        </h4>
       
    </div>
    @include('backend.kyc.include.__menu')
    <div class="card">
        <div class="card-body p-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __(' Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            @foreach($kycLevels as $kyc)
                                <tr>
                                    <td class="table-td">
                                        <strong>{{ $kyc->name }}</strong>
                                    </td>
                                    <td class="table-td">
                                        @if( $kyc->status)
                                            <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                                {{ __('Active') }}
                                            </div>
                                        @else
                                            <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
                                                {{ __('Disabled') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                        <a href="{{ route('admin.kyclevels.edit',$kyc->id) }}" class="toolTip onTop action-btn">
                                            <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                        </a>
                                           
                                        <button type="button" data-id="{{ $kyc->id }}" data-name="{{ $kyc->name }}" class="toolTip onTop action-btn">
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
    <!-- Modal for Delete deleteKycType -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="deleteKyc"
        tabindex="-1"
        aria-labelledby="deleteKyc"
        aria-hidden="true"
    >
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                    <div class="modal-body popup-body p-6 py-8 text-center space-y-5">
                        <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger-500 text-danger-500 bg-opacity-30">
                            <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                        </div>
                        <div class="title">
                            <h4 class="text-xl font-medium dark:text-white capitalize">
                                {{ __('Are you sure?') }}
                            </h4>
                        </div>
                        <p>
                            {{ __('You want to Delete') }} <strong
                                class="name"></strong> {{ __('KYC Verification Type?') }}
                        </p>
                        <form method="post" id="kycEditForm">
                            @method('DELETE')
                            @csrf
                            <div class="action-btns text-center">
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
    </div>
    <!-- Modal for Delete deleteKycType-->
@endsection
@section('script')
    <script>
        $('.deleteKyc').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.kyc-form.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#kycEditForm').attr('action', url)

            $('.name').html(name);
            $('#deleteKyc').modal('show');
        })
    </script>
@endsection
