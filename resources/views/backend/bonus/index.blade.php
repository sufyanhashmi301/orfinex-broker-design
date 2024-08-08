@extends('backend.layouts.app')
@section('content')
    <div class="card">
        <div class="card-header noborder">
            <h4 class="card-title">
                {{ __('Bonus')}}
            </h4>
            <a href="{{ url('admin/bonus/create') }}" class="btn btn-dark btn-sm inline-flex items-center justify-center" type="button">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                ADD NEW
            </a>
        </div>
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="data-table">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Bonus Title') }}</th>
                                    <th scope="col" class="table-th">{{ __('Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Process') }}</th>
                                    <th scope="col" class="table-th">{{ __('Applicable by') }}</th>
                                    <th scope="col" class="table-th">{{ __('Start Date') }}</th>
                                    <th scope="col" class="table-th">{{ __('End Date') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                <tr>
                                    <td class="table-td">
                                        <strong>100% Bonus</strong>
                                    </td>
                                    <td class="table-td">
                                        <span class="badge bg-slate-900 text-white capitalize">In Percentage</span>
                                    </td>
                                    <td class="table-td">On Deposit</td>
                                    <td class="table-td">Auto Apply</td>
                                    <td class="table-td">Jan 01 2024</td>
                                    <td class="table-td">Mar 01 2024</td>
                                    <td class="table-td">
                                        <a href="" class="action-btn" data-bs-toggle="tooltip" title="" data-bs-original-title="Edit Record" aria-label="Edit Record">
                                            <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                        </a>
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
