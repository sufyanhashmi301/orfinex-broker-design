@extends('backend.layouts.app')
@section('title')
    {{ __('Discount Codes') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="javascript:;" class="btn btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#newDiscountModal">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                {{ __('New Discount Code') }}
            </a>
        </div>
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
                                <th scope="col" class="table-th">{{ __('Code Name') }}</th>
                                <th scope="col" class="table-th">{{ __('Type') }}</th>
                                <th scope="col" class="table-th">{{ __('Value') }}</th>
                                <th scope="col" class="table-th">{{ __('Applies To') }}</th>
                                <th scope="col" class="table-th">{{ __('Usage Limit') }}</th>
                                <th scope="col" class="table-th">{{ __('Expires On') }}</th>
                                <th scope="col" class="table-th">{{ __('Status') }}</th>
                                <th scope="col" class="table-th">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                <tr>
                                    <td class="table-td">{{ __('Save20') }}</td>
                                    <td class="table-td">{{ __('Fixed') }}</td>
                                    <td class="table-td">{{ __('$50') }}</td>
                                    <td class="table-td">{{ __('Challenge Traders') }}</td>
                                    <td class="table-td">{{ __('Unlimited') }}</td>
                                    <td class="table-td">{{ __('11-30-2-24') }}</td>
                                    <td class="table-td">
                                        <span class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">{{ __('Active') }}</span>
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a href="javascript" class="action-btn" data-bs-toggle="modal" data-bs-target="#editDiscountModal">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </a>
                                            <a href="javascript" class="action-btn" data-bs-toggle="modal" data-bs-target="#deleteDiscountModal">
                                                <iconify-icon icon="lucide:trash"></iconify-icon>
                                            </a>
                                            <a href="javascript" class="action-btn" data-bs-toggle="modal" data-bs-target="#disableDiscountModal">
                                                <iconify-icon icon="fe:disabled"></iconify-icon>
                                            </a>
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

    {{--Modal for discount create--}}
    @include('backend.discount.modals.__create')

    {{--Modal for discount update--}}
    @include('backend.discount.modals.__edit')

    {{--Modal for discount delete--}}
    @include('backend.discount.modals.__delete')

    {{--Modal for discount disable--}}
    @include('backend.discount.modals.__disable')
@endsection
@section('script')
    <script>
        (function ($) {
            "use strict";
            var table = $('#dataTable').DataTable({
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
                autoWidth: false,
            });
        })(jQuery);
    </script>
@endsection
