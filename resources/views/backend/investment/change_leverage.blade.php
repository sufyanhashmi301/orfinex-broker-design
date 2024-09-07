@extends('backend.layouts.app')
@section('title')
    {{ __('Change Leverage') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            @yield('title')
        </h4>
    </div>

    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Account Number') }}</th>
                                <th scope="col" class="table-th">{{ __('User') }}</th>
                                <th scope="col" class="table-th">{{ __('Account Type') }}</th>
                                <th scope="col" class="table-th">{{ __('Old Leverage') }}</th>
                                <th scope="col" class="table-th">{{ __('New Leverage') }}</th>
                                <th scope="col" class="table-th">{{ __('Currency') }}</th>
                                <th scope="col" class="table-th">{{ __('Balance') }}</th>
                                <th scope="col" class="table-th">{{ __('Equity') }}</th>
                                <th scope="col" class="table-th">{{ __('Time') }}</th>
                                <th scope="col" class="table-th">{{ __('Status') }}</th>
                                <th scope="col" class="table-th">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                <tr>
                                    <td>601073</td>
                                    <td>
                                        <a href="" class="flex">
                                            <span class="w-8 h-8 rounded-[100%] bg-slate-100 text-slate-900 dark:bg-slate-600 dark:text-slate-200 flex flex-col items-center justify-center font-normal capitalize ltr:mr-3 rtl:ml-3"> NA </span>
                                            <div>
                                                <span class="text-sm text-slate-900 dark:text-white block capitalize"> Sufyan9079 </span>
                                                <span class="text-xs text-slate-500 dark:text-slate-300"> sufyanhashmi301@gmail.com </span>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <strong> Business Account </strong>
                                    </td>
                                    <td>Check\Checking</td>
                                    <td>USD</td>
                                    <td>100</td>
                                    <td>
                                        <strong class="green-color">0 USD</strong>
                                    </td>
                                    <td>
                                        <div class="">876984</div>
                                    </td>
                                    <td>Aug 19, 2024 13:11</td>
                                    <td>
                                        <div class="badge bg-warning-500 text-warning-500 bg-opacity-30 capitalize"> Pending </div>
                                    </td>
                                    <td>
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <button type="button" class="btn btn-sm btn-light inline-flex items-center justify-center">
                                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                                <span>{{ __('Approve') }}</span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger inline-flex items-center justify-center">
                                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="mdi:close"></iconify-icon>
                                                <span>{{ __('Reject') }}</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>601073</td>
                                    <td>
                                        <a href="" class="flex">
                                            <span class="w-8 h-8 rounded-[100%] bg-slate-100 text-slate-900 dark:bg-slate-600 dark:text-slate-200 flex flex-col items-center justify-center font-normal capitalize ltr:mr-3 rtl:ml-3"> NA </span>
                                            <div>
                                                <span class="text-sm text-slate-900 dark:text-white block capitalize"> Sufyan9079 </span>
                                                <span class="text-xs text-slate-500 dark:text-slate-300"> sufyanhashmi301@gmail.com </span>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <strong> Business Account </strong>
                                    </td>
                                    <td>Check\Checking</td>
                                    <td>USD</td>
                                    <td>100</td>
                                    <td>
                                        <strong class="green-color">0 USD</strong>
                                    </td>
                                    <td>
                                        <div class="">876984</div>
                                    </td>
                                    <td>Aug 19, 2024 13:11</td>
                                    <td>
                                        <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize"> Approved </div>
                                    </td>
                                    <td>
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <button type="button" class="btn btn-sm btn-light inline-flex items-center justify-center cursor-not-allowed" disabled>
                                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                                <span>{{ __('Approve') }}</span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger inline-flex items-center justify-center cursor-not-allowed" disabled>
                                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="mdi:close"></iconify-icon>
                                                <span>{{ __('Reject') }}</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('#dataTable').DataTable({
            dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
            processing: true,
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
                search: "Search:",
                processing: '<iconify-icon icon="lucide:loader"></iconify-icon>'
            },
            serverSide: true,
            autoWidth: false,
        });
    </script>
@endsection
