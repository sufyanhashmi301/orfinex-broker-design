@extends('frontend::layouts.user')
@section('title')
    {{ __('Certificates') }}
@endsection
@section('content')
    <div class="card mb-6">
        <div class="card-body p-6">
            <h4 class="card-title mb-2">
                {{ __('Leaderboard') }}
            </h4>
            <p class="card-text">
                {{ __('Overview of currently most profitable active :siteTitle Accounts.', ['siteTitle' => setting('site_title', 'global')]) }}
            </p>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <ul class="nav nav-tabs custom-tabs inline-flex items-center overflow-hidden rounded list-none border-0 pl-0">
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary active">
                    All
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    10k
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    25k
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    50k
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    100k
                </a>
            </li>
        </ul>
    </div>

    <div class="card mb-6">
        <div class="card-body p-6 pt-3">
            <!-- BEGIN: Company Table -->
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('#') }}</th>
                                    <th scope="col" class="table-th">{{ __('Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Profit') }}</th>
                                    <th scope="col" class="table-th">{{ __('Equity') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account size') }}</th>
                                    <th scope="col" class="table-th">{{ __('Gain %') }}</th>
                                    <th scope="col" class="table-th">{{ __('Country') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-td">{{ __('1') }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                    <img src="{{ asset('frontend/images/flags/usa-flag.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                                </div>
                                            </div>
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    {{ __('USA') }}
                                                </h4>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ __('1') }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                    <img src="{{ asset('frontend/images/flags/usa-flag.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                                </div>
                                            </div>
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    {{ __('USA') }}
                                                </h4>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ __('1') }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                    <img src="{{ asset('frontend/images/flags/usa-flag.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                                </div>
                                            </div>
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    {{ __('USA') }}
                                                </h4>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ __('1') }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                    <img src="{{ asset('frontend/images/flags/usa-flag.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                                </div>
                                            </div>
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    {{ __('USA') }}
                                                </h4>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ __('1') }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                    <img src="{{ asset('frontend/images/flags/usa-flag.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                                </div>
                                            </div>
                                            <div class="flex-1 text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    {{ __('USA') }}
                                                </h4>
                                            </div>
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
    @include('frontend::utilities.__comingSoon_modal')
@endsection
@section('script')
    <script>
        $( document ).ready(function() {
            $('#comingSoonModal').modal('show');
        });
    </script>
@endsection
