@extends('backend.layouts.app')
@section('title')
    {{ __('Leaderboard') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="grid md:grid-cols-3 grid-cols-1 gap-5 mb-5">
        <div class="card">
            <div class="card-body p-4">
                <div class="flex justify-between">
                    <div class="flex space-x-3 rtl:space-x-reverse">
                        <div class="flex-none">
                            <img src="{{ asset('frontend/images/highest-payout__badge.png') }}" alt="">
                        </div>
                        <div class="flex-1">
                            <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                {{ __('Highest Payout') }}
                            </div>
                            <div class="text-slate-900 dark:text-white text-lg font-medium">
                                {{ __('Jagroop D') }}
                            </div>
                        </div>
                    </div>
                    <button class="action-btn editBtn" type="button" data-title="Highest Payout">
                        <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                    </button>
                </div>
                <ul class="space-y-3 mt-4">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base">{{ __('$100,000') }}</span>
                        <span>
                            <span class="text-lg font-medium">{{ __('$123,00.23') }}</span>
                            <span class="text-success-500 ml-1">{{ __('+123%') }}</span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <div class="flex justify-between">
                    <div class="flex space-x-3 rtl:space-x-reverse">
                        <div class="flex-none">
                            <img src="{{ asset('frontend/images/best-ratio__badge.png') }}" alt="">
                        </div>
                        <div class="flex-1">
                            <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                {{ __('Best Ratio') }}
                            </div>
                            <div class="text-slate-900 dark:text-white text-lg font-medium">
                                {{ __('Diego C') }}
                            </div>
                        </div>
                    </div>
                    <button class="action-btn editBtn" type="button" data-title="Best Ratio">
                        <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                    </button>
                </div>
                <ul class="space-y-3 mt-4">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base">{{ __('$5,000') }}</span>
                        <span class="text-lg font-medium">{{ __('100%') }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <div class="flex justify-between">
                    <div class="flex space-x-3 rtl:space-x-reverse">
                        <div class="flex-none">
                            <img src="{{ asset('frontend/images/fastest-evalution__badge.png') }}" alt="">
                        </div>
                        <div class="flex-1">
                            <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                {{ __('Fastest Evalution') }}
                            </div>
                            <div class="text-slate-900 dark:text-white text-lg font-medium">
                                {{ __('Mayank S') }}
                            </div>
                        </div>
                    </div>
                    <button class="action-btn editBtn" type="button" data-title="Fastest Evalution">
                        <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                    </button>
                </div>
                <ul class="space-y-3 mt-4">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base">{{ __('$100,000') }}</span>
                        <span class="text-lg font-medium">{{ __('1d 0h 1m 10s') }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body relative p-4">
                <div class="flex items-center">
                    <span class="text-slate-900 dark:text-white text-lg font-medium mr-1">{{ __('Kai S') }}</span>
                </div>
                <div class="absolute top-0 right-4">
                    <img src="{{ asset('frontend/images/medal-1__badge.png') }}" alt="">
                </div>
                <ul class="space-y-3 mt-5">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="badge bg-success-500 text-white capitalize">{{ __('Buy') }}</span>
                        <span class="text-base">{{ __('$100,000') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base">{{ __('XAUUSD') }}</span>
                        <span class="text-base text-success-500">{{ __('$12,000') }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body relative p-4">
                <div class="flex items-center">
                    <span class="text-slate-900 dark:text-white text-lg font-medium mr-1">{{ __('Adib Z') }}</span>
                </div>
                <div class="absolute top-0 right-4">
                    <img src="{{ asset('frontend/images/medal-2__badge.png') }}" alt="">
                </div>
                <ul class="space-y-3 mt-5">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="badge bg-success-500 text-white capitalize">{{ __('Buy') }}</span>
                        <span class="text-base">{{ __('$100,000') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base">{{ __('XAUUSD') }}</span>
                        <span class="text-base text-success-500">{{ __('$12,000') }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body relative p-4">
                <div class="flex items-center">
                    <span class="text-slate-900 dark:text-white text-lg font-medium mr-1">{{ __('Ranger A') }}</span>
                </div>
                <div class="absolute top-0 right-4">
                    <img src="{{ asset('frontend/images/medal-3__badge.png') }}" alt="">
                </div>
                <ul class="space-y-3 mt-5">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="badge bg-danger-500 text-white capitalize">{{ __('Sell') }}</span>
                        <span class="text-base">{{ __('$100,000') }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base">{{ __('XAUUSD') }}</span>
                        <span class="text-base text-success-500">{{ __('$12,000') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between mb-3">
        <h4 class="text-lg text-slate-600 dark:text-white font-semibold">
            {{ __('Best account in profit') }}
        </h4>
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
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-td">{{ 1 }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <button class="action-btn" type="button">
                                            <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ 2 }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <button class="action-btn" type="button">
                                            <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ 3 }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <button class="action-btn" type="button">
                                            <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ 4 }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <button class="action-btn" type="button">
                                            <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ 5 }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <button class="action-btn" type="button">
                                            <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Modal for update leaderboard--}}
    @include('backend.leaderboard.modal.__edit')
@endsection
@section('script')
    <script !src="">
        $(document).ready(function() {
            // When the "Open Modal" button is clicked
            $('.editBtn').click(function () {

                var title = $(this).data('title');

                $('#modalTitle').text(title);
                $('#titleInput').val(title);

                $('#editLeaderBoardModal').modal('show');

            });
        })
    </script>
@endsection
