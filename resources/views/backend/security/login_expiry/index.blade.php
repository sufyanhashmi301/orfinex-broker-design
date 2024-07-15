@extends('backend.security.index')
@section('title')
    {{ __('Login Expiry') }}
@endsection
@section('security-content')
    <div class="card">
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="data-table">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('#') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Expiry Date') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                <tr>
                                    <td class="table-td">1</td>
                                    <td class="table-td">
                                        <a href="javascript:;" class="flex">
                                            <span class="w-8 h-8 rounded-[100%] bg-slate-100 text-slate-900 dark:bg-slate-600 dark:text-slate-200 flex flex-col items-center justify-center font-normal capitalize ltr:mr-3 rtl:ml-3">
                                                NA
                                            </span>
                                            <div>
                                                <span class="text-sm text-slate-900 dark:text-white block capitalize">
                                                    Naeem Ali
                                                </span>
                                                <span class="text-xs text-slate-500 dark:text-slate-300">
                                                    Junior
                                                </span>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="table-td">10-1-2024</td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a href="" class="action-btn">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </a>
                                            <a href="" class="action-btn">
                                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">2</td>
                                    <td class="table-td">
                                        <a href="javascript:;" class="flex">
                                            <span class="w-8 h-8 rounded-[100%] bg-slate-100 text-slate-900 dark:bg-slate-600 dark:text-slate-200 flex flex-col items-center justify-center font-normal capitalize ltr:mr-3 rtl:ml-3">
                                                NA
                                            </span>
                                            <div>
                                                <span class="text-sm text-slate-900 dark:text-white block capitalize">
                                                    Naeem Ali
                                                </span>
                                                <span class="text-xs text-slate-500 dark:text-slate-300">
                                                    Junior
                                                </span>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="table-td">15-1-2-24</td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a href="" class="action-btn">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </a>
                                            <a href="" class="action-btn">
                                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
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
@endsection