<div
    class="tab-pane fade"
    id="pills-direct-referral"
    role="tabpanel"
    aria-labelledby="pills-direct-referral-tab"
>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                {{ __('Direct referrals of :name',['name'=>$user->full_name]) }}
            </h4>
            @can('user-direct-referral-create')
                <span data-bs-toggle="modal" data-bs-target="#addReferralModal">
                    <a href="javascript:void(0)" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                        {{ __('Add Referral') }}
                    </a>
                </span>
            @endcan
        </div>
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="user-referral-dataTable">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Avatar') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Email') }}</th>
                                    <th scope="col" class="table-th">{{ __('Balance') }}</th>
                                    <th scope="col" class="table-th">{{ __('KYC') }}</th>
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
</div>
@push('single-script')
    <script>
        (function ($) {
            "use strict";
            $('#user-referral-dataTable').DataTable({
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
                ajax: "{{ route('admin.referral.direct.list',$user->id) }}",
                columns: [
                    {"class": "table-td", data: 'avatar', name: 'avatar'},
                    {"class": "table-td", data: 'full_name', name: 'full_name'},
                    {"class": "table-td", data: 'email', name: 'email'},
                    {"class": "table-td", data: 'balance', name: 'balance'},
                    {"class": "table-td", data: 'kyc', name: 'kyc'},
                    {"class": "table-td", data: 'status', name: 'status'},
                    {"class": "table-td", data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        })(jQuery);

        $('.select2').select2({
            dropdownParent: $('#addReferralModal')
        });
    </script>
@endpush
