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
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                @can('user-direct-referral-create')
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addReferralModal" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                        {{ __('Add Referral') }}
                    </a>
                @endcan
                <form id="filter-form" method="POST" action="{{ route('admin.user.export', ['type' => 'refferal', 'user_id' => $user->id]) }}">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm inline-flex items-center justify-center min-w-max">
                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lets-icons:export-fill"></iconify-icon>
                        {{ __('Export') }}
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="user-referral-dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Avatar') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Email') }}</th>
                                    <th scope="col" class="table-th">{{ __('Phone') }}</th>
                                    <th scope="col" class="table-th">{{ __('Balance') }}</th>
                                    <th scope="col" class="table-th">{{ __('KYC') }}</th>
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
        </div>
    </div>
</div>
@push('single-script')
    <script>
        (function ($) {
            "use strict";
            $('#user-referral-dataTable').DataTable({
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
                ajax: "{{ route('admin.referral.direct.list',$user->id) }}",
                columns: [
                    {data: 'avatar', name: 'avatar'},
                    {data: 'full_name', name: 'full_name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'balance', name: 'balance'},
                    {data: 'kyc', name: 'kyc'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        })(jQuery);

        $('#countrySelect').select2({
            dropdownParent: $('#addReferralModal')
        });

    </script>
@endpush
