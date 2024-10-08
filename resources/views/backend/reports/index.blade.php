@extends('backend.layouts.app')
@section('title')
    {{ __('Reports') }}
@endsection
@section('content')

    <div class="card p-6 mb-5">
        <h4 class="card-title mb-4">{{ __('All Reports') }}</h4>
        <form action="">
            <div class="flex justify-between flex-wrap items-center">
                <div class="inline-flex sm:space-x-4 space-x-2 ltr:pr-4 rtl:pl-4 mb-2 sm:mb-0">
                    <div class="input-area relative">
                        <label class="sr-only" for="inlineFormSelectPref">Preference</label>
                        <select class="form-control h-9 w-100" id="inlineFormSelectPref">
                            <option>Choose...</option>
                            <option value="1" selected>Transactions</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="input-area relative">
                        <label class="sr-only" for="inlineFormInputGroupUsername">Username</label>
                        <input type="date" name="" class="form-control h-9 flatpickr flatpickr-input active">
                    </div>
                </div>
                <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                    <button type="submit" class="btn btn-sm inline-flex items-center justify-center bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter" class="text-sm"></iconify-icon>
                        <span class="ms-1">Apply Filters</span>
                    </button>
                    <a href="" class="btn btn-sm inline-flex items-center justify-center bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:download-cloud" class="text-sm"></iconify-icon>
                        <span class="ms-1">Download</span>
                    </a>
                    <button type="button" class="btn btn-sm inline-flex items-center justify-center bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white" data-bs-toggle="modal" data-bs-target="#configureModal">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:wrench" class="text-sm"></iconify-icon>
                        <span class="ms-1">Configure</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">User</th>
                                    <th scope="col" class="table-th">Email</th>
                                    <th scope="col" class="table-th">Transaction ID</th>
                                    <th scope="col" class="table-th">Type</th>
                                    <th scope="col" class="table-th">Amount</th>
                                    <th scope="col" class="table-th">Gateway</th>
                                    <th scope="col" class="table-th">Status</th>
                                    <th scope="col" class="table-th">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                <tr>
                                    <td class="table-td">
                                        <span class="fw-bold">NaeemAli9338</span>
                                    </td>
                                    <td class="table-td">test@test.com</td>
                                    <td class="table-td">TRXUG0ZGH1XUR</td>
                                    <td class="table-td">Signup Bonus</td>
                                    <td class="table-td">
                                        <strong class="text-success-500">+8 USD</strong>
                                    </td>
                                    <td class="table-td">System</td>
                                    <td class="table-td">
                                        Success
                                    </td>
                                    <td class="table-td">Jan 01 2024 12:17</td>
                                </tr>
                                <tr>
                                    <td class="table-td">
                                        <span class="fw-bold">NaeemMunir2507</span>
                                    </td>
                                    <td class="table-td">bo.augustin@falkcia.com</td>
                                    <td class="table-td">TRXD8LJHRWUWM</td>
                                    <td class="table-td">Signup Bonus</td>
                                    <td class="table-td">
                                        <strong class="text-success-500">+8 USD</strong>
                                    </td>
                                    <td class="table-td">System</td>
                                    <td class="table-td">Success</td>
                                    <td class="table-td">Jan 01 2024 11:57</td>
                                </tr>
                                <tr>
                                    <td class="table-td">
                                        <span class="fw-bold">test23945</span>
                                    </td>
                                    <td class="table-td">test2@gmail.com</td>
                                    <td class="table-td">TRXFCWBHCYYJE</td>
                                    <td class="table-td">Signup Bonus</td>
                                    <td class="table-td">
                                        <strong class="text-success-500">+8 USD</strong>
                                    </td>
                                    <td class="table-td">System</td>
                                    <td class="table-td">Success</td>
                                    <td class="table-td">Dec 18 2023 11:32</td>
                                </tr>
                                <tr>
                                    <td class="table-td">
                                        <span class="fw-bold">NaeemAli9338</span>
                                    </td>
                                    <td class="table-td">test@test.com</td>
                                    <td class="table-td">TRXUG0ZGH1XUR</td>
                                    <td class="table-td">Signup Bonus</td>
                                    <td class="table-td">
                                        <strong class="text-success-500">+8 USD</strong>
                                    </td>
                                    <td class="table-td">System</td>
                                    <td class="table-td">
                                        Success
                                    </td>
                                    <td class="table-td">Jan 01 2024 12:17</td>
                                </tr>
                                <tr>
                                    <td class="table-td">
                                        <span class="fw-bold">NaeemMunir2507</span>
                                    </td>
                                    <td class="table-td">bo.augustin@falkcia.com</td>
                                    <td class="table-td">TRXD8LJHRWUWM</td>
                                    <td class="table-td">Signup Bonus</td>
                                    <td class="table-td">
                                        <strong class="text-success-500">+8 USD</strong>
                                    </td>
                                    <td class="table-td">System</td>
                                    <td class="table-td">Success</td>
                                    <td class="table-td">Jan 01 2024 11:57</td>
                                </tr>
                                <tr>
                                    <td class="table-td">
                                        <span class="fw-bold">test23945</span>
                                    </td>
                                    <td class="table-td">test2@gmail.com</td>
                                    <td class="table-td">TRXFCWBHCYYJE</td>
                                    <td class="table-td">Signup Bonus</td>
                                    <td class="table-td">
                                        <strong class="text-success-500">+8 USD</strong>
                                    </td>
                                    <td class="table-td">System</td>
                                    <td class="table-td">Success</td>
                                    <td class="table-td">Dec 18 2023 11:32</td>
                                </tr>
                                <tr>
                                    <td class="table-td">
                                        <span class="fw-bold">NaeemAli9338</span>
                                    </td>
                                    <td class="table-td">test@test.com</td>
                                    <td class="table-td">TRXUG0ZGH1XUR</td>
                                    <td class="table-td">Signup Bonus</td>
                                    <td class="table-td">
                                        <strong class="text-success-500">+8 USD</strong>
                                    </td>
                                    <td class="table-td">System</td>
                                    <td class="table-td">
                                        Success
                                    </td>
                                    <td class="table-td">Jan 01 2024 12:17</td>
                                </tr>
                                <tr>
                                    <td class="table-td">
                                        <span class="fw-bold">NaeemMunir2507</span>
                                    </td>
                                    <td class="table-td">bo.augustin@falkcia.com</td>
                                    <td class="table-td">TRXD8LJHRWUWM</td>
                                    <td class="table-td">Signup Bonus</td>
                                    <td class="table-td">
                                        <strong class="text-success-500">+8 USD</strong>
                                    </td>
                                    <td class="table-td">System</td>
                                    <td class="table-td">Success</td>
                                    <td class="table-td">Jan 01 2024 11:57</td>
                                </tr>
                                <tr>
                                    <td class="table-td">
                                        <span class="fw-bold">test23945</span>
                                    </td>
                                    <td class="table-td">test2@gmail.com</td>
                                    <td class="table-td">TRXFCWBHCYYJE</td>
                                    <td class="table-td">Signup Bonus</td>
                                    <td class="table-td">
                                        <strong class="text-success-500">+8 USD</strong>
                                    </td>
                                    <td class="table-td">System</td>
                                    <td class="table-td">Success</td>
                                    <td class="table-td">Dec 18 2023 11:32</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('backend.reports.__configure_modal')

@endsection
