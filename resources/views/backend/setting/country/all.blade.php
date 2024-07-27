@extends('backend.setting.country.index')
@section('title')
    {{ __('Countries') }}
@endsection
@section('country-content')
    <div class="card">
        <div class="card-body px-6 pb-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Country') }}</th>
                                    <th scope="col" class="table-th">{{ __('Country Code') }}</th>
                                    <th scope="col" class="table-th">{{ __('ISO 3166-2') }}</th>
                                    <th scope="col" class="table-th">{{ __('ISO 3166-3') }}</th>
                                    <th scope="col" class="table-th">{{ __('Currency Code') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                <tr>
                                    <td class="table-td">
                                        429
                                    </td>
                                    <td class="table-td">
                                        New York
                                    </td>
                                    <td class="table-td">
                                        357
                                    </td>
                                    <td class="table-td">
                                        NZ
                                    </td>
                                    <td class="table-td">
                                        NZ
                                    </td>
                                    <td class="table-td">
                                        SGD
                                    </td>
                                    <td class="table-td">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" value="" class="sr-only peer" checked>
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <button class="action-btn" type="button">
                                                <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                            </button>
                                            <button class="action-btn" type="button">
                                                <iconify-icon icon="heroicons:trash"></iconify-icon>
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