@extends('backend.symbol_groups.index')
@section('title')
    {{ __('All Symbol Groups') }}
@endsection
@section('title-btns')
    <a href="" class="btn btn-white inline-flex items-center justify-center">
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
        {{ __('Back') }}
    </a>
    <a href="" class="btn btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#symbolGroupModal">
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
                                    <th scope="col" class="table-th">{{ __('Symbol ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Symbol') }}</th>
                                    <th scope="col" class="table-th">{{ __('Path') }}</th>
                                    <th scope="col" class="table-th">{{ __('Description') }}</th>
                                    <th scope="col" class="table-th">{{ __('Contact Size') }}</th>
                                    <th scope="col" class="table-th">
                                        <div class="flex items-center">
                                            <span>{{ __('Status') }}</span>
                                            <span class="toolTip onTop leading-none" data-tippy-content="primary tooltip!" data-tippy-theme="dark">
                                                <iconify-icon class="text-lg ltr:ml-2 rtl:mr-2" icon="lucide:info"></iconify-icon>
                                            </span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-td">
                                        {{ __('429') }}
                                    </td>
                                    <td class="table-td">
                                        {{ __('AUDCAD') }}
                                    </td>
                                    <td class="table-td">
                                        {{ __('Classic: Market\Forex\ADUCAD') }}
                                    </td>
                                    <td class="table-td">
                                        {{ __('Australian Dollar - Canadian DollarAustralian Dollar - Canadian Dollar') }}
                                    </td>
                                    <td class="table-td">
                                        {{ __('100000') }}
                                    </td>
                                    <td class="table-td">
                                        <div class="form-switch ps-0" style="line-height:0;">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-checkbox">
                                                <input type="checkbox" name="" value="1" class="sr-only peer" checked>
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">
                                        {{ __('429') }}
                                    </td>
                                    <td class="table-td">
                                        {{ __('AUDCAD') }}
                                    </td>
                                    <td class="table-td">
                                        {{ __('Classic: Market\Forex\ADUCAD') }}
                                    </td>
                                    <td class="table-td">
                                        {{ __('Australian Dollar - Canadian DollarAustralian Dollar - Canadian Dollar') }}
                                    </td>
                                    <td class="table-td">
                                        {{ __('100000') }}
                                    </td>
                                    <td class="table-td">
                                        <div class="form-switch ps-0" style="line-height:0;">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-checkbox">
                                                <input type="checkbox" name="" value="1" class="sr-only peer" checked>
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">
                                        {{ __('429') }}
                                    </td>
                                    <td class="table-td">
                                        {{ __('AUDCAD') }}
                                    </td>
                                    <td class="table-td">
                                        {{ __('Classic: Market\Forex\ADUCAD') }}
                                    </td>
                                    <td class="table-td">
                                        {{ __('Australian Dollar - Canadian DollarAustralian Dollar - Canadian Dollar') }}
                                    </td>
                                    <td class="table-td">
                                        {{ __('100000') }}
                                    </td>
                                    <td class="table-td">
                                        <div class="form-switch ps-0" style="line-height:0;">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-checkbox">
                                                <input type="checkbox" name="" value="1" class="sr-only peer" checked>
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
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
