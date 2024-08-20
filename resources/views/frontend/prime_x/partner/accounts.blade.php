@extends('frontend::layouts.partner')
@section('title')
    {{ __('Accounts') }}
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
                    <label for="" class="min-w-max text-sm text-slate-600">{{ __('Country:') }}</label>
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
                <div class="input-area flex items-center space-x-3">
                    <label for="" class="min-w-max text-sm text-slate-600">{{ __('Platform Type:') }}</label>
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
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-body p-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Account Number') }}</th>
                                    <th scope="col" class="table-th">{{ __('Client Name/Country') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account Type/Currency') }}</th>
                                    <th scope="col" class="table-th">{{ __('Platform/NDA') }}</th>
                                    <th scope="col" class="table-th">{{ __('Islamic') }}</th>
                                    <th scope="col" class="table-th">{{ __('Leverage/Deposit') }}</th>
                                    <th scope="col" class="table-th">{{ __('Bonus') }}</th>
                                    <th scope="col" class="table-th">{{ __('Volume/Commission') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-td">
                                        <span class="block">{{ __('32423233432') }}</span>
                                        <span class="text-success-500">{{ __('Live') }}</span>
                                    </td>
                                    <td class="table-td">
                                        <span class="block">{{ __('Asad Nisar') }}</span>
                                        <span>{{ __('Pakistan') }}</span>
                                    </td>
                                    <td class="table-td">
                                        <span class="block">{{ __('Standard MT4') }}</span>
                                        <span>{{ __('USD') }}</span>
                                    </td>
                                    <td class="table-td">
                                        <span class="block">{{ __('MT4') }}</span>
                                        <span>{{ __('NO') }}</span>
                                    </td>
                                    <td class="table-td">{{ __('NO') }}</td>
                                    <td class="table-td">{{ __('300/0') }}</td>
                                    <td class="table-td">{{ __('3434') }}</td>
                                    <td class="table-td">{{ __('300/0') }}</td>
                                    <td class="table-td">
                                        <a href="" class="action-btn">
                                            <iconify-icon icon="lucide:edit"></iconify-icon>
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
