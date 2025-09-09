@extends('frontend::layouts.partner')
@section('title')
    {{ __('Clients') }}
@endsection
@section('content')
    <div class="flex justify-end space-x-2 items-center mb-6">
        <button class="btn btn-primary inline-flex items-center justify-center">
            {{ __('Invite with email') }}
        </button>
        <button class="btn btn-primary inline-flex items-center justify-center">
            {{ __('Invite with SMS') }}
        </button>
    </div>
    <div class="card p-6 mb-5">
        <h4 class="card-title mb-5">{{ __('Filter your reports') }}</h4>
        <form action="" method="post">
            <div class="grid lg:grid-cols-3 grid-cols-1 gap-7">
                <div class="input-area flex items-center space-x-3">
                    <label for="" class="min-w-max text-sm text-slate-600">{{ __('Campaign:') }}</label>
                    <select name="" class="select2 form-control w-full">
                        <option value="all">{{ __('All') }}</option>
                    </select>
                </div>
                <div class="input-area flex items-center space-x-3">
                    <label for="" class="min-w-max text-sm text-slate-600">{{ __('Report duration:') }}</label>
                    <select name="" class="select2 form-control w-full">
                        <option value="all">{{ __('All') }}</option>
                    </select>
                </div>
                <div class="input-area flex items-center space-x-3">
                    <label for="" class="min-w-max text-sm text-slate-600">{{ __('Search:') }}</label>
                    <select name="" class="select2 form-control w-full">
                        <option value="all">{{ __('All') }}</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Level') }}</th>
                                <th scope="col" class="table-th">{{ __('Campaign') }}</th>
                                <th scope="col" class="table-th">{{ __('Name/Country') }}</th>
                                <th scope="col" class="table-th">{{ __('Client Email') }}</th>
                                <th scope="col" class="table-th">{{ __('Mobile') }}</th>
                                <th scope="col" class="table-th">{{ __('KYC Status') }}</th>
                                <th scope="col" class="table-th">{{ __('Commission') }}</th>
                                <th scope="col" class="table-th">{{ __('Volume') }}</th>
                                <th scope="col" class="table-th">{{ __('Balance/Equity') }}</th>
                                <th scope="col" class="table-th">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-td">
                                        <span class="block">{{ __('Level 0') }}</span>
                                        <span>{{ __('23-12-2024') }}</span>
                                    </td>
                                    <td class="table-td">{{ __('-') }}</td>
                                    <td class="table-td">
                                        <span class="block">{{ __('Asad Nisar') }}</span>
                                        <span>{{ __('Pakistan') }}</span>
                                    </td>
                                    <td class="table-td">
                                        <span class="block">{{ __('asadsynt@gmail.com') }}</span>
                                        <span class="text-success">{{ __('Verified') }}</span>
                                    </td>
                                    <td class="table-td">
                                        <span class="block">{{ __('+92 328 4324626') }}</span>
                                        <span class="text-success">{{ __('Verified') }}</span>
                                    </td>
                                    <td class="table-td">
                                        <span class="badge bg-success text-success bg-opacity-30 capitalize">
                                            {{ __('Approved') }}
                                        </span>
                                    </td>
                                    <td class="table-td">{{ __('0') }}</td>
                                    <td class="table-td">{{ __('0.00') }}</td>
                                    <td class="table-td">{{ __('Level 0') }}</td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a href="" class="action-btn">
                                                <iconify-icon icon="lucide:edit"></iconify-icon>
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
