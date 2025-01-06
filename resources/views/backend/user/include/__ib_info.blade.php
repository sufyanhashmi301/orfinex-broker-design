<div
    class="tab-pane space-y-5 fade"
    id="ib-info"
    role="tabpanel"
    aria-labelledby="ib-info-tab"
>
    <div class="card basicTable_wrapper">
        <div class="card-header noborder">
            <h4 class="card-title">{{ __('IB Account') }}</h4>
            <div class="flex flex-wrap flex-md-nowrap align-items-stretch gap-2 mb-2 mb-md-0">
                <span data-bs-toggle="modal" data-bs-target="#addIBModal">
                    <a href="javascript:void(0)" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                        {{ __('Approve IB Member') }}
                    </a>
                </span>
{{--                <span data-bs-toggle="modal" data-bs-target="#updateIBModal">--}}
{{--                    <a href="javascript:void(0)" class="btn btn-dark btn-sm inline-flex items-center justify-center">--}}
{{--                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>--}}
{{--                        {{ __('Update IB') }}--}}
{{--                    </a>--}}
{{--                </span>--}}
{{--                <span data-bs-toggle="modal" data-bs-target="#addMIBModal">--}}
{{--                    <a href="javascript:void(0)" class="btn btn-dark btn-sm inline-flex items-center justify-center">--}}
{{--                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>--}}
{{--                        {{ __('Add New Multi IB') }}--}}
{{--                    </a>--}}
{{--                </span>--}}
{{--                <span data-bs-toggle="modal" data-bs-target="#updateMIBModal">--}}
{{--                    <a href="javascript:void(0)" class="btn btn-dark btn-sm inline-flex items-center justify-center">--}}
{{--                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>--}}
{{--                        {{ __('Update Multi IB') }}--}}
{{--                    </a>--}}
{{--                </span>--}}
            </div>
        </div>
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('IB Group') }}</th>
                                    <th scope="col" class="table-th">{{ __('status') }}</th>
                                    <th scope="col" class="table-th"></th>

                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                <tr>
                                    <td class="table-td">
                                        {{ isset($user->ib_group_id) ? $user->ibGroup->name : 'N/A' }}
                                    </td>
                                    <td class="table-td">
                                        {{ ucfirst($user->ib_status)}}
                                    </td>
                                </tr>
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

            var table = $('#ib-info-dataTable').DataTable({
                dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5'lip>",
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
                ajax: "{{ route('admin.user.ib-info',$user->id) }}",
                columns: [
                    {data: 'ib_login', name: 'ib_login'},
                    {data: 'ib_balance', name: 'ib_balance'},
                    {data: 'ib_status', name: 'ib_status'},
                    // {data: 'action', name: 'action'},

                ]
            });
        })(jQuery);


        $('#ibGroupIDSelect').select2({
            dropdownParent: $('#addIBModal')
        });

    </script>
@endpush
