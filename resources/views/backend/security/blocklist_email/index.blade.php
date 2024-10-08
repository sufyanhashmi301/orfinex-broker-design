@extends('backend.security.index')
@section('title')
    {{ __('Blocklist Email') }}
@endsection
@section('security-content')
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="data-table">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('#') }}</th>
                                    <th scope="col" class="table-th">{{ __('Blacklist Email') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                <tr>
                                    <td class="table-td">1</td>
                                    <td class="table-td">Naeemali@gmail..com</td>
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
                                    <td class="table-td">naeemali2020@gmail.com</td>
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
