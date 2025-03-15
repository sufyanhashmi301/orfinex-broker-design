@extends('backend.symbol_groups.index')
@section('title')
    {{ __('MataTrader 5') }}
@endsection
@section('title-btns')
    <a href="" class="btn btn-white inline-flex items-center justify-center">
        {{ __('View All Symbol') }}
    </a>
    <a href="" class="btn btn-sm btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#symbolGroupModal">
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
        {{ __('Add Symbol Group') }}
    </a>
@endsection
@section('symbol-groups-content')
    <div class="card">
        <div class="card-body p-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Symbol Group') }}</th>
                                    <th scope="col" class="table-th">{{ __('Symbols') }}</th>
                                    <th scope="col" class="table-th">{{ __('Create Time') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-td">
                                        {{ __('429') }}
                                    </td>
                                    <td class="table-td">
                                        {{ __('Forex-1') }}
                                    </td>
                                    <td class="table-td">
                                        <ul class="flex flex-wrap items-center gap-3">
                                            <li class="badge badge-secondary uppercase">
                                                {{ __('EURUSD') }}
                                            </li>
                                            <li class="badge badge-secondary uppercase">
                                                {{ __('GBPUSD') }}
                                            </li>
                                            <li class="badge badge-secondary uppercase">
                                                {{ __('USDJPY') }}
                                            </li>
                                            <li class="badge badge-secondary uppercase">
                                                {{ __('EURCAD') }}
                                            </li>
                                            <li class="badge badge-secondary uppercase">
                                                {{ __('AUDCAD') }}
                                            </li>
                                            <li class="badge badge-secondary uppercase">
                                                {{ __('EURGBP') }}
                                            </li>
                                            <li class="badge badge-secondary uppercase">
                                                {{ __('AUDEUR') }}
                                            </li>
                                            <li class="badge badge-secondary uppercase">
                                                {{ __('USDINR') }}
                                            </li>
                                        </ul>
                                    </td>
                                    <td class="table-td">
                                        {{ __('15 May 2020 9:30 am') }}
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a href="" class="action-btn">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </a>
                                            <a href="" class="action-btn">
                                                <iconify-icon icon="lucide:trash"></iconify-icon>
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
